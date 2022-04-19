<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface NewsRepository
{
    public function search(array $params): Collection;
}
