<?php

namespace App\Services;

use App\Models\Tweet;
use Carbon\Carbon;
use App\Models\Image;
use DB;
use Illuminate\Support\Facades\Storage;
class TweetService
{
    public function getTweets()
    {
        return Tweet::with('images')->orderby('created_at','desc')->get();
    }

    public function checkOwnTweet(int $userId, int $tweetId):bool
    {
        $tweet = Tweet::where('id',$tweetId)->first();
        if (!$tweet) {
            return false;
        }
        return $tweet->user_id === $userId;
    }

    public function countYesterDayTweets():int
    {
        return Tweet::whereDate('created_at', '>=', Carbon::yesterday()->toDateTimeString())
            ->whereDate('created_at', '<', Carbon::today()->toDateTimeString())
            ->count();
    }

    public function saveTweet(int $userId, string $content, array $images)
    {
        DB::transaction(function () use ($userId,$content,$images) {
            $tweet = new Tweet;
            $tweet->user_id = $userId;
            $tweet->content = $content;
            $tweet->save();

            foreach ($images as $image) {
                Storage::putFile('public/images',$image);
                $imageModal = new Image();
                $imageModal->name  = $image->hashName();
                $imageModal->save();
                $tweet->images()->attach($imageModal->id);
            }
        });
    }

    public function deleteTweet(int $tweetId)
    {

        DB::transaction(function () use ($tweetId) {
            $tweet = Tweet::where('id', $tweetId)->firstOrFail();
            $tweet->images()->each(function($image) use($tweet) {
                $filePath = 'public/images/' . $image->name;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
                $tweet->images()->detach($image->id);
                $image->delete();
            });
            $tweet->delete();
        });

    }
}