<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'contents';
    public $timestamps = true;
    protected $fillable = [
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
}
