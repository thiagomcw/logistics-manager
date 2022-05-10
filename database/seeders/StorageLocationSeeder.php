<?php

namespace Database\Seeders;

use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

class StorageLocationSeeder extends Seeder
{
    public function run()
    {
        StorageLocation::factory()->count(1000)->create();
    }
}
