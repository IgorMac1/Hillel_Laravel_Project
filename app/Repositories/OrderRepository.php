<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Repositories\Contracts\OrderRepositoryContract;

class OrderRepository implements OrderRepositoryContract
{

    public function create(array $request, float $total): Order|bool
    {
        $user = auth()->user();
        $status = OrderStatus::defaultStatus()->first();

        $request = array_merge($request, [
            'status_id' => $status->id,
            'total' => $total
        ]);

        $order = $user->orders()->create($request);

        return $order;
    }

    public function setTransaction(string $transactionOrderId, array $data): Order
    {
        // TODO: Implement setTransaction() method.
        return new Order;
    }
}
