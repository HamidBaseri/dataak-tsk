<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
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
            'source' => $this->faker->word(),
            'body' => $this->faker->text(),
            'src_url' => $this->faker->url(),
            'avatar' => $this->faker->image(storage_path('app/images'),300,300, null, false),
        ];
    }
}
