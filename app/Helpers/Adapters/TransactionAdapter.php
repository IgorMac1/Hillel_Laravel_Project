<?php

namespace App\Helpers\Adapters;

class TransactionAdapter
{
    public function __construct(public string $payment_system, public int $user_id, public string $status)
    {
    }
}
