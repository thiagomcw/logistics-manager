<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StorageLocationControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndexSuccess()
    {
        $this
            ->sendIndexRequest()
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                    ],
                ],
            ]);
    }

    private function sendIndexRequest(array $params = []): TestResponse
    {
        return $this->json('GET', route('api.storage-locations.index'), $params);
    }
}
