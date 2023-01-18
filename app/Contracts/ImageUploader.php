<?php

namespace App\Contracts;

use Illuminate\Http\File;

interface ImageUploader
{
    /**
     * Upload blog post preview image.
     *
     * @param File|string $file
     * @return string path to uploaded file
     */
    public function uploadImage($file);

    /**
     * Remove blog post preview image.
     *
     * @param string $path
     */
    public function removeImage($path);

}