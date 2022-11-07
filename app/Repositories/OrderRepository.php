<?php

namespace App\Repositories;

use App\Helpers\Adapters\TransactionAdapter;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Repositories\Contracts\OrderRepositoryContract;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderRepository implements OrderRepositoryContract
{
    const ORDER_STATUSES = [
        'completed' => 'COMPLETED',
    ];

    public function create(array $request, float $total): Order|bool
    {
        $user = auth()->user();
        $status = OrderStatus::defaultStatus()->first();

        $request = array_merge($request, [
            'status_id' => $status->id,
            'total' => $total,
        ]);

        $order = $user->orders()->create($request);

        $this->addProductsToOrder($order);

        return $order;
    }

    public function setTransaction(string $vendorOrderId, TransactionAdapter $adapter): Order
    {
        $order = Order::where('vendor_order_id', $vendorOrderId)->firstOrFail();
        $transaction = $order->transaction()->create((array) $adapter);

        if ($adapter->status === self::ORDER_STATUSES['completed']) {
            $order->update([
                'status_id' => OrderStatus::paidStatus()->firstOrFail()?->id,
                'transaction_id' => $transaction->id,
            ]);
        }

        return $order;
    }

    protected function addProductsToOrder(Order $order)
    {
        Cart::instance('cart')->content()->groupBy('id')->each(function ($item) use ($order) {
            $cartItem = $item->first();
            $order->products()->attach(
                $cartItem->model, // Product
                [
                    'quantity' => $cartItem->qty,
                    'single_price' => $cartItem->model->endPrice,
                ]
            );
            $inStock = $cartItem->model->in_stock - $cartItem->qty;

            if (! $cartItem->model->update(['in_stock' => $inStock])) {
                throw new \Exception("Smth went wrong with product (id={$cartItem->model->id}) in_stock update");
            }
        });
    }
}
