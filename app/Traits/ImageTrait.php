<?php

namespace App\Traits;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;

trait ImageTrait
{

    public function imageStore($image, $folder)
    {
        $imageName = time() . '_' . rand(1, 1000000) . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('Image/' . $folder . '', $imageName, 'public');

        return $imagePath;
    }

    public function imageReplace($oldImagePath, $newImage, $folder)
    {
        Storage::disk('public')->delete($oldImagePath);
        $newImagePath = $this->imageStore($newImage, $folder);

        return $newImagePath;
    }

    public function  imageRule($isRequired)
    {
        $required = $isRequired ? ['required'] : ['nullable'];

        return array_merge($required, ['image', 'max:2048']);
    }

    public function  imageMessages()
    {
        return [
            'image.image' => __('validation.file_image'),
            'image.max' => __('validation.max.image', ['max' => 2048]),
        ];
    }
}
