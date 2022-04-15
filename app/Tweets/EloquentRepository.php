<?php

namespace App\Tweets;

use App\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;

class EloquentRepository implements TweetsRepository
{
    public function search(string $query = ''): Collection
    {
        return Tweet::query()
            ->where('body', 'LIKE', "%{$query}%")
            ->orWhere('username', 'LIKE', "%{$query}%")
            ->orWhere('created_at', 'LIKE', "%{$query}%")
            ->get();
    }
}
