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

            // Ambil konfigurasi plan dari config/plans.php
            $plans = config('plans.plans');
            if (!isset($plans[$planKey])) {
                toastr()->error('Selected plan not found.');
                return back();
            }

            $selectedPlan = $plans[$planKey];
            $amount = (int) ($selectedPlan['price_idr'] ?? 0);

            // Jika Free Plan → langsung ubah role tanpa Xendit
            if ($amount <= 0) {
                $user->role = 'free';
                $user->save();

                toastr()->success('You are now on Free Plan!');
                return redirect()->route('plan');
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

            Log::info('✅ Xendit Invoice created', [
                'user_id'    => $user->id,
                'plan'       => $planKey,
                'amount'     => $amount,
                'invoice_id' => $invoice['id'] ?? null,
            ]);

            // Redirect ke Hosted Invoice Page (Xendit UI)
            if (!empty($invoice['invoice_url'])) {
                toastr()->info("Redirecting to payment page for {$selectedPlan['name']} Plan...");
                return redirect()->away($invoice['invoice_url']);
            }

            toastr()->error('Failed to generate Xendit invoice.');
            return back();

        } catch (\Throwable $e) {
            Log::error('❌ Xendit Payment create failed', [
                'error' => $e->getMessage(),
            ]);
            toastr()->error('Payment creation failed: ' . $e->getMessage());
            return back();
        }
    }

    /**
     * Callback webhook dari Xendit
     */
    public function handleCallback(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('📩 Xendit Callback received', $payload);

            $status = $payload['status'] ?? null;
            $externalId = $payload['external_id'] ?? null;
            $payerEmail = $payload['payer_email'] ?? null;

            if ($status === 'PAID' && $externalId) {
                $plan = strtolower(explode('-', $externalId)[1] ?? 'free');
                $user = User::where('email', $payerEmail)->first();

                if ($user) {
                    // Ganti role user
                    $user->role = $plan;
                    $user->save();

                    Log::info("✅ User {$user->email} upgraded to {$plan} plan (via callback)");
                    return response()->json(['success' => true]);
                }

                Log::warning("⚠️ User not found for callback email: {$payerEmail}");
                return response()->json(['error' => 'User not found'], 404);
            }

            Log::warning("⚠️ Invalid callback status: {$status}");
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('❌ Xendit callback error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
