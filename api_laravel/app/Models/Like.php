<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Like extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'likes';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'content_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function count_like($content_id)
    {
        return DB::table('likes')->where('content_id','=',$content_id)
        ->selectRaw('
            count(content_id) as count_like
        ')->get();
    }
}
