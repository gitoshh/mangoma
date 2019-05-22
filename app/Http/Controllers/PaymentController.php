<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Token;

class PaymentController extends Controller
{
    public const STRIPE_VENDOR = 'Mangoma';
    public const STRIPE_PRODUCT = 'Mangoma premium account';

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Creates new token from card information.
     *
     * @throws BadRequestException
     * @return JsonResponse
     */
    public function createToken():JsonResponse
    {
        try{
            $payload = $this->request->only([
                'number',
                'exp_month',
                'exp_year',
                'cvc'
            ]);
        } catch (Exception $exception) {
            throw new BadRequestException('Invalid payload');
        }


        Stripe::setApiKey(getenv('STRIPE_KEY'));

        $token = Token::create([
            'card' => [
                'number' => $payload['number'],
                'exp_month' => $payload['exp_month'],
                'exp_year' => $payload['exp_year'],
                'cvc' => $payload['cvc']
            ]
        ]);

        return response()->json([
            'message' => 'success',
            'data'    => $token
        ]);
    }

    /**
     * Adds a new subscription.
     *
     * @return JsonResponse
     */
    public function newSubscription(): JsonResponse
    {
        $token = $this->get('stripeToken');
        $response = Auth::user()->newSubscription('Mangoma premium account','plan_F6HqCkiweMUWGW')->create($token, [
            'email' => Auth::user()->email,
        ]);

        return response()->json([
            'message' => 'success',
            'data'    => $response->toArray(),
        ]);
    }

    public function cancelSubscription()
    {

    }

    public function switchSubscription()
    {

    }

    /**
     * Retrieves all invoices for the current user.
     *
     * @return JsonResponse
     */
    public function viewInvoices(): JsonResponse
    {
        $invoices = [];
        $response = Auth::user()->invoices()->toArray();
        foreach ($response as $item) {
            $invoices[] = [
                'id'          => $item->id,
                'customer'    => $item->customer,
                'amount_due'  => $item->amount_due,
                'amount_paid' => $item->amount_paid,
                'invoice_pdf' => $item->invoice_pdf,
                ];
        }
        return response()->json([
            'message'=> 'success',
            'data'   => $invoices,
            ]);
    }

    /**
     * Retrieves user invoices.
     * @param string $id
     * @return resource
     */
    public function downloadInvoice(string $id)
    {
        $data = [
            'vendor'  => self::STRIPE_VENDOR,
            'product' => self::STRIPE_PRODUCT
        ];

        return Auth::user()->downloadInvoice($id, $data);
    }

}