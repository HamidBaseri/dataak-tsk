<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['username', 'body', 'retweets', 'avatar', 'image'];

}
