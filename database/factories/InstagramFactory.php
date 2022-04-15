<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstagramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'album_id' => $this->faker->randomElement(Album::pluck('id')),
            'name' => $this->faker->name(),
            'avatar' => $this->faker->image(storage_path('app/images'),300,300, null, false),
            'username' => $this->faker->username(),
            'body' => $this->faker->text(),
        ];
    }
}
