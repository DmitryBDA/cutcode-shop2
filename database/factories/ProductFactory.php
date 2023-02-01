<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        Storage::createDirectory('images/products');

        return [
            'title' => ucfirst($this->faker->words(2, true)),
            'brand_id' => Brand::query()->inRandomOrder()->value('id'),
            'thumbnail' => $this->faker->imageFile(
                base_path('/tests/Fixtures/images/products'),
                '/app/public/images/products'
            ),
            'price' => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
