<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'comments';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'content_id',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public static function comment_get($content_id)
    {
        return DB::table('comments as comment')
        ->leftJoin('users as user','user.id', '=' ,'comment.user_id')

        ->selectRaw('
            comment.id as comment_id,
            comment.user_id,
            comment.content_id,
            comment.description,
            comment.created_at,
            user.name   as user_comment_name,
            user.url    as user_comment_url
        ')
        ->orderBy('created_at','DESC')
        ->where('comment.content_id','=',$content_id)
        ->get();
    }
}
