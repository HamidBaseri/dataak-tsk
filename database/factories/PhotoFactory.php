<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'album_id' => $this->faker->randomElement(Album::pluck('id')),
            'path' => $this->faker->image(storage_path('app/images'),200,100, null, false),
        ];
    }
}
