<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login',    [UserController::class, 'login']);

//Route::post('profile',    [UserController::class, 'update']);
/*
Route::middleware('auth:api')->put('/profile', function (Request $request) {
    $user = $request->user();
    $data = $request->all();

    return $data;
});
 */
Route::group(['middleware' => 'auth:api'], function(){
    Route::put('/profile' , [UserController::class, 'update']);
});
