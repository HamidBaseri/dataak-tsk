<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TweetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => $this->faker->text(),
            'username' => $this->faker->username(),
            'retweets' => $this->faker->randomDigit(),
            'image' => $this->faker->image(storage_path('app/images'),640,480, null, false),
            'avatar' => $this->faker->image(storage_path('app/images'),300,300, null, false),
        ];
    }
}
