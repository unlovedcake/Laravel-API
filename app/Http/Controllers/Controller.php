<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveImage($image, $path = 'public')
    {
        if (!$image) {
            return null;
        }




        $fileName   = time() . $image->getClientOriginalName();
        Storage::disk($path)->put($fileName, File::get($image));
        $file_name  = $image->getClientOriginalName();
        $file_type  = $image->getClientOriginalExtension();
        $filiSize = $this->fileSize($image);

        // save image
        //Storage::disk($path)->put($filename, base64_decode($image));

        //return the path
        // Url is the base url exp: localhost:8000
        return URL::to('/') . '/storage/' . $path . '/' . $fileName;
    }


    public function saveVideo($video, $path = 'public')
    {
        if (!$video) {
            return null;
        }




        $fileName   = time() . $video->getClientOriginalName();
        Storage::disk($path)->put($fileName, File::get($video));
        $file_name  = $video->getClientOriginalName();
        $file_type  = $video->getClientOriginalExtension();
        $filiSize = $this->fileSize($video);

        // save image
        //Storage::disk($path)->put($filename, base64_decode($image));

        //return the path
        // Url is the base url exp: localhost:8000
        return URL::to('/') . '/storage/' . $path . '/' . $fileName;
    }

    public function fileSize($file, $precision = 2)
    {
        $size = $file->getSize();

        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }
}
