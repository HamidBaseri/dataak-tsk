<?php

namespace App\Repositories\News;

use App\Models\News;
use App\Models\User;
use App\Notifications\NewsCreated;

class NewsObserver
{

    public function created(News $news)
    {
        $source = $news->source;

        $users = User::whereHas("alerts", function ($q) use ($source) {
            $q->where("source", $source);
        })->get();

        //just because channel is not set to show when notification will be sent
        if (Count($users)) {
            foreach ($users as $user) {

                var_dump('notification sent to user: ' . $user->name);
            }
        }
        //commented because this is just test project
//        \Illuminate\Support\Facades\Notification::send($users, new NewsCreated());
    }
}
