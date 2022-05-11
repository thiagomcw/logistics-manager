<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Package\IndexRequest;
use App\Http\Requests\Api\Package\StoreRequest;
use App\Http\Requests\Api\Package\UpdateRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PackageController extends Controller
{
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $packages = Package::query()
            ->where(function (Builder $builder) use ($request) {
                if ($deliveryDate = $request->get('delivery_date')) {
                    $builder->where('delivery_date', $deliveryDate);
                }

                if ($status = $request->get('status')) {
                    $builder->where('status', $status);
                }
            })
            ->orderBy('delivery_date')
            ->get();

        return PackageResource::collection($packages);
    }

    public function store(StoreRequest $request): PackageResource
    {
        $package = Package::query()->create($request->validated());

        return new PackageResource($package);
    }

    public function show(Package $package): PackageResource
    {
        return new PackageResource($package);
    }

    public function update(Package $package, UpdateRequest $request): PackageResource
    {
        $package->update($request->validated());

        return new PackageResource($package->refresh());
    }
}
