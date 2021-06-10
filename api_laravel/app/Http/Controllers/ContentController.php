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
        $user_page = User::find($id);

        if  ($user_page) {
            Auth::user();
            $content          = new DataIndex();
            $content->user_id = $user_page->id;
            $content_list     = $content->getContent();
            return array("content" => $content_list, "data_user_page"=>$user_page);
        } else {
            return response()->json(array("error"=>"Falha ao encontrar o usuario, tente mais tarde"));
        }


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
                'title.required'         => 'Titulo obrigatorio',
                'description.required'   => 'Texto obrigatorio',
            ]
        );

        if ($validator->fails()) {
            return response()->json(array("error"=>$validator->errors()));
        } else {
            if ($content = Content::create([
                'user_id'       => $user->id,
                'title'         => $data['title'],
                'description'   => $data['description'],
                'link'          => $data['link'],
                'created_at'    =>\Carbon\Carbon::now()->format('Y-m-d H:i:s'),

            ])) {
                if (isset($data['image'])) {
                    $image_manipulator = new ImageManipulator();
                    $image_manipulator->image               = $data["image"];
                    $image_manipulator->id                  = $user->id; //coloca um id na pasta
                    $image_manipulator->image_name          = $content->title;
                    $image_manipulator->directory           = 'content';
                    $image_manipulator->id_directory        = 'content_id';
                    $image_manipulator->image_data_exists   = $content->image;
                    $data_image = $image_manipulator->pathImageCreate();
                    if ($data_image == false) {
                        return response()->json(array("error"=>"Formato invalido, insira apenas, JPG, PNG ou SVG"));
                    }
                    $content->image        = $data_image->image_name;
                    $content->url_image    = $data_image->url;
                    if ($content->save()) {
                        return response()->json(array("success"=>"Post inserido com sucesso!", "content"=>$this->get()));
                    } else {
                        return response()->json(array("error"=>"Ocorreu uma imagem com Post, por favor tente mais tarde"));
                    }
                }
                return response()->json(array("success"=>"Post inserido com sucesso!", "content"=>$this->get()));
            } else {
                return response()->json(array("error"=>"Ocorreu uma falha ao inserir o Post, por favor tente mais tarde"));
            }
        }
    }
}
