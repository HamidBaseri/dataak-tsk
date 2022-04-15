<?php

namespace App\Tweets;

use Illuminate\Database\Eloquent\Collection;

interface TweetsRepository
{
    public function search(string $query = '', string $date = ''): Collection;
}
