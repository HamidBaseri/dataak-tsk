<?php

namespace App\Instagrams;

use Illuminate\Database\Eloquent\Collection;

interface InstagramsRepository
{
    public function search(string $query = ''): Collection;
}
