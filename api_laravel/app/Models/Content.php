<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Content extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'contents';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'description',
        'image',
        'url_image',
        'link',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
      return $this->belongsToMany(User::class, 'likes', 'content_id', 'user_id');
    }


    public function getContents()
    {
        return DB::table('contents as conte')
        ->leftJoin('users   as user',   'user.id',         '=','conte.user_id')
        ->leftJoin('friends as friend', 'friend.user_id',  '=','user.id')
        ->selectRaw("
            conte.id,
            conte.user_id,
            conte.title,
            conte.description,
            conte.image,
            conte.url_image,
            conte.link,
            conte.created_at,
            conte.updated_at,
            user.id                 as  user_id,
            user.name               as  user_name,
            user.url                as  user_url,
            user.name               as  user_name,
            conte.created_at
        ")
        ->orderBy('created_at','DESC')
        ->when($this->user_id, function ($query, $user_id) {
            return $query->where('conte.user_id','=',$user_id);
        })
        ->paginate(5);
    }
}
