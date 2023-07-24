<?php

use Albus\ShopOneSync\Controllers\ExchangeController;
use Illuminate\Session\Middleware\StartSession;

$sPath = '1c_exchange';
Route::group(['middleware' => [ StartSession::class ]], function () use ($sPath) {
    Route::match(['get', 'post'], $sPath, ExchangeController::class);
});