<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Instagram;
use App\Models\News;
use App\Models\Photo;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::factory()->times(1)->create();
        Album::factory()->times(3)->create();
        Photo::factory()->times(10)->create();
        Tweet::factory()->times(10)->create();
        Instagram::factory()->times(10)->create();
        News::factory()->times(10)->create();
    }
}
