<?php


use Laravel\Cashier\Billable;

class PaymentControllerTest extends BaseTest
{
    private $payload;

    private $mockedTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->payload = [
            'number'    => '4242424242424242',
            'exp_month' => '01',
            'exp_year'  => '2021',
            'cvc'       => '123',
        ];

        $this->mockedTrait = Mockery::mock(Billable::class);
        app()->instance(Billable::class,
            $this->mockedTrait);
    }

    public function testCreateTokenSuccessfully(): void
    {
        $this->post('/stripe/token', $this->payload, $this->headers);
        $this->assertResponseOk();
    }

    public function testCreateTokenFailureBadRequest(): void
    {
        unset($this->payload['cvc']);
        $this->post('/stripe/token', $this->payload, $this->headers);
        $this->assertContains('The cvc field is required', $this->response->getContent());
    }

    public function testCreateNewSubscriptionSuccessfully(): void
    {
        $this->post('/stripe/token', $this->payload, $this->headers);
        $response = json_decode($this->response->getContent(), true);
        $this->post('/stripe/subscribe', ['stripeToken' => $response['data']['id']], $this->headers);
        $this->assertResponseOk();
    }

    public function testCancelSubscriptionSuccessfully(): void
    {
        $this->post('/stripe/token', $this->payload, $this->headers);
        $response = json_decode($this->response->getContent(), true);
        $this->post('/stripe/subscribe', ['stripeToken' => $response['data']['id']], $this->headers);
        $this->post('/stripe/cancel', [], $this->headers);
        $this->assertResponseOk();
    }

    public function testViewInvoicesSuccessfully(): void
    {
        $this->post('/stripe/token', $this->payload, $this->headers);
        $response = json_decode($this->response->getContent(), true);
        $this->post('/stripe/subscribe', ['stripeToken' => $response['data']['id']], $this->headers);
        $this->get('/stripe/invoices', [], $this->headers);
        $this->assertResponseOk();
    }
}
