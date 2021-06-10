<?php

namespace App\Http\Controllers;

use App\Libraries\ImageManipulator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


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
                    'name'      => $data['name'],
                    'email'     => $data['email'],
                    'password'  => Hash::make($data['password']),
                ])){
                    $user->url = "http://127.0.0.1:8000/img/usuario.jpg";
                    $user->save();
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
                return response()->json(array("error"=>"Usuario ou senha invalido"));
            }
        }
    }

    protected function update(Request $request)
    {
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
                $user->name         = $data['name'];
                $user->email        = $data['email'];
                $user->description  = $data['description'] ? $data['description'] : "Olá meu nome é $user->name";

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
                        return response()->json(array("error"=>"Formato invalido, insira apenas, JPG, PNG ou SVG"));
                    }
                    $user->imagem = $data_image->image_name;
                    $user->url    = $data_image->url;
                }

                if(isset($data['password'])) {
                    $user->password = Hash::make($data['password']);
                }
                if ($user->save()){
                    $user->token = $user->createToken($request->email)->accessToken;
                    return response()->json(array("success"=>"Usuario alterado com sucesso","user"=>$user));
                } else {
                    return response()->json(array("error"=>"Ocorreu uma falha ao alterar as informações, tente mais tarde"));
                }
            } else {
                return response()->json(array("error"=>"Esse email foi cadastrado para outro usuario"));
            }
        }
        return response()->json(array("success"=>"Usuario alterado com sucesso","user"=>$user));
    }

    protected function friend(Request $request)
    {
        $user = $request->user();

        if ($user->id == $request->id){
            return response()->json(array("error"=>"Id usuario é igual ao do amigo"));
        }

        if ($friend = User::where('id','=',$request->id)->first()) {
            $user->friends()->toggle($friend->id); //adiciona ou remove amigo pelo id
            return response()->json(array("success"=>"Amigo adicionado ou removido", "friends" => $user->friends, "followers"=>$friend->followers ));
        }
        return response()->json(array("error"=>"usuario não localizado"));
    }

    protected function list_friend(Request $request)
    {
        $user = $request->user();

        if ($user){
            return response()->json(array("success"=>"Sucesso", "friends" => $user->friends, "friends" => $user->friends, "followers"=>$user->followers));
        }
        return response()->json(array("error"=>"usuario não localizado"));
    }

    protected function list_friend_page(Request $request, $id)
    {
        $user = User::find($id);
        $user_logged = $request->user();
        if ($user){
            return response()->json(array("success"=>"Sucesso", "friends" => $user->friends,"user_logged" =>$user_logged->friends, "followers"=>$user->followers));
        }
        return response()->json(array("error"=>"usuario não localizado"));
    }
}
