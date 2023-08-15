<?php

namespace Database\Factories;

use App\Models\Pages;
use Illuminate\Database\Eloquent\Factories\Factory;

class PagesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pages::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(400),
            'meta_title' => $this->faker->text(200),
            'meta_keywords' => $this->faker->text(200),
            'meta_description' => $this->faker->text(200),
            'slug' => $this->faker->slug(),
            'status' => Pages::STATUS_NOT_ACTIVE,
        ];
    }
}
