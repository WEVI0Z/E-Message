<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\DialogController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return "hello laravel";
});

Route::post('/register', [AuthorizationController::class, "registerApi"]);
Route::post('/login', [AuthorizationController::class, "loginApi"]);

Route::group(["middleware" => "token"], function() {
    Route::get("/test", [AuthorizationController::class, "tokenTest"]);
    Route::post("/conversation", [DialogController::class, "createConversation"]);
    Route::get("/conversation", [DialogController::class, "getConversations"]);
    Route::get("/conversation/{conversationId}", [DialogController::class, "getConversation"]);
    Route::post("/message/{conversationId}", [DialogController::class, "sendMessage"]);
    Route::post("/users/search", [DialogController::class, "searchUsers"]);
});