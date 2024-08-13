<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaImage extends Model
{
    use HasFactory;
    protected $table = 'media_images';
    protected $fillable = ['title', 'path', 'size', 'user_id'];
}
