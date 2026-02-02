<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Validation\Rule;

class UpdateAccessoryRequest extends BaseInventoryRequest
{
    public function rules(): array
    {
        $accessory = $this->route('id') ? \App\Models\Accessory::find($this->route('id')) : null;
        $itemId = $accessory ? $accessory->item_id : null;

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
            'accessories_type' => ['required', Rule::in(['Crown', 'Bros', 'Kembang Goyang', 'Karset', 'Obi', 'Selendang'])],
            'parent_type' => 'nullable|string|max:255',
        ]);
    }
}
