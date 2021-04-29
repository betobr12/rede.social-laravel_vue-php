<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected function register(Request $request){
        /*
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        */

        if($user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])){

            $user->token = $user->createToken($request->email)->accessToken;

            return response()->json(array("success"=>"Usuario registrado com sucesso","User"=>$user));
        }else{
            return response()->json(array("error"=>"Erro ao registrar o usuario"));
        }
    }

}
