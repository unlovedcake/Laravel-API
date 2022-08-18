<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Carbon;

class PostController extends Controller
{

    public function index()
    {

        $post = Post::join('users', 'users.id', '=', 'posts.user_id')
            //->where('posts.user_id', '=', 2)
            ->get(['posts.id', 'posts.user_id', 'users.name', 'users.image', 'posts.body']);
        //->get(['users.*', 'posts.body']);
        //return Post::collection($user->posts);
        return response([
            'message' => 'Get User Post.',
            'post' => $post,
        ], 200);
    }

    // create a post
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string',
            'file' => 'mimes:csv,txt,xlx,xls,pdf,docx|max:2048',
            'image' => 'mimes:doc,docx,jpeg,png,jpg,gif,svg,pdf|max:2048',
            'video' => 'mimetypes:audio/mp3,audio/mp4,video/mp4,video/avi,video/mpeg,video/quicktime|max:102400'

        ]);


        // if (!$request->file('image')) {
        //     return response()->json(['Upload file not found.'], 400);
        // }
        //$files = $request->file('image');

        //$image = $this->saveImage($request->image, 'posts');
        $image = null;
        $videoFile = null;

        if ($request->file('image')) {
            $image = $this->saveImage($request->image, 'profiles');
        } else if ($request->file('video')) {
            $videoFile = $this->saveVideo($request->video, 'profiles');
        } else if ($request->file('file')) {
            $filePost = $this->saveVideo($request->video, 'profiles');
        }



        $timeStamp = 123;

        //$fileName   = time() . $request->image->getClientOriginalName();


        if ($attrs) {

            $post = Post::create([
                'body' => $attrs['body'],
                'user_id' => auth()->user()->id,
                'imagePost' => $image,
                'videoPost' => $videoFile,
                'timeStamp' => Carbon::now()->timestamp . $timeStamp,

                'image' => Auth::user()->image,
            ]);

            //     $randomNumber = random_int(1000, 9999);

            //     $post = new Post();

            //     if ($files == null) {
            //         $post->body = $attrs['body'];
            //         $post->user_id = auth()->user()->id;
            //         $post->image =  null;
            //         $post->timeStamp =  Carbon::now()->timestamp . $timeStamp;
            //         $post->save();
            //     } else {
            //         foreach ($files as $mediaFiles) {



            //             $path = $this->saveImage($mediaFiles, 'profiles');
            //             $name =  time() .  $mediaFiles->getClientOriginalName();

            //             $post->body = $attrs['body'];
            //             $post->user_id = auth()->user()->id;
            //             $post->image =  $path;
            //             $post->timeStamp =  Carbon::now()->timestamp . $timeStamp;
            //             $post->save();


            //             // $save = new Image();
            //             // $save->post_id =   $post->id;
            //             // $save->title = $name;
            //             // $save->path = $path;
            //             // $save->save();
            //         }
            //     }
        } else {
            return response()->json(['Invalid file format'], 422);
        }








        // for now skip for post image

        return response([
            'message' => 'Post created.',
            'post' => $post,
        ], 200);
    }

    // update a post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $post->update([
            'body' =>  $attrs['body']
        ]);

        // for now skip for post image

        return response([
            'message' => 'Post updated.',
            'post' => $post
        ], 200);
    }


    // get all user details
    public function getAllUserPost()
    {
        $post = Post::join('users', 'users.id', '=', 'posts.user_id')
            //->where('posts.user_id', '=', 2)
            // ->join('images', 'images.post_id', '=', 'posts.id')

            ->get(['posts.id', 'posts.user_id', 'posts.timeStamp', 'posts.imagePost', 'users.name', 'users.image', 'posts.body']);

        return response([
            'message' => 'Get User All Post.',
            'post' => $post,
        ], 200);
    }
}
