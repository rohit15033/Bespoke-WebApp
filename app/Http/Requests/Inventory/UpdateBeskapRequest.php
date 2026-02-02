<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Validation\Rule;

class UpdateBeskapRequest extends BaseInventoryRequest
{
    public function rules(): array
    {
        $beskap = $this->route('id') ? \App\Models\Beskap::find($this->route('id')) : null;
        $itemId = $beskap ? $beskap->item_id : null;

        return array_merge(parent::rules(), [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items', 'code')->ignore($itemId),
            ],
            'type' => 'required|string|max:50',
            'new_images' => 'nullable|array',
            'new_images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
            'existing_images' => 'nullable|array',
        ]);
    }
}
