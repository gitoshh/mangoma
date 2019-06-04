<?php

namespace App\Http\Controllers;

use App\Domains\Entrust as EntrustDomain;
use App\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Stripe\Stripe;
use Stripe\Token;

class PaymentController extends Controller
{
    public const STRIPE_VENDOR = 'Mangoma';
    public const STRIPE_PRODUCT = 'Mangoma premium account';

    /**
     * @var EntrustDomain
     */
    private $entrustDomain;

    public function __construct(Request $request, EntrustDomain $entrustDomain)
    {
        parent::__construct($request);
        $this->entrustDomain = $entrustDomain;
    }

    /**
     * Creates new token from card information.
     *
     * @throws ValidationException
     *
     * @return JsonResponse
     */
    public function createToken():JsonResponse
    {
        $this->validate($this->request, [
            'number'    => 'required|string',
            'exp_month' => 'required|string',
            'exp_year'  => 'required|string',
            'cvc'       => 'required|string',
        ]);

        $payload = $this->request->only([
            'number',
            'exp_month',
            'exp_year',
            'cvc',
        ]);

        Stripe::setApiKey(getenv('STRIPE_KEY'));

        $token = Token::create([
            'card' => [
                'number'    => $payload['number'],
                'exp_month' => $payload['exp_month'],
                'exp_year'  => $payload['exp_year'],
                'cvc'       => $payload['cvc'],
            ],
        ]);

        return response()->json([
            'message' => 'success',
            'data'    => $token,
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
        $response = Auth::user()->newSubscription(self::STRIPE_PRODUCT, 'plan_F6HqCkiweMUWGW')
            ->create($token, ['email' => Auth::user()->email]);

        if (Auth::user()->subscribed(self::STRIPE_PRODUCT)) {
            $roleId = null;
            $role = Role::where('name', 'Premium')->first();
            if (!empty($role)) {
                $roleId = $role['id'];
            } else {
                $response = $this->entrustDomain->newRole('Premium', 'premium');
                $roleId = $response['id'];
            }
            Auth::user()->attachRole($roleId);
        }

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
    }

    /**
     * Cancel user subscription.
     */
    public function cancelSubscription()
    {
        $response = Auth::user()->subscription(self::STRIPE_PRODUCT)->cancel();

        return response()->json([
            'message' => 'success',
            'data'    => $response,
        ]);
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
     *
     * @param string $id
     *
     * @return resource
     */
    public function downloadInvoice(string $id)
    {
        $data = [
            'vendor'  => self::STRIPE_VENDOR,
            'product' => self::STRIPE_PRODUCT,
        ];

        return Auth::user()->downloadInvoice($id, $data);
    }
}
