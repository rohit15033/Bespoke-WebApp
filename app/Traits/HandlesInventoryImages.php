<?php

namespace App\Traits;

use App\Models\ItemsImagesUrls;
use Illuminate\Support\Facades\Storage;

trait HandlesInventoryImages
{
    /**
     * Handle uploading and creating image records for an item.
     *
     * @param  \App\Models\Items  $item
     * @param  array  $images
     * @return void
     */
    protected function uploadImages($item, array $images)
    {
        foreach ($images as $file) {
            $path = $file->store('items_images', 'public');
            ItemsImagesUrls::create([
                'item_id' => $item->id,
                'image_url' => $path,
            ]);
        }
    }

    /**
     * Update images for an item, deleting ones not in existingImageIds.
     *
     * @param  \App\Models\Items  $item
     * @param  array  $existingImageIds
     * @param  array  $newImages
     * @return void
     */
    protected function updateImages($item, array $existingImageIds, array $newImages)
    {
        // Delete images not kept
        $item->images()->whereNotIn('id', $existingImageIds)->get()->each(function ($img) {
            Storage::disk('public')->delete($img->image_url);
            $img->delete();
        });

        // Upload new images
        if (!empty($newImages)) {
            $this->uploadImages($item, $newImages);
        }
    }

    /**
     * Delete all images for an item.
     *
     * @param  \App\Models\Items  $item
     * @return void
     */
    protected function deleteImages($item)
    {
        foreach ($item->images as $img) {
            Storage::disk('public')->delete($img->image_url);
            $img->delete();
        }
    }
}
