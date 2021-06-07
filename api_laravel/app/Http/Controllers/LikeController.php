<?php

namespace App\Http\Controllers;

use App\Libraries\DataIndex;
use App\Models\Content;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    protected function like(Request $request, $id)
    {
        $user           = $request->user();
        $contents       = new DataIndex();

        
        $content = Content::find($id);
        $like_count = 0;

        //$page_data = (object) $content->getContents();


       // return response()->json($content->paginate(5));

        if ($like = Like::where('content_id','=',$content->id)->where('user_id','=',$user->id)->whereNull('deleted_at')->first()) {
            $like->delete();
            $like_count = $like->count_like($content->id)[0];
            return  array("status"=>true, "likes"=> $like_count->count_like,'content'=>$contents->getContent());
        } else {
            if ($like = Like::create([
                'user_id'      => $user->id,
                'content_id'   => $content->id,
                'created_at'   => \Carbon\Carbon::now() //date('Y-m-d')
            ])) {
                $like_count = $like->count_like($content->id)[0];
                return  array("status"=>true, "likes"=> $like_count->count_like,'content'=>$contents->getContent());
            }
        }
    }

}
