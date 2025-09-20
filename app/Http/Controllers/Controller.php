<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

abstract class Controller
{
    protected function updateImage(Request $request, string $tag = '', string $path = '')
    {
        // Upload Image
        if ($request->hasFile($tag)) {
            $image_tmp = $request->file($tag);
            if ($image_tmp->isValid()) {
                // Get Image Extension
                $extension = $image_tmp->getClientOriginalExtension();
                // create image manager with desired driver
                $manager = new ImageManager(new Driver());
                // open an image file
                $image = $manager->read($image_tmp);
                // Generate New Image Name
                $imageName = rand(11111, 99999) . '.' . $extension;
                // Create Path
                $imagePath = $path . $imageName;
                // Save to storage
                if ($image->save($imagePath)) {
                    return $imageName;
                }
            }
        } else if (!empty($data['current_image'])) {
            return $data['current_image'];
        } else {
            return "";
        }
    }
}
