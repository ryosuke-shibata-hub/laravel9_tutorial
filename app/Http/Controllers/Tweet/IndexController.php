<?php

namespace App\Http\Controllers\Tweet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Services\TweetService;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request,TweetService $tweetService)
    {

        // $tweet = Tweet::orderBy('created_at','desc')->get();

        // $tweetService = new TweetService();
        $tweet = $tweetService->getTweets();

        return view('Tweet.index',[
            'tweet' => $tweet,
        ]);
    }
}