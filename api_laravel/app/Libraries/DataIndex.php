<?php

namespace App\Libraries;

use App\Models\Comment;
use App\Models\Content;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class DataIndex
{
    public $user_id;
    public $friend_id;

    public function getContent()
    {
        $user = Auth::user();
        $content                    = new Content();
        $content->user_id           = $this->user_id;
        $content->friend_id         = $this->friend_id;
        $contents                   = $content->getContents();

        foreach ($contents as $key => $cont) {
            $total_likes         = Like::count_like($cont->id)[0];
            $cont->total_likes   = $total_likes->count_like;
            $cont->comments      = Comment::comment_get($cont->id);
            $liked               = $user->likes()->find($cont->id);
            $cont->user          = $user;

            $cont->liked_content = false;

            if ($liked) {
                $cont->liked_content = true;
            }
        }
        return (object) $contents;
    }

}
