<?php

namespace App\Http\Controllers;

use App\Libraries\DataIndex;
use App\Models\Comment;
use App\Models\Content;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected function comment(Request $request, $id)
    {
        $content        = Content::find($id);   //acessa um conteudo
        if ($content) {
            $user = $request->user();
            $user->comments()->create([
                'user_id'      => $user->id,
                'content_id'   => $content->id,
                'description'  => $request->description,
                'created_at'   => \Carbon\Carbon::now() //date('Y-m-d')
            ]);

            $content = new DataIndex();
            $content_list = $content->getContent();
            return array("content" => $content_list);
        } else {
            return response()->json(array("error" => false));
        }
    }
}
