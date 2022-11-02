<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Contract\InvoicesServiceContract;

class DownloadInvoiceController extends Controller
{
    public function __invoke(Order $order, InvoicesServiceContract $invoices)
    {
        return $invoices->generate($order)->download();
    }
}
