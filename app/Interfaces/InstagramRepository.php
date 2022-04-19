<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface InstagramRepository
{
    public function search(array $params): Collection;
}
