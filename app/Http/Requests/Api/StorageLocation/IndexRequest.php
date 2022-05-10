<?php

namespace App\Http\Requests\Api\StorageLocation;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'available' => 'nullable|boolean',
        ];
    }
}
