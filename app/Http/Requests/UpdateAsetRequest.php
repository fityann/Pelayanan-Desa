<?php

namespace App\Http\Requests;

use App\Enums\KondisiAset;
use App\Enums\StatusAset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_category_id' => ['required', 'exists:asset_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'condition' => ['required', new Enum(KondisiAset::class)],
            'acquisition_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'acquisition_source' => ['nullable', 'string', 'max:255'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', new Enum(StatusAset::class)],
            'description' => ['nullable', 'string'],
        ];
    }
}
