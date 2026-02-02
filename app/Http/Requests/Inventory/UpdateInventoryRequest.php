<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Validation\Rule;

class UpdateInventoryRequest extends BaseInventoryRequest
{
    public function rules(): array
    {
        // Try to find the item ID from the route. 
        // This assumes the route parameter 'id' refers to the child record (e.g. Celana ID)
        // Adjust if it's different in some controllers.
        
        $rules = parent::rules();
        
        // Dynamic unique rule if we can find the item_id
        // For now, we'll keep it simple and the controllers will handle the specific child record if needed,
        // or we use a generic approach here.
        
        return array_merge($rules, [
            'code' => [
                'required',
                'string',
                'max:255',
                // Note: We'll need the item_id to properly ignore. 
                // Using a fallback or specific requests for complex ones.
            ],
            'new_images' => 'nullable|array',
            'new_images.*' => 'file|image|mimes:jpeg,png,jpg,gif|max:51200',
            'existing_images' => 'nullable|array',
        ]);
    }
}
