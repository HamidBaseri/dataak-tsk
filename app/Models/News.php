<?php

namespace App\Models;

use App\News\NewsObserver;
use App\Search\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    use Searchable;

    public static function boot()
    {
        parent::boot();
        self::observe(new NewsObserver());
    }
}
