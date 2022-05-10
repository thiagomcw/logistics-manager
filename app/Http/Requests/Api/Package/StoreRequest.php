<?php

namespace App\Http\Requests\Api\Package;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description'         => 'required|max:255',
            'delivery_address'    => 'required',
            'delivery_date'       => 'required|date_format:Y-m-d',
            'storage_location_id' => [
                'required',
                Rule::exists('storage_locations', 'id'),
                Rule::unique('packages')
                    ->where(fn($query) => $query->where('status', 'stored')),
            ],
        ];
    }
}
