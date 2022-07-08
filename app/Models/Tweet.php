<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TweetImage;
use App\Models\Image;
class Tweet extends Model
{
    use HasFactory;
    protected $table = 'tweets';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'tweet_images')
        ->using(TweetImage::class);
    }
}