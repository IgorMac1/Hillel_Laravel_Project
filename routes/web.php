<?php

use App\Http\Controllers\Auth\TelegramLoginController;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('job', function() {
//    $order = Order::all()->last();
//   \App\Jobs\OrderCreatedJob::dispatch($order)->onQueue('email'); // queue: default
//});
//
//Route::get('invoice', function() {
//    $order = Order::all()->last();
//    $service = new \App\Services\InvoicesService();
//    $invoice = $service->generate($order);
//
//    $invoice->save('s3');
//    dd(Storage::temporaryUrl($invoice->filename, now()->addDays(7)));
//});

Auth::routes();

Route::delete(
    'ajax/images/{image}',
    \App\Http\Controllers\Ajax\RemoveImageController::class
)->middleware(['auth', 'admin'])->name('ajax.images.delete');

Route::resource('categories', \App\Http\Controllers\CategoriesController::class)->only(['show', 'index']);
Route::resource('products', \App\Http\Controllers\ProductsController::class)->only(['show', 'index']);

Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart');
Route::post('cart/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::delete('cart', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('cart/{product}/count', [\App\Http\Controllers\CartController::class, 'countUpdate'])->name('cart.count.update');

Route::get('auth/telegram', TelegramLoginController::class)->name('auth.telegram');

Route::middleware('auth')->group(function () {
    Route::get('checkout', \App\Http\Controllers\CheckoutController::class)->name('checkout');
    Route::post('order', \App\Http\Controllers\OrdersController::class)->name('orders');

    Route::get('/order/{order}/invoice', \App\Http\Controllers\Invoices\DownloadInvoiceController::class)
        ->name('orders.generate.invoice');

    Route::get('wishlist/{product}/add', [\App\Http\Controllers\WishListController::class, 'add'])->name('wishlist.add');
    Route::delete('wishlist/{product}/delete', [\App\Http\Controllers\WishListController::class, 'delete'])->name('wishlist.delete');

    Route::name('account.')->prefix('account')->group(function () {
        Route::get('/', [\App\Http\Controllers\Account\UsersController::class, 'index'])->name('index');
        Route::get('{user}/edit', [\App\Http\Controllers\Account\UsersController::class, 'edit'])
            ->middleware('can:view,user')
            ->name('edit');
        Route::put('{user}', [\App\Http\Controllers\Account\UsersController::class, 'update'])
            ->middleware('can:update,user')
            ->name('update');
        Route::get('telegram/callback', \App\Http\Controllers\TelegramCallBackController::class)->name('telegram.callback');
        Route::get('wishlist', \App\Http\Controllers\Account\WishListController::class)->name('wishlist');
    });
});

Route::name('admin.')->prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoriesController::class)->except(['show']);
    Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class)->except(['show']);
});

Route::prefix('paypal')->group(function () {
    Route::post('order/create', [\App\Http\Controllers\Payments\PaypalController::class, 'create']);
    Route::post('order/{orderId}/capture', [\App\Http\Controllers\Payments\PaypalController::class, 'capture']);
    Route::get('order/{orderId}/thankyou', [\App\Http\Controllers\Payments\PaypalController::class, 'thankYou']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
