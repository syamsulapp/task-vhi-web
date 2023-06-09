<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhotosModels extends Model
{
    protected $table = 'photos';

    protected $fillable = ['name', 'caption', 'tags', 'img', 'users_id', 'created_at', 'updated_at'];

    public function like(): HasMany
    {
        return $this->hasMany(LikeModels::class, 'photos_id');
    }

    use HasFactory;
}
