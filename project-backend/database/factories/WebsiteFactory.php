<?php

namespace Database\Factories;

use App\Domain\Websites\Models\Website; // Your model
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Website>
 */
class WebsiteFactory extends Factory
{
    // Explicitly bind the factory to your model
    protected $model = Website::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'slug' => $this->faker->url(),
        ];
    }
}
