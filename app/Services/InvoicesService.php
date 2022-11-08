<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Facades\Invoice as InvoiceFacade;
use LaravelDaily\Invoices\Invoice;

class InvoicesService implements Contract\InvoicesServiceContract
{
    public function generate(Order $order): Invoice
    {
        $user = $order->user;
        $customer = new Buyer([
            'name' => $user->fullName,
            'custom_fields' => [
                'email' => $user->email,
                'phone' => $user->phone,
                'city' => $order->city,
                'address' => $order->address,
            ],
        ]);

        $items = $this->getInvoiceItems($order->products);
        $serialNumber = $order->transaction?->id ?? $order->vendor_order_id;

        $invoice = InvoiceFacade::make()
            ->status($order->status->name)
            ->serialNumberFormat($serialNumber)
            ->buyer($customer)
            ->taxRate(config('cart.tax'))
            ->filename($serialNumber)
            ->logo('https://seeklogo.com/images/B/business-company-logo-C561B48365-seeklogo.com.png')
            ->addItems($items);

        if ($order->in_process) {
            $invoice->payUntilDays(3);
        }

        return $invoice;
    }

    protected function getInvoiceItems(Collection $products): array
    {
        $items = [];

        $products->each(function ($product) use (&$items) {
            $items[] = (new InvoiceItem())
                ->title($product->title)
                ->pricePerUnit($product->pivot->single_price)
                ->quantity($product->pivot->quantity)
                ->units('од');
        });

        return $items;
    }
}
