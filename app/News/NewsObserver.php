<?php

namespace App\News;

use App\Models\News;
use App\Notifications\NewsCreated;
use App\Models\User;
use Elasticsearch\Endpoints\Count;

class NewsObserver
{

    public function created(News $news)
    {
        $source = $news->source;

//        $users= User::with('alerts')->where('source', '=', $source)->get();

        $users = User::whereHas("alerts", function ($q) use ($source) {
            $q->where("source", $source);
        })->get();
//        dd($users);
        //just because channel is not set to show when notification will be sent
        if (Count($users)) {
            foreach ($users as $user) {

                var_dump('notification sent to user: ' . $user->name);
            }
        }
//        \Illuminate\Support\Facades\Notification::send($users, new NewsCreated());
    }
}
