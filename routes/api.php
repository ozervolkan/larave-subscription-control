<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//public
Route::post('/register', [AuthController::class, 'register'])->name('register'); //register
Route::post('/login', [AuthController::class, 'login'])->name('login'); //login


//protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix'=>'/user', 'namespace'=>'Api'], function (){

        Route::get('/{userid}', [SubscriptionController::class, 'list']); //all subscriptions and transactions

        Route::post('/{userid}/subscription', [SubscriptionController::class, 'create'])->name('subs.create');   //new subscription
        Route::put('/{userid}/subscription/{id}', [SubscriptionController::class, 'update'])->name('subs.update');    //update subscription
        Route::delete('/{userid}/subscription/{id}', [SubscriptionController::class, 'delete'])->name('subs.delete'); //delete subscription

        Route::post('/{id}/transaction', [TransactionController::class, 'create']); //create transaction
    });
});

