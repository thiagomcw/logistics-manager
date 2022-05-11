<?php

namespace App\Http\Requests\Api\Package;

use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->package->status === 'stored' && $this->package->delivery_date === now()->format('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:' . implode(',', Package::STATUS),
        ];
    }
}
