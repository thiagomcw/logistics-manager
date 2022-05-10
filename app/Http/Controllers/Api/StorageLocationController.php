<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StorageLocationResource;
use App\Models\StorageLocation;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StorageLocationController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $locations = StorageLocation::all();

        return StorageLocationResource::collection($locations);
    }
}
