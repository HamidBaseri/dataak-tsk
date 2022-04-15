<?php

namespace App\News;

use Illuminate\Database\Eloquent\Collection;

interface NewsRepository
{
    public function search(string $query = '', string $date = ''): Collection;
}
