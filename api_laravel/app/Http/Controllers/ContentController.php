<?php

namespace App\Http\Controllers;

use App\Libraries\ImageManipulator;
use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{

    public function get(Request $request)
    {
        $content = Content::with('user')->orderBy('created_at','DESC')->paginate(5);

        return response()->json(array('content' => $content));
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


                        $contents = Content::with('user')->orderBy('created_at','DESC')->paginate(5);//mudar


                        return response()->json(array("success"=>"Post inserido com sucesso!", "content"=>$contents));
                    } else {
                        return response()->json(array("error"=>"Ocorreu uma imagem com Post, por favor tente mais tarde"));
                    }
                }
                return response()->json(array("success"=>"Post inserido com sucesso!", "content"=>$content));
            } else {
                return response()->json(array("error"=>"Ocorreu uma falha ao inserir o Post, por favor tente mais tarde"));
            }
        }
    }
}
