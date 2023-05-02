<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\DialogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::post("/conversation/find", [DialogController::class, "findConversation"]);
    Route::get("/conversation", [DialogController::class, "getConversations"]);
    Route::get("/conversation/{conversationId}", [DialogController::class, "getConversation"]);
    Route::post("/message/{conversationId}", [DialogController::class, "sendMessage"]);
    Route::post("/users/search", [DialogController::class, "searchUsers"]);
});