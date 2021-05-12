<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'friends';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'friend_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
