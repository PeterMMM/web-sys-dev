<?php

namespace Database\Factories;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\CookieCategory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cookie>
 */
class CookieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Cookie::class;

    public function definition(): array
    {
        $category = CookieCategory::count() >= 7 ? CookieCategory::inRandomOrder()->first()->id: CookieCategory::factory();

        return [
            'title' => $this->faker->text(20),
            'description' => $this->faker->paragraph(),
            'image' => '/storage/images/default_img.webp',
            'category_id' => $category
        ];
    }
}
