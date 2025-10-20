<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use App\Models\User;

class PaymentController extends Controller
{
    protected InvoiceApi $invoiceApi;

    public function __construct()
    {
        // Ambil API key dari config/xendit.php
        Configuration::setXenditKey(config('xendit.secret_key'));
        $this->invoiceApi = new InvoiceApi();
    }

    /**
     * Membuat transaksi dan langsung redirect ke Hosted Invoice Page (UI bawaan Xendit)
     */
    public function createTransaction(Request $request)
    {
        try {
            $request->validate(['plan' => 'required|in:free,pro,plus']);
            $user = Auth::user();
            $planKey = $request->plan;

            $plans = config('plans.plans');
            $usdToIdr = config('plans.usd_to_idr');
            $priceUsd = $plans[$planKey]['price_usd'] ?? 0;
            $amount = $priceUsd * $usdToIdr;

            // Jika free plan → langsung ubah role tanpa Xendit
            if ($amount <= 0) {
                $user->role = 'free';
                $user->save();
                return redirect()->route('plan')->with('success', 'You are now on Free Plan');
            }

            $externalId = 'PLAN-' . strtoupper($planKey) . '-' . uniqid();

            $params = [
                'external_id' => $externalId,
                'payer_email' => $user->email,
                'description' => ucfirst($planKey) . ' Plan Subscription',
                'amount' => $amount,
                'currency' => 'IDR',
                'success_redirect_url' => route('plan'),
                'failure_redirect_url' => route('plan'),
                'customer' => [
                    'given_names' => $user->first_name ?? 'User',
                    'email' => $user->email,
                ],
            ];

            // Buat invoice di Xendit
            $invoice = $this->invoiceApi->createInvoice($params);



            // Log
            Log::info('Xendit Invoice created', [
                'user_id' => $user->id,
                'plan' => $planKey,
                'amount' => $amount,
                'invoice_id' => $invoice['id'] ?? null,
            ]);

            // Langsung redirect ke Hosted Invoice Page (UI bawaan Xendit)
            if (!empty($invoice['invoice_url'])) {
                return redirect()->away($invoice['invoice_url']);
            }

            return back()->withErrors(['error' => 'Failed to generate Xendit invoice.']);

        } catch (\Throwable $e) {
            Log::error('Xendit Payment create failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Payment creation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Callback webhook dari Xendit
     */
    public function handleCallback(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Xendit Callback received', $payload);

            $status = $payload['status'] ?? null;
            $externalId = $payload['external_id'] ?? null;
            $payerEmail = $payload['payer_email'] ?? null;

            if ($status === 'PAID' && $externalId) {
                $plan = strtolower(explode('-', $externalId)[1] ?? 'free');
                $user = User::where('email', $payerEmail)->first();

                if ($user) {
                    $planController = new \App\Http\Controllers\PlanController();

                    $fakeRequest = new Request([
                        'plan' => $plan
                    ]);

                    Auth::login($user);

                    // Jalankan changePlan
                    $response = $planController->changePlan($fakeRequest);

                    Log::info("✅ PlanController::changePlan berhasil dipanggil untuk {$user->email} ke {$plan}");
                    Auth::logout();

                    return response()->json(['success' => true]);
                } else {
                    Log::warning("⚠️ User not found for callback: {$payerEmail}");
                }
            }

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Xendit callback error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
