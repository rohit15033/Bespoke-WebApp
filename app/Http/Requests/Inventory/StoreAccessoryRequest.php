<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Validation\Rule;

class StoreAccessoryRequest extends BaseInventoryRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'code' => 'required|string|max:255|unique:items,code',
            'images' => 'required',
            'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
            'accessories_type' => ['required', Rule::in(['Crown', 'Bros', 'Kembang Goyang', 'Karset', 'Obi', 'Selendang'])],
            'parent_type' => 'nullable|string|max:255',
        ]);
    }
}
