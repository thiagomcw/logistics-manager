<?php

namespace App\Http\Requests\Api\Package;

use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'delivery_date' => 'nullable|date_format:Y-m-d',
            'status'        => 'nullable|in:' . implode(',', Package::STATUS),
        ];
    }
}
