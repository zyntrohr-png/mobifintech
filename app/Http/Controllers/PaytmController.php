<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use paytm\paytmchecksum\PaytmChecksum;

class PaytmController extends Controller
{
    private const TEST_AMOUNT = '1.00';

    public function checkout()
    {
        return view('paytm.checkout', [
            'configured' => $this->isConfigured(),
        ]);
    }

    public function initiate()
    {
        if (! $this->isConfigured()) {
            return to_route('paytm.checkout')
                ->withErrors(['paytm' => 'Add the Paytm sandbox credentials to this staging server’s .env file.']);
        }

        $orderId = 'STG'.Str::upper(Str::random(12));
        $customerId = 'CUST'.Str::upper(Str::random(8));

        $payment = Payment::create([
            'order_id' => $orderId,
            'customer_id' => $customerId,
            'amount' => self::TEST_AMOUNT,
            'status' => 'initiated',
        ]);

        $parameters = [
            'MID' => config('paytm.merchant_id'),
            'ORDER_ID' => $payment->order_id,
            'CUST_ID' => $payment->customer_id,
            'INDUSTRY_TYPE_ID' => config('paytm.industry_type'),
            'CHANNEL_ID' => config('paytm.channel_id'),
            'TXN_AMOUNT' => $payment->amount,
            'WEBSITE' => config('paytm.website'),
            'CALLBACK_URL' => route('paytm.callback'),
        ];

        $checksum = PaytmChecksum::generateSignature($parameters, config('paytm.merchant_key'));

        return response()
            ->view('paytm.redirect', [
                'parameters' => $parameters,
                'checksum' => $checksum,
                'transactionUrl' => config('paytm.transaction_url'),
            ])
            ->header('Cache-Control', 'no-store, private');
    }

    public function callback(Request $request)
    {
        $checksum = $request->input('CHECKSUMHASH');
        $parameters = $request->except('CHECKSUMHASH');

        if (! is_string($checksum) || collect($parameters)->contains(fn ($value) => ! is_string($value))) {
            abort(400, 'Invalid Paytm callback.');
        }

        $ciphertext = base64_decode($checksum, true);
        if ($ciphertext === false || $ciphertext === '' || strlen($ciphertext) % 16 !== 0) {
            abort(400, 'Invalid Paytm callback signature.');
        }

        $merchantKey = config('paytm.merchant_key');
        if (! is_string($merchantKey) || $merchantKey === ''
            || ! PaytmChecksum::verifySignature($parameters, $merchantKey, $checksum)) {
            abort(400, 'Paytm callback signature could not be verified.');
        }

        $payment = DB::transaction(function () use ($parameters) {
            $payment = Payment::query()
                ->where('order_id', $parameters['ORDERID'] ?? '')
                ->lockForUpdate()
                ->first();

            if (! $payment
                || ! hash_equals((string) config('paytm.merchant_id'), (string) ($parameters['MID'] ?? ''))
                || ! hash_equals($payment->amount, (string) ($parameters['TXNAMOUNT'] ?? ''))) {
                abort(400, 'Paytm callback does not match the test order.');
            }

            if ($payment->status === 'initiated') {
                $payment->update([
                    'status' => match ($parameters['STATUS'] ?? '') {
                        'TXN_SUCCESS' => 'success',
                        'TXN_FAILURE' => 'failed',
                        default => 'pending',
                    },
                    'transaction_id' => $parameters['TXNID'] ?? null,
                ]);
            }

            return $payment;
        });

        return to_route('paytm.result', ['orderId' => $payment->order_id]);
    }

    public function result(string $orderId)
    {
        $payment = Payment::query()->where('order_id', $orderId)->firstOrFail();

        return view('paytm.result', compact('payment'));
    }

    private function isConfigured(): bool
    {
        return config('paytm.environment') === 'TEST'
            && filled(config('paytm.merchant_id'))
            && filled(config('paytm.merchant_key'))
            && filled(config('paytm.website'));
    }
}
