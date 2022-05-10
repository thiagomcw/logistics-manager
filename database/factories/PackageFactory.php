<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\StorageLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class PackageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'storage_location_id' => fn() => StorageLocation::factory()->create()->getKey(),
            'description'         => fn() => $this->faker->text(100),
            'status'              => fn() => Arr::first(Package::STATUS),
            'delivery_address'    => fn() => $this->faker->address(),
            'delivery_date'       => fn() => $this->faker
                ->dateTimeBetween('now', '+1 year')
                ->format('Y-m-d'),
        ];
    }
}
