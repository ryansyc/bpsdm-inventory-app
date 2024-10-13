<?php

namespace App\Observers;

use Illuminate\Support\Facades\Storage;

class FileObserver
{
    public function updating($model)
    {
        // Attributes to check for updates (image and file)
        $attributes = ['file', 'image'];

        foreach ($attributes as $attribute) {
            // Check if the attribute exists and is being updated
            if ($model->isDirty($attribute) && $model->getOriginal($attribute)) {
                // Get the original value of the attribute (image or file)
                $oldFile = $model->getOriginal($attribute);

                // Delete the old file if it exists
                if ($oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        }
    }

    public function deleted($model)
    {
        // Delete the image if it exists
        if (isset($model->image)) {
            Storage::disk('public')->delete($model->image);
        }

        // Delete the file if it exists
        if (isset($model->file)) {
            Storage::disk('public')->delete($model->file);
        }
    }
}
