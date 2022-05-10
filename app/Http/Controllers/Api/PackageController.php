<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Package\IndexRequest;
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
}
