<?php

use App\Http\Controllers\PaytmController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/paytm', [PaytmController::class, 'checkout'])->name('paytm.checkout');
Route::post('/paytm/checkout', [PaytmController::class, 'initiate'])
    ->middleware('throttle:10,1')
    ->name('paytm.initiate');
Route::post('/paytm/callback', [PaytmController::class, 'callback'])->name('paytm.callback');
Route::get('/paytm/result/{orderId}', [PaytmController::class, 'result'])
    ->whereAlphaNumeric('orderId')
    ->name('paytm.result');
