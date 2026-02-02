<?php

namespace App\Http\Requests\Inventory;

class StoreKebayaRequest extends BaseInventoryRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'code' => 'required|string|max:255|unique:items,code',
            'images' => 'required',
            'images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
            'length' => 'required|string|max:255',
        ]);
    }
}
