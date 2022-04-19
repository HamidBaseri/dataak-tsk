<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instagram extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['title', 'body', 'name', 'username', 'album_id', 'avatar'];
}
