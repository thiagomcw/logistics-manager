<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Package;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PackageControllerTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

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

    public function testStoreValidationRequired()
    {
        $this
            ->sendStoreRequest()
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'storage_location_id',
                    'description',
                    'delivery_address',
                    'delivery_date',
                ],
            ]);
    }

    public function testStoreValidationDetails()
    {
        $this
            ->sendStoreRequest([
                'storage_location_id' => 'a',
                'description'         => $this->faker->realText(400),
                'delivery_date'       => 'a',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'storage_location_id',
                    'description',
                    'delivery_date',
                ],
            ]);
    }

    public function testStoreValidationStorageLocationAlreadyUsed()
    {
        $package = Package::factory()->create();

        $this
            ->sendStoreRequest([
                'storage_location_id' => $package->storage_location_id,
                'description'         => $this->faker->text(),
                'delivery_address'    => $package->delivery_address,
                'delivery_date'       => $package->delivery_date,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'storage_location_id',
                ],
            ]);
    }

    public function testStoreSuccess()
    {
        $package = Package::factory()->make();

        $this
            ->sendStoreRequest([
                'storage_location_id' => $package->storage_location_id,
                'description'         => $package->description,
                'delivery_address'    => $package->delivery_address,
                'delivery_date'       => $package->delivery_date,
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'storage_location_id',
                    'description',
                    'delivery_address',
                    'delivery_date',
                ],
            ])
            ->assertJson([
                'data' => [
                    'storage_location_id' => $package->storage_location_id,
                    'description'         => $package->description,
                    'delivery_address'    => $package->delivery_address,
                    'delivery_date'       => $package->delivery_date,
                ],
            ]);
    }

    public function testShowBindingError()
    {
        $this
            ->sendShowRequest()
            ->assertStatus(404);
    }

    public function testShowSuccess()
    {
        $package = Package::factory()->create();

        $this
            ->sendShowRequest($package->getKey())
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->packageValidationData($package),
            ]);
    }

    public function testUpdateBindingError()
    {
        $this
            ->sendUpdateRequest()
            ->assertStatus(404);
    }

    public function testUpdateValidationRequired()
    {
        $package = Package::factory()->create([
            'delivery_date' => now()->format('Y-m-d'),
        ]);

        $this
            ->sendUpdateRequest($package->getKey())
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['status'],
            ]);
    }

    public function testUpdateValidationDetails()
    {
        $package = Package::factory()->create([
            'delivery_date' => now()->format('Y-m-d'),
        ]);

        $this
            ->sendUpdateRequest($package->getKey(), [
                'status' => 'a',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['status'],
            ]);
    }

    public function testUpdateUnauthorizedByStatus()
    {
        $package = Package::factory()->create([
            'status'        => 'delivered',
            'delivery_date' => now()->format('Y-m-d'),
        ]);

        $this
            ->sendUpdateRequest($package->getKey(), [
                'status' => 'a',
            ])
            ->assertStatus(403);
    }

    public function testUpdateUnauthorizedByDeliveryDate()
    {
        $package = Package::factory()->create([
            'delivery_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $this
            ->sendUpdateRequest($package->getKey(), [
                'status' => 'a',
            ])
            ->assertStatus(403);
    }

    public function testUpdateSuccess()
    {
        $package = Package::factory()->create([
            'delivery_date' => now()->format('Y-m-d'),
        ]);

        $this
            ->sendUpdateRequest($package->getKey(), [
                'status' => 'delivered',
            ])
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->packageValidationData($package->refresh()),
            ])
            ->assertJson([
                'data' => [
                    'status' => 'delivered',
                ],
            ]);
    }

    private function sendIndexRequest(array $params = []): TestResponse
    {
        return $this->json('GET', route('api.packages.index'), $params);
    }

    private function sendStoreRequest(array $data = []): TestResponse
    {
        return $this->json('POST', route('api.packages.store'), $data);
    }

    private function sendShowRequest(int $id = 0): TestResponse
    {
        return $this->json('GET', route('api.packages.show', $id));
    }

    private function sendUpdateRequest(int $id = 0, array $data = []): TestResponse
    {
        return $this->json('PUT', route('api.packages.update', $id), $data);
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
