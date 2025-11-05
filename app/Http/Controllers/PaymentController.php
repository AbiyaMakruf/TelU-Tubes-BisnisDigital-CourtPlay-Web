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
            // ✅ Validate only the key fields you need
            $validated = $request->validate([
                'status'          => ['required', 'string'],
                'payer_email'     => ['required', 'email'],
                'external_id'     => ['required', 'string'],
                'payment_method'  => ['nullable', 'string'],
                'payment_channel' => ['nullable', 'string'],
            ]);

            Log::info('📩 Xendit Callback received', $validated);

            // Use validated data safely
            $status         = strtoupper($validated['status']);
            $payerEmail     = $validated['payer_email'];
            $externalId     = $validated['external_id'];
            $paymentMethod  = $validated['payment_method'] ?? null;
            $paymentChannel = $validated['payment_channel'] ?? null;

            // ---- your existing logic remains unchanged ----
            if ($status === 'PAID' && !empty($externalId)) {

                $parts = explode('-', $externalId);
                $plan  = strtolower($parts[1] ?? 'free');

                $user = User::where('email', $payerEmail)->first();

                if (!$user) {
                    Log::warning("⚠️ User not found for callback email: {$payerEmail}");
                    return response()->json(['error' => 'User not found'], 404);
                }

                if ($user->role === $plan) {
                    Log::info("ℹ️ Callback ignored — User {$user->email} already on {$plan} plan.");
                    return response()->json(['success' => true, 'message' => 'User already on this plan']);
                }

                $oldPlan = $user->role;

                try {
                    Mail::to($user->email)->send(new PlanChangedMail($user, $oldPlan, $plan));
                    Log::info("📧 Plan change email sent to {$user->email} ({$oldPlan} → {$plan})");
                } catch (\Throwable $mailError) {
                    Log::error("❌ Failed to send plan change email to {$user->email}: {$mailError->getMessage()}");
                }

                $user->update(['role' => $plan]);

                Log::info("✅ User {$user->email} upgraded to {$plan} plan (via Xendit {$paymentMethod}-{$paymentChannel})");
                return response()->json(['success' => true]);
            }

            Log::warning("⚠️ Ignored callback with status={$status}, external_id={$externalId}");
            return response()->json(['success' => true, 'message' => 'No action taken']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('⚠️ Invalid Xendit callback payload', $e->errors());
            return response()->json([
                'error' => 'Invalid payload',
                'details' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('❌ Xendit callback error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
