<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id',
        'imagePost',
        'videoPost',
        'filePost',
        'image',
        'timeStamp'
    ];

    // protected $casts = [
    //     'image' => 'array'
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
