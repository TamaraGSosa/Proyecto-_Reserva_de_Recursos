<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\StatusResource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status_resource=StatusResource::inRandomOrder()->first();
        $category=Category::inRandomOrder()->first();
        return [
            'name'=>$this->faker->sentence(),
            'marca'=>$this->faker->sentence(),
            'description'=>$this->faker->paragraph(),
            'status_resource_id'=>$status_resource->id,
            'category_id'=>$category->id,
        ];
    }

 
}
