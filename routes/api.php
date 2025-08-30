<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelegramController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Telegram Bot Routes
Route::prefix('telegram')->group(function () {
    Route::post('webhook', [TelegramController::class, 'webhook']);
    Route::post('setup-webhook', [TelegramController::class, 'setupWebhook']);
    Route::get('test-bot', [TelegramController::class, 'testBot']);
    Route::get('webhook-info', [TelegramController::class, 'getWebhookInfo']);
});
