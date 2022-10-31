<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Repositories\Contracts\OrderRepositoryContract;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    const PAYMENT_SYSTEM = 'PAYPAL';
    protected PayPalClient $payPalClient;

    public function __construct()
    {
        $this->payPalClient = new PayPalClient();
        $this->payPalClient->setApiCredentials(config('paypal'));
        $this->payPalClient->setAccessToken($this->payPalClient->getAccessToken());
    }

    public function create(CreateOrderRequest $request, OrderRepositoryContract $repository)
    {
        try {
            DB::beginTransaction();

            $total = Cart::instance('cart')->total(2, '.', '');
            $paypalOrder = $this->createPaymentOrder($total);
            $request = array_merge($request->validated(), [
                'vendor_order_id' => $paypalOrder['id']
            ]);

            $order = $repository->create($request, $total);

            DB::commit();

            return response()->json($order);
        } catch (\Exception $exception) {
            DB::rollBack();
            logs()->warning($exception);
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    public function capture(string $orderId, OrderRepositoryContract $repository)
    {
        try {
            DB::beginTransaction();

            $result = $this->payPalClient->capturePaymentOrder($orderId);
            $order = $repository->setTransaction($orderId, [
                self::PAYMENT_SYSTEM,
                auth()->id(),
                $result['status']
            ]);
            $result['orderId'] = $order->id;

            DB::commit();

            return response()->json($result);
        } catch (\Exception $exception) {
            DB::rollBack();
            logs()->warning($exception);
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }

    protected function createPaymentOrder($total): array
    {
        return $this->payPalClient->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency'),
                        'value' => $total
                    ]
                ]
            ]
        ]);
    }
}
