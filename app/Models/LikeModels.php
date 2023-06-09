<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeModels extends Model
{
    protected $table = 'likes';

    protected $fillable = ['photos_id', 'users_id', 'created_at', 'updated_at'];

    use HasFactory;
}
