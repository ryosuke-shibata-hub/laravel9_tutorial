<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\DailyTweetCount;
use App\Services\TweetService;
use Illuminate\Contracts\Mail\Mailer;

class SendDailyTweetCountMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-daily-tweet-count-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '前日のつぶやき数を集計してつぶやきを促すメールを送信する';
    private TweetService $tweetService;
    private Mailer $mailer;
    /**
     * Execute the console command.
     *
     * @return int
     */

    public function __construct(TweetService $tweetService, Mailer $mailer)
    {
        parent::__construct();
        $this->tweetService = $tweetService;
        $this->mailer = $mailer;
    }

    public function handle()
    {
    $tweetCount = $this->tweetService->countYesterDayTweets();

    $users = User::get();
    foreach ($users as $user) {
        $this->mailer->to($user->email)
        ->send(new DailyTweetCount($user, $tweetCount));
    }
        return 0;
    }


}