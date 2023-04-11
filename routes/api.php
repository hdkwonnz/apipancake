<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TotalController;
use App\Http\Controllers\Api\PromotionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

////sold total amount
Route::get('/total/{apiKey}/{branchCode}/{from}/{to}', [TotalController::class, 'index'])
    ->name('total.index');
Route::post('/total', [TotalController::class, 'store'])
    ->name('total.store');

////promotion code
Route::get('/checkPmCode/{apiKey}/{pmCode}', [PromotionController::class, 'checkPmCode'])
    ->name('checkPmCode');
Route::post('/updatePmCode', [PromotionController::class, 'updatePmCode'])
    ->name('updatePmCode');
Route::post('/createPmCode', [PromotionController::class, 'createPmCode'])
    ->name('createPmCode');
