<?php


use App\Events\OrderCreatedEvent;
use App\Models\Order;
use App\Http\Controllers\TestController;
use App\Services\Contracts\FileStorageServiceContract;
use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('/', function(){
//    $order = Order::all()->last();
//    OrderCreatedEvent::dispatch($order);
//})->name('home');


Route::get('job', function() {
    //    dd('work');
    \Cache::store('redis')->put('Laradock', 'Awesome', 10);
    //   $order = Order::all()->last();
    //   \App\Jobs\OrderCreatedJob::dispatch($order)->onQueue('email'); // queue: default
});




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

Route::middleware('auth')->group(function() {
    Route::get('checkout', \App\Http\Controllers\CheckoutController::class)->name('checkout');
    Route::post('order', \App\Http\Controllers\OrdersController::class)->name('orders');

    Route::get('/order/{order}/invoice', \App\Http\Controllers\Invoices\DownloadInvoiceController::class)
        ->name('orders.generate.invoice');

    Route::name('account.')->prefix('account')->group(function() {
        Route::get('/', [\App\Http\Controllers\Account\UsersController::class, 'index'])->name('index');
        Route::get('{user}/edit', [\App\Http\Controllers\Account\UsersController::class, 'edit'])
            ->middleware('can:view,user')
            ->name('edit');
        Route::put('{user}', [\App\Http\Controllers\Account\UsersController::class, 'update'])
            ->middleware('can:update,user')
            ->name('update');
    });


});

Route::name('admin.')->prefix('admin')->middleware(['auth', 'admin'])->group(function() {
    Route::get('dashboard', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoriesController::class)->except(['show']);
    Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class)->except(['show']);
});

Route::prefix('paypal')->group(function() {
    Route::post('order/create', [\App\Http\Controllers\Payments\PaypalController::class, 'create']);
    Route::post('order/{orderId}/capture', [\App\Http\Controllers\Payments\PaypalController::class, 'capture']);
    Route::get('order/{orderId}/thankyou', [\App\Http\Controllers\Payments\PaypalController::class, 'thankYou']);
});
