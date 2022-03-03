<?php

namespace App\Http\Controllers;

use App\Libraries\DataIndex;
use App\Libraries\ImageManipulator;
use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    public function get()
    {
        $user = Auth::user();
        $friends = $user->friends;
        $data_friend = [];

        foreach ($friends as $friend) {
            array_push($data_friend,
                $friend->id,
            );
        }

        $content            = new DataIndex();
        $content->friend_id = $data_friend;
        $content_list = $content->getContent();
        return array("content" => $content_list);
    }

    public function page($id)
    {
        if ($user_page = User::find($id)) {
            Auth::user();
            $content          = new DataIndex();
            $content->user_id = $user_page->id;
            $content_list     = $content->getContent();
            return array("content" => $content_list, "data_user_page" => $user_page);
        } 
        return response()->json(array("error" => "Não foi possível localizar o usuário, por favor tente mais tarde"));
    }

    protected function new(Request $request)
    {
        $user = $request->user();
        $data = $request->all();

        $validator = Validator::make($data,
            [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
            ],
            [
                'title.required'         => 'Titulo obrigatório',
                'description.required'   => 'Texto obrigatório',
            ]
        );

        if ($validator->fails()) {
            return response()->json(array("error" => $validator->errors()->first()));
        }
         
        if (!$content = Content::create([
            'user_id'       => $user->id,
            'title'         => $data['title'],
            'description'   => $data['description'],
            'link'          => $request->link,
            'created_at'    =>\Carbon\Carbon::now()->format('Y-m-d H:i:s'),
        ])) {
            return response()->json(array("error" => "Não foi possível inserir o Post, por favor tente mais tarde"));            
        }
        
        if (isset($data['image'])) {
            $image_manipulator = new ImageManipulator();
            $image_manipulator->image               = $data["image"];
            $image_manipulator->id                  = $user->id;
            $image_manipulator->image_name          = $content->title;
            $image_manipulator->directory           = 'content';
            $image_manipulator->id_directory        = 'content_id';
            $image_manipulator->image_data_exists   = $content->image;
            $data_image = $image_manipulator->pathImageCreate();
            
            if ($data_image == false) {
                return response()->json(array("error" => "Formato inválido, insira apenas, JPG, PNG ou SVG"));
            }

            $content->image        = $data_image->image_name;
            $content->url_image    = $data_image->url;

            if (!$content->save()) {
                return response()->json(array("error" => "Não foi possível inserir uma imagem ao Post, por favor tente mais tarde"));
            } 
            return response()->json(array("success" => "Post inserido com sucesso!", "content" => $this->get()));          
        }
        return response()->json(array("success" => "Post inserido com sucesso!", "content" => $this->get()));
    }
}
