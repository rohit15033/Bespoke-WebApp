<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class BaseInventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Permission check can be added here or in controllers
        return true; 
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'production_month' => in_array($this->production_month, [null, '', 'null', 'Choose Month'])
                ? null
                : $this->production_month,
            'production_year' => in_array($this->production_year, [null, '', 'null', 'Choose Year'])
                ? null
                : $this->production_year,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'production_month' => 'nullable|integer',
            'production_year' => 'nullable|integer',
            'subcolor_id' => 'required|exists:subcolors,id',
            'occasion_ids' => 'nullable|array',
            'occasion_ids.*' => 'exists:occasions,id',
        ];
    }
}
