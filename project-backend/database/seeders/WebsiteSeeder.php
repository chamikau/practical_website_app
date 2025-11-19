<?php

namespace Database\Seeders;

use App\Domain\Websites\Models\Website;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Website::factory()->count(2)->create();
    }
}
