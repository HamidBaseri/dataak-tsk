<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface TweetsRepository
{
    public function search(array $params): Collection;
}
