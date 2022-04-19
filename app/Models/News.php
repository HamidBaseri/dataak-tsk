<?php

namespace App\Models;

use App\Repositories\News\NewsObserver;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['title', 'source', 'body', 'src_url'];

    public static function boot()
    {
        parent::boot();
        self::observe(new NewsObserver());
    }
}
