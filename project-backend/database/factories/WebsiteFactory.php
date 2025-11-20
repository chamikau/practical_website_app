<?php

namespace Database\Factories;

use App\Domain\Websites\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Website>
 */
class WebsiteFactory extends Factory
{
    protected $model = Website::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'slug' => $this->faker->url(),
        ];
    }
}
