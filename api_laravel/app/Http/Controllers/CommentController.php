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
        $content        = Content::find($id);
        if ($content) {
            $user = $request->user();
            $user->comments()->create([
                'user_id'      => $user->id,
                'content_id'   => $content->id,
                'description'  => $request->description,
                'created_at'   => \Carbon\Carbon::now() 
            ]);

            $content = new DataIndex();
            $content_list = $content->getContent();
            return array("content" => $content_list);
        } else {
            return response()->json(array("error" => false));
        }
    }

    protected function comment_page(Request $request, $id)
    {
        $content        = Content::find($id);   
        if ($content) {
            $user = $request->user();
            $user->comments()->create([
                'user_id'      => $user->id,
                'content_id'   => $content->id,
                'description'  => $request->description,
                'created_at'   => \Carbon\Carbon::now() 
            ]);

            $content_data          = new DataIndex();
            $content_data->user_id = $content->user_id;
            $content_list          = $content_data->getContent();
            return array("content" => $content_list);
        } else {
            return response()->json(array("error" => false));
        }
    }
}
