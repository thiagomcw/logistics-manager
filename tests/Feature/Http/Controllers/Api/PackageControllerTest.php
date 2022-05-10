<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Package;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PackageControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndexValidation()
    {
        $this
            ->sendIndexRequest([
                'delivery_date' => 'a',
                'status'        => 'a',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'delivery_date',
                    'status',
                ],
            ]);
    }

    public function testIndexSuccess()
    {
        $package = Package::factory()->create();

        $this
            ->sendIndexRequest()
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    $this->packageValidationData($package),
                ],
            ]);
    }

    public function testIndexSuccessByDeliveryDate()
    {
        Package::factory()->count(2)
            ->create(['delivery_date' => now()->addYear()->format('Y-m-d')]);

        $package = Package::factory()
            ->create(['delivery_date' => now()->addDay()->format('Y-m-d')]);

        $data = $this
            ->sendIndexRequest([
                'delivery_date' => $package->delivery_date,
            ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    $this->packageValidationData($package),
                ],
            ])
            ->json('data');

        $this->assertCount(1, $data);
    }

    public function testIndexSuccessByStatus()
    {
        Package::factory()->count(2)
            ->create(['status' => Arr::last(Package::STATUS)]);

        $package = Package::factory()->create();

        $data = $this
            ->sendIndexRequest([
                'status' => $package->status,
            ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    $this->packageValidationData($package),
                ],
            ])
            ->json('data');

        $this->assertCount(1, $data);
    }

    private function sendIndexRequest(array $params = []): TestResponse
    {
        return $this->json('GET', route('api.packages.index'), $params);
    }

    private function packageValidationData(Package $package): array
    {
        return [
            'id'                  => $package->getKey(),
            'storage_location_id' => $package->storage_location_id,
            'description'         => $package->description,
            'status'              => $package->status,
            'delivery_address'    => $package->delivery_address,
            'delivery_date'       => $package->delivery_date,
        ];
    }
}
