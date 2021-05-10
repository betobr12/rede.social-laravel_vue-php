<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Libraries\ImageManipulator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    protected function register(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255','unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ],[
            'name.required'         => 'Nome do usuario obrigatorio',
            'name.max'              => 'Caractere maximo para o nome foi ultrapassado',
            'email.required'        => 'Email obrigatorio',
            'email.unique'          => 'Esse email foi cadastrado para outro usuario',
            'email.max'             => 'Caractere maximo para o email foi ultrapassado',
            'email.email'           => 'Email invalido',
            'password.required'     => 'Senha obrigatoria',
            'password.min'          => 'É necessario mais caracteres para senha',
           ]
        );

        if($validator->fails()){
            return response()->json(array("error"=>$validator->errors()));
        }else{
            if(!User::where('email','=',$request->email)->first()){
                if($user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ])){
                    $user->token = $user->createToken($request->email)->accessToken;
                    return response()->json(array("success"=>"Usuario registrado com sucesso","user"=>$user));
                }else{
                    return response()->json(array("error"=>"Erro ao registrar o usuario"));
                }
            }else{
                return response()->json(array("error"=>"Esse email foi cadastrado para outro usuario"));
            }
        }
    }

    protected function login(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string'],
        ],[
            'email.required'        => 'Email obrigatorio. ',
            'email.max'             => 'Caractere maximo para o email foi ultrapassado. ',
            'email.email'           => 'Email invalido. ',
            'password.required'     => 'Senha obrigatoria. ',
           ]
        );

        if($validator->fails()){
            return response()->json(array("error"=>$validator->errors()));
        }{
            if(Auth::attempt(['email' => $data['email'],'password' => $data['password']])){
                $user = auth()->user();
                $user->token = $user->createToken($data['email'])->accessToken;

                return response()->json(array("success"=>"Usuario logado com sucesso","user"=>$user));
            }else{
                return response()->json(array("error"=>"Erro ao autenticar"));
            }
        }
    }

    protected function update(Request $request){
        $user = $request->user();
        $data = $request->all();



        if (isset($data['password'])) {
            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255','unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => ['required', 'string', 'min:8','confirmed'],
            ],[
                'name.required'         => 'Nome do usuario obrigatorio',
                'name.max'              => 'Caractere maximo para o nome foi ultrapassado',
                'email.required'        => 'Email obrigatorio',
                'email.unique'          => 'Esse email foi cadastrado para outro usuario',
                'email.max'             => 'Caractere maximo para o email foi ultrapassado',
                'email.email'           => 'Email invalido',
                'password.required'     => 'Senha obrigatoria',
                'password.min'          => 'É necessario mais caracteres para senha',
                'password.confirmed'    => 'Confirme sua senha com a mesma digitada anteriormente!',
               ]
            );
        } else {
            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255','unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],

            ],[
                'name.required'         => 'Nome do usuario obrigatorio',
                'name.max'              => 'Caractere maximo para o nome foi ultrapassado',
                'email.required'        => 'Email obrigatorio',
                'email.unique'          => 'Esse email foi cadastrado para outro usuario',
                'email.max'             => 'Caractere maximo para o email foi ultrapassado',
                'email.email'           => 'Email invalido',
               ]
            );
        }

        if ($validator->fails()){
            return response()->json(array("error"=>$validator->errors()));
        } else {
            if ($user = User::where('id','=',$user->id)->first()) {
                $user->name     = $data['name'];
                $user->email    = $data['email'];

                if (isset($data['imagem'])) {
                    $image_manipulator = new ImageManipulator();
                    $image_manipulator->image               = $data["imagem"];
                    $image_manipulator->id                  = $user->id;
                    $image_manipulator->image_name          = $user->imagem;
                    $image_manipulator->directory           = 'profile';
                    $image_manipulator->server              = 'http://127.0.0.1:8000/';
                    $image_manipulator->id_directory        = 'perfil_id';
                    $image_manipulator->image_data_exists   = $user->imagem;
                    $image_name = $image_manipulator->pathImageCreate();

                    $user->imagem = $image_name->image_name;
                }

                if(isset($data['password'])) {
                    $user->password = Hash::make($data['password']);
                }
                $user->save();
                $user->token = $user->createToken($request->email)->accessToken;
                return response()->json(array("success"=>"Usuario alterado com sucesso","user"=>$user));
            } else {
                return response()->json(array("error"=>"Esse email foi cadastrado para outro usuario"));
            }
        }
        return response()->json(array("success"=>"Usuario alterado com sucesso","user"=>$user));

    }

}
