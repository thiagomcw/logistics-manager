<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected array $fillable = [
        'storage_location_id',
        'description',
        'status',
        'delivery_address',
        'delivery_date',
    ];

    protected array $attributes = [
        'status' => 'stored',
    ];

    const STATUS = [
        'stored',
        'delivering',
        'delivered',
    ];

    public function storageLocation(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class);
    }
}
