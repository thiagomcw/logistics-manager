<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Package;
use App\Models\StorageLocation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StorageLocationControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndexValidation()
    {
        $this
            ->sendIndexRequest(['available' => 'a'])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['available'],
            ]);
    }

    public function testIndexSuccess()
    {
        $location = StorageLocation::factory()->create();

        $this
            ->sendIndexRequest()
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    ['id' => $location->getKey()],
                ],
            ]);
    }

    public function testIndexSuccessAvailable()
    {
        $location = StorageLocation::factory()->create();

        $this
            ->sendIndexRequest([
                'available' => true,
            ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    ['id' => $location->getKey()],
                ],
            ]);
    }

    public function testIndexSuccessAvailableEmpty()
    {
        Package::factory()->create();

        $data = $this
            ->sendIndexRequest([
                'available' => true,
            ])
            ->assertStatus(200)
            ->json('data');

        $this->assertCount(0, $data);
    }

    private function sendIndexRequest(array $params = []): TestResponse
    {
        return $this->json('GET', route('api.storage-locations.index'), $params);
    }
}
