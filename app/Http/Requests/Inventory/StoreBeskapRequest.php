<?php

namespace App\Http\Requests\Inventory;

class StoreBeskapRequest extends BaseInventoryRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'code' => 'required|string|max:255|unique:items,code',
            'type' => 'required|string|max:50',
            'images' => 'required',
            'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
        ]);
    }
}
