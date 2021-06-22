<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login',    [UserController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function(){
    Route::put('/profile' , [UserController::class, 'update']);
    Route::prefix('content')->group(function($id) {
        Route::post('/'         , [ContentController::class, 'new']);
        Route::get('/'          , [ContentController::class, 'get']);
        Route::get('/page/{id}' , [ContentController::class, 'page']);
    });
    Route::prefix('like')->group(function($id) {
        Route::put('/{id}'     , [LikeController::class, 'like']);
        Route::put('/page/{id}'     , [LikeController::class, 'like_page']);
    });
    Route::prefix('comment')->group(function($id) {
        Route::put('/{id}'     , [CommentController::class, 'comment']);
        Route::put('/page/{id}'     , [CommentController::class, 'comment_page']);
    });
    Route::prefix('user')->group(function($id) {
        Route::post('/friend'               , [UserController::class, 'friend']);
        Route::get('/list_friend'           , [UserController::class, 'list_friend']);
        Route::get('/list_friend_page/{id}' , [UserController::class, 'list_friend_page']);
    });
});


