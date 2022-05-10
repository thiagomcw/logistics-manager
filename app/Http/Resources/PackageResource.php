<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                  => $this->resource->getKey(),
            'storage_location_id' => $this->resource->storage_location_id,
            'description'         => $this->resource->description,
            'status'              => $this->resource->status,
            'delivery_address'    => $this->resource->delivery_address,
            'delivery_date'       => $this->resource->delivery_date,
        ];
    }
}
