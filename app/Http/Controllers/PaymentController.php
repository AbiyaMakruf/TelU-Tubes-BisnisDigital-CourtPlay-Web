<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use App\Models\User;
use App\Mail\PlanChangedMail;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    protected InvoiceApi $invoiceApi;

    public function __construct()
    {
        Configuration::setXenditKey(config('xendit.secret_key'));
        $this->invoiceApi = new InvoiceApi();
    }

    public function createTransaction(Request $request)
    {
        try {


            $request->validate(['plan' => 'required|in:free,pro,plus']);
            $user = Auth::user();
            $planKey = $request->plan;

            $plans = config('plans.plans');
            if (!isset($plans[$planKey])) {
                toastr()->error('Selected plan not found.');
                return back();
            }

            $selectedPlan = $plans[$planKey];
            $amount = (int) ($selectedPlan['price_idr'] ?? 0);

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
                toastr()->success("Your plan updated to {$selectedPlan['name']}.");
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

    public function handleCallbackSuccess(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('📩 Xendit Callback received', $payload);

            // Ambil field penting dari payload
            $status          = strtoupper($payload['status'] ?? '');
            $payerEmail      = $payload['payer_email'] ?? null;
            $externalId      = $payload['external_id'] ?? null;
            $paymentMethod   = $payload['payment_method'] ?? null;
            $paymentChannel  = $payload['payment_channel'] ?? null;

            // Validasi status sukses dan external_id ada
            if ($status === 'PAID' && !empty($externalId)) {

                // Contoh external_id: PLAN-PLUS-68fe2f0344037 → ambil "PLUS"
                $parts = explode('-', $externalId);
                $plan  = strtolower($parts[1] ?? 'free');

                if (empty($payerEmail)) {
                    Log::warning("⚠️ Missing payer_email for external_id: {$externalId}");
                    return response()->json(['error' => 'Missing payer email'], 400);
                }

                // Temukan user berdasarkan email
                $user = User::where('email', $payerEmail)->first();

                if (!$user) {
                    Log::warning("⚠️ User not found for callback email: {$payerEmail}");
                    return response()->json(['error' => 'User not found'], 404);
                }

                // Hindari double-update kalau user sudah di-plan yang sama
                if ($user->role === $plan) {
                    Log::info("ℹ️ Callback ignored — User {$user->email} already on {$plan} plan.");
                    return response()->json(['success' => true, 'message' => 'User already on this plan']);
                }

                // Simpan plan lama untuk email
                $oldPlan = $user->role;

                // Kirim email notifikasi sebelum update role
                try {
                    Mail::to($user->email)->send(new PlanChangedMail($user, $oldPlan, $plan));
                    Log::info("📧 Plan change email sent to {$user->email} ({$oldPlan} → {$plan})");
                } catch (\Throwable $mailError) {
                    Log::error("❌ Failed to send plan change email to {$user->email}: {$mailError->getMessage()}");
                }

                // Update role user
                $user->role = $plan;
                $user->save();

                Log::info("✅ User {$user->email} upgraded to {$plan} plan (via Xendit {$paymentMethod}-{$paymentChannel})");
                return response()->json(['success' => true]);
            }

            Log::warning("⚠️ Ignored callback with status={$status}, external_id={$externalId}");
            return response()->json(['success' => true, 'message' => 'No action taken']);

        } catch (\Throwable $e) {
            Log::error('❌ Xendit callback error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
