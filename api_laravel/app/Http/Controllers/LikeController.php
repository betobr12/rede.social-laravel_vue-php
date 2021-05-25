<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    protected function like(Request $request, $id)
    {
        $content = Content::find($id);

       // $contents       = new  Content();
       // $content_list   = $contents->getContents(); //paginate não foi definido, pode causar problemas no futuro
        $contents       = new  ContentController();
        $content_list   = $contents->get();

        if ($content) {
            $user = $request->user();
            $user->likes()->toggle($content->id); //adiciona ou remove amigo pelo id

            //$content->likes->count();
            return response()->json(array("status"=>true,"content"=>$content_list, "likes"=> $content->likes()->count()));
        } else {
            return response()->json(array("error" => false));
        }
    }
}
