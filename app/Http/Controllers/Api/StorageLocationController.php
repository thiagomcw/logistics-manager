<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorageLocation\IndexRequest;
use App\Http\Resources\StorageLocationResource;
use App\Models\StorageLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StorageLocationController extends Controller
{
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $locations = StorageLocation::query()
            ->where(function (Builder $builder) use ($request) {
                if ($request->get('available')) {
                    $builder->whereDoesntHave('package');
                }
            })
            ->get();

        return StorageLocationResource::collection($locations);
    }
}
