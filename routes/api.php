<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/user', [UserController::class, 'create']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', [UserController::class, 'show'])->name('user.get');
    Route::put('/user', [UserController::class, 'update'])->name('user.update');
    Route::post('/service/order', [ServiceOrderController::class, 'createOrder'])->name('order.create');
    Route::get('/service/order', [ServiceOrderController::class, 'getAllOrder'])->name('order.get');
    Route::get('/service/order/{id}', [ServiceOrderController::class, 'getOrderById'])->name('order.getById');
    Route::get('/service/order/{id}/user', [ServiceOrderController::class, 'getOrderByUserId'])->name('order.getBUserId');
});
