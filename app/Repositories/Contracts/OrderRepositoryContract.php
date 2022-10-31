<?php

namespace App\Repositories\Contracts;

use App\Helpers\Adapters\TransactionAdapter;
use App\Http\Requests\CreateProductRequest;
use App\Models\Order;

interface OrderRepositoryContract
{
    public function create(array $request, float $total): Order|bool;

    public function setTransaction(string $vendorOrderId, TransactionAdapter $adapter): Order;
}
