<?php

namespace App\Repositories\Tweets;

use App\Interfaces\TweetsRepository;
use App\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;

class EloquentRepository implements TweetsRepository
{
    public function search(array $params): Collection
    {
        return Tweet::query()
            ->where('body', 'LIKE', "%{$params['q']}%")
            ->orWhere('username', 'LIKE', "%{$params['q']}%")
            ->orWhere('created_at', 'LIKE', "%{$params['date']}%")
            ->get();
    }
}
