<?php

use App\Http\Controllers\ContentController;
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

/*Route::get('testes', function() {

    $user = App\Models\User::find(1);
    $user2 = App\Models\User::find(3);//para adicionar aos amigos

    //criar conteudo
    /*

    */
    /*//adicionar amigo

        //$user->friends()->toggle($user2->id); //adiciona ou remove amigo pelo id
        //return $user->friends;
    */

    //adicionar curtidas
   /* $content = App\Models\Content::find(1);
    $user->likes()->toggle($content->id); //adiciona ou remove amigo pelo id
    return $content->likes->count();
    */
//adicionar comentarios
/*
    $content = App\Models\Content::find(1);
    $user->comments()->create([
        'content_id'   =>$content->id,
        'description'  =>'Teste de comentario',
        'created_at'   =>\Carbon\Carbon::now() //date('Y-m-d')
    ]);
    $user2->comments()->create([
        'content_id'   =>$content->id,
        'description'  =>'Teste de comentario do user2',
        'created_at'   =>\Carbon\Carbon::now() //date('Y-m-d')
    ]);
    return $content->comments;

    });
*/



Route::post('/register', [UserController::class, 'register']);
Route::post('/login',    [UserController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function(){
    Route::put('/profile' , [UserController::class, 'update']);
    Route::prefix('content')->group(function() {
        Route::post('/'     , [ContentController::class, 'new']);
        Route::get('/'      , [ContentController::class, 'get']);
    });
});


