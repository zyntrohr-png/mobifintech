<?php

namespace Tests\Feature;

use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use paytm\paytmchecksum\PaytmChecksum;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_an_environment_notice(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSeeText('Staging')
            ->assertSeeText('Test payments only')
            ->assertSee(route('paytm.checkout'));
    }

    public function test_checkout_requires_paytm_sandbox_credentials(): void
    {
        config([
            'paytm.merchant_id' => null,
            'paytm.merchant_key' => null,
        ]);

        $this->get('/paytm')
            ->assertOk()
            ->assertSeeText('Sandbox credentials are not configured');

        $this->post('/paytm/checkout')
            ->assertRedirect(route('paytm.checkout'))
            ->assertSessionHasErrors('paytm');
    }

    public function test_checkout_creates_a_fixed_value_sandbox_order_and_signed_gateway_form(): void
    {
        $this->configurePaytm();

        $response = $this->post('/paytm/checkout');

        $response->assertOk()
            ->assertSee('https://securegw-stage.paytm.in/theia/processTransaction')
            ->assertSee('name="TXN_AMOUNT" value="1.00"', false)
            ->assertSee('name="MID" value="TESTMID"', false)
            ->assertSee('name="CHECKSUMHASH"', false);

        $this->assertDatabaseHas('payments', [
            'amount' => '1.00',
            'status' => 'initiated',
        ]);
    }

    public function test_valid_paytm_callback_updates_the_matching_sandbox_order(): void
    {
        $this->configurePaytm();
        $payment = Payment::create([
            'order_id' => 'STGTESTORDER123',
            'customer_id' => 'CUSTTEST123',
            'amount' => '1.00',
            'status' => 'initiated',
        ]);

        $parameters = [
            'MID' => 'TESTMID',
            'ORDERID' => $payment->order_id,
            'TXNAMOUNT' => '1.00',
            'STATUS' => 'TXN_SUCCESS',
            'TXNID' => 'TESTTXN123',
        ];
        $parameters['CHECKSUMHASH'] = PaytmChecksum::generateSignature($parameters, '1234567890123456');

        $this->post('/paytm/callback', $parameters)
            ->assertRedirect(route('paytm.result', ['orderId' => $payment->order_id]));

        $this->assertDatabaseHas('payments', [
            'order_id' => $payment->order_id,
            'status' => 'success',
            'transaction_id' => 'TESTTXN123',
        ]);
    }

    public function test_invalid_paytm_callback_signature_is_rejected(): void
    {
        $this->configurePaytm();
        $payment = Payment::create([
            'order_id' => 'STGTESTORDER456',
            'customer_id' => 'CUSTTEST456',
            'amount' => '1.00',
            'status' => 'initiated',
        ]);

        $this->post('/paytm/callback', [
            'MID' => 'TESTMID',
            'ORDERID' => $payment->order_id,
            'TXNAMOUNT' => '1.00',
            'STATUS' => 'TXN_SUCCESS',
            'CHECKSUMHASH' => 'not-a-valid-checksum',
        ])->assertBadRequest();

        $this->assertDatabaseHas('payments', [
            'order_id' => $payment->order_id,
            'status' => 'initiated',
        ]);
    }

    public function test_valid_signature_with_a_mismatched_amount_is_rejected(): void
    {
        $this->configurePaytm();
        $payment = Payment::create([
            'order_id' => 'STGTESTORDER789',
            'customer_id' => 'CUSTTEST789',
            'amount' => '1.00',
            'status' => 'initiated',
        ]);
        $parameters = [
            'MID' => 'TESTMID',
            'ORDERID' => $payment->order_id,
            'TXNAMOUNT' => '99.00',
            'STATUS' => 'TXN_SUCCESS',
        ];
        $parameters['CHECKSUMHASH'] = PaytmChecksum::generateSignature($parameters, '1234567890123456');

        $this->post('/paytm/callback', $parameters)->assertBadRequest();

        $this->assertDatabaseHas('payments', [
            'order_id' => $payment->order_id,
            'status' => 'initiated',
        ]);
    }

    private function configurePaytm(): void
    {
        config([
            'paytm.environment' => 'TEST',
            'paytm.merchant_id' => 'TESTMID',
            'paytm.merchant_key' => '1234567890123456',
            'paytm.website' => 'WEBSTAGING',
            'paytm.industry_type' => 'Retail',
            'paytm.channel_id' => 'WEB',
        ]);
    }
}
