<?php

use Stripe\Token;

class PaymentControllerTest extends BaseTest
{
    private $payload;

    private $mockedClass;

    public function setUp(): void
    {
        parent::setUp();
        $this->payload = [
            'number'    => '4242424242424242',
            'exp_month' => '01',
            'exp_year'  => '2021',
            'cvc'       => '123',
        ];

        $this->mockedClass = Mockery::mock(Token::class);
        app()->instance(Token::class,
            $this->mockedClass);
    }

    public function testCreateTokenSuccessfully(): void
    {
        $this->mockedClass->shouldReceive('create')->andReturn(['id' => uniqid('tok_', false)]);
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
