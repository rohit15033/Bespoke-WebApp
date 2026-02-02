<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Validation\Rule;

class UpdateKebayaRequest extends BaseInventoryRequest
{
    public function rules(): array
    {
        $kebaya = $this->route('id') ? \App\Models\Kebaya::find($this->route('id')) : null;
        $itemId = $kebaya ? $kebaya->item_id : null;

        return array_merge(parent::rules(), [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items', 'code')->ignore($itemId),
            ],
            'new_images' => 'nullable|array',
            'new_images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
            'existing_images' => 'nullable|array',
            'length' => 'required|string|max:255',
        ]);
    }
}
