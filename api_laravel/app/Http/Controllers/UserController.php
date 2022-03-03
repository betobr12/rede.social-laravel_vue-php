<?php

namespace App\Http\Controllers;

use App\Libraries\ImageManipulator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    protected function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255','unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ],[
            'name.required'         => 'Nome do usuário obrigatório',
            'name.max'              => 'Caractere maximo para o nome foi ultrapassado',
            'email.required'        => 'Email obrigatório',
            'email.unique'          => 'Esse email foi cadastrado para outro usuário',
            'email.max'             => 'Caractere maximo para o email foi ultrapassado',
            'email.email'           => 'Email inválido',
            'password.required'     => 'Senha obrigatoria',
            'password.min'          => 'É necessario mais caracteres para senha',
           ]
        );

        if ($validator->fails()) {
            return response()->json(array("error" => $validator->errors()->first()));
        }

        if (User::where('email','=',$request->email)->first()) {
            return response()->json(array("error" => "Esse email foi cadastrado para outro usuário"));
        }

        if ($user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
        ])) {            
            
            $user->url = "http://127.0.0.1:8000/img/usuario.jpg";
            
            if (!$user->save()) {
                return response()->json(array("error"=>"Erro ao registrar o usuário"));
            }
            
            $user->token = $user->createToken($request->email)->accessToken;
            
            return response()->json(array("success" => "Usuário registrado com sucesso", "user" => $user));
        }
        return response()->json(array("error"=>"Erro ao registrar o usuário"));
    }

    protected function login(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string'],
        ],[
            'email.required'        => 'Email obrigatório. ',
            'email.max'             => 'Caractere maximo para o email foi ultrapassado. ',
            'email.email'           => 'Email inválido. ',
            'password.required'     => 'Senha obrigatoria. ',
           ]
        );

        if ($validator->fails()) {
            return response()->json(array("error" => $validator->errors()->first()));
        }

        if (Auth::attempt(['email' => $data['email'],'password' => $data['password']])) {            
            $user = auth()->user();
            $user->token = $user->createToken($data['email'])->accessToken;
            return response()->json(array("success" => "Usuário logado com sucesso", "user" => $user));
        }
        return response()->json(array("error" => "Usuário ou senha inválido"));        
    }

    protected function update(Request $request)
    {
        $user = $request->user();
        $data = $request->all();

        if (isset($data['password'])) {
            $validator = Validator::make($data, [               
                'password' => ['string', 'min:8','confirmed'],
            ],[
                'password.min'          => 'É necessario mais caracteres para senha',
                'password.confirmed'    => 'Confirme sua senha com a mesma digitada anteriormente!',
               ]
            );

            if ($validator->fails()){
                return response()->json(array("error" => $validator->errors()->first()));
            }
        }

        if ($user = User::where('id','=',$user->id)->first()) {
            $user->name         = $request->name ? $request->name : $user->name;
            $user->email        = $request->email ? $request->email : $user->email;
            $user->description  = $request->description ? $request->description : "Olá meu nome é $user->name";
            
            if (isset($data['imagem'])) {
                $image_manipulator = new ImageManipulator();
                $image_manipulator->image               = $data["imagem"];
                $image_manipulator->id                  = $user->id;
                $image_manipulator->image_name          = $user->imagem;
                $image_manipulator->directory           = 'profile';
                $image_manipulator->id_directory        = 'perfil_id';
                $image_manipulator->image_data_exists   = $user->imagem;
                $data_image = $image_manipulator->pathImageCreate();
                
                if ($data_image == false) {
                    return response()->json(array("error" => "Formato inválido, insira apenas, JPG, PNG ou SVG"));
                }
                $user->imagem = $data_image->image_name;
                $user->url    = $data_image->url;
            }

            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            if (!$user->save()){
                return response()->json(array("error" => "Ocorreu uma falha ao alterar as informações, tente mais tarde"));
            }
                
            $user->token = $user->createToken($request->email)->accessToken;

            return response()->json(array("success" => "Usuário alterado com sucesso", "user" => $user));
        }
        return response()->json(array("error" => "Não foi possivel alterar o seu cadastro"));
    }

    protected function friend(Request $request)
    {
        $user = $request->user();

        if ($user->id == $request->id){
            return response()->json(array("error" => "Id usuário é igual ao do amigo"));
        }

        if ($friend = User::where('id','=',$request->id)->first()) {
            $user->friends()->toggle($friend->id); //adiciona ou remove amigo pelo id
            return response()->json(array("success"=>"Amigo adicionado ou removido", "friends" => $user->friends, "followers" => $friend->followers ));
        }
        return response()->json(array("error" => "Usuário não localizado"));
    }

    protected function list_friend(Request $request)
    {
        if ($user = $request->user()) {
            return response()->json(
                array(
                    "success" => "Sucesso", 
                    "friends" => $user->friends, 
                    "followers"=>$user->followers
                )
            );
        }
        return response()->json(array("error" => "Usuário não localizado"));
    }

    protected function list_friend_page(Request $request, $id)
    {
        $user = User::find($id);
        $user_logged = $request->user();
        if ($user) {
            return response()->json(
                array(
                    "success" => "Sucesso", 
                    "friends" => $user->friends,
                    "user_logged" => $user_logged->friends, 
                    "followers"=>$user->followers
                )
            );
        }
        return response()->json(array("error" => "usuário não localizado"));
    }
}
