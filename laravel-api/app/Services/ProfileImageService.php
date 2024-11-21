<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileImageService
{
    public function handleImageUpload($image)
    {
        return $this->resizeAndSaveImage($image);
    }

    protected function resizeAndSaveImage($image)
    {

        // Load Driver
        $manager = new ImageManager(new Driver());

        // Org Image Name
        $filenamewithextension = $image->getClientOriginalName();
        // $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
        $extension = $image->getClientOriginalExtension();
        $time = time();

        // Directories exist
        $this->ensureDirectoriesExist([
            'public/profile',
            'public/profile/thumbnail'
        ]);

        //filename to store
        $filenameToStore =  $time . '.' . $extension;
        $smallThumbnail = 't_' . $time . '.' . $extension;

        // Store Path
        $path = storage_path('app/public/profile/' . $filenameToStore);
        $smalThumbnailPath = storage_path('app/public/profile/thumbnail/' . $smallThumbnail);

        $img = $manager->read($image);
        // $img = $img->scale(width: 300);
        $img->cover(300, 300);
        $img->toPng()->save($path);

        $img = $manager->read($image);
        // $img = $img->scale(width:150);
        
        // scale down to fixed width
        // $img->scaleDown(width: 200); // 200 x 150
        // $img->scaleDown(height: 150); //  400 x 300

        $img->cover(200, 200);


        $img->toPng()->save($smalThumbnailPath);

        return [
            'profile_picture'  =>  $filenameToStore,
        ];
    }

    protected function ensureDirectoriesExist(array $directories)
    {
        foreach ($directories as $directory) {
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
        }
    }

    public function deleteImage($fileName)
    {
        Storage::delete('public/profile/' . $fileName);

        Storage::delete('public/profile/thumbnail/t_' . $fileName);
    }
}
