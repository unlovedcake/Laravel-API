<?php

namespace App\Http\Controllers\API;

use Validator;

use App\Models\Image;

use App\Models\Video;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MultipleUploadController extends Controller
{

    public function index()
    {

        return response([

            'images' => Image::all(),
            'message' => 'Display All Images.',
        ], 200);
    }


    public function store(Request $request)

    {


        $check = $request->validate([
            'path.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        if (!$request->file('path')) {
            return response()->json(['Upload file not found.'], 400);
        }

        $files = $request->file('path');

        if ($check) {
            foreach ($files as $mediaFiles) {



                $path = $this->saveImage($mediaFiles, 'profiles');
                $name =  time() .  $mediaFiles->getClientOriginalName();
                //$path = $mediaFiles->move(public_path('/app/public/profiles') . $name);
                //store image file into directory and db
                $save = new Image();
                $save->title = $name;
                $save->path = $path;
                $save->save();
            }
        } else {
            return response()->json(['Invalid file format'], 422);
        }

        return response()->json(['image' => $save, 'message' => 'File uploaded successfully.'], 200);
    }

    public function addVideo(Request $request)
    {
        $check = $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:102400'
            //'video' => 'required|file|mimetypes:video/mp4',
        ]);



        $videoFile = $this->saveVideo($request->video, 'video_files');


        $video = new Video;

        if ($request->hasFile('video')) {
            // $path = $request->file('video')->store('videos', ['disk' =>      'my_files']);
            // $video->video = $path;

            if ($check) {
                $video->title = $request->title;
                $video->video = $videoFile;
                $video->save();
            } else {
                return response()->json(['Invalid file format'], 422);
            }
        } else {
            return response()->json(['No file slected'], 400);
        }


        return response([
            'message' => 'Video File created.',
            'video' => $video,
        ], 200);
    }
}
