<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function index()
    {

        return User::all();
    }


    // get all user details
    public function getAllUsers()
    {
        return response([

            'user' => User::all(),
            'message' => 'Display All Users.',
        ], 200);
    }

    public function register(Request $request)
    {

        $fieldsValue = request()->validate([

            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'address' => 'required|string',
            'contact' => 'required|string',
            'password' => 'required|min:6|confirmed'
        ]);



        $user = User::create([
            'name' => $fieldsValue['name'],
            'email' => $fieldsValue['email'],
            'address' => $fieldsValue['address'],
            'contact' => $fieldsValue['contact'],
            'image' => 'https://media.istockphoto.com/vectors/default-profile-picture-avatar-photo-placeholder-vector-illustration-vector-id1223671392?k=20&m=1223671392&s=612x612&w=0&h=lGpj2vWAI3WUT1JeJWm1PRoHT3V15_1pdcTn2szdwQ0=',
            'password' => bcrypt($fieldsValue['password']),


        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;



        $response = [
            'user' => $user,
            'token' =>   $token
        ];


        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        // if (!Auth::attempt($fields)) {
        //     return response([
        //         'message' => 'Invalid credentials.'
        //     ], 403);
        // }

        if (!$user) {
            return response([
                'message' => 'Email is not registered'
            ], 401);
        } else if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Incorrect Password'
            ], 401);
        } else {
            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user' => $user,
                'message' => "You are now logged in.",
                'token' => $token
            ];
        }



        return response($response, 200);
    }


    // get specific user details
    public function updateSpecificUser(Request $request, $id)
    {
        $user = User::find($id);
        $attrs = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'contact' => 'required|string',

        ]);
        $path = storage_path() . '/app/public/profiles/' . $user->fileName;

        //dd($path);
        if (File::exists($path)) {

            unlink($path);
        }

        $fileName   = time() . $request->image->getClientOriginalName();
        $image = $this->saveImage($request->image, 'profiles');


        //$id = auth()->user()->id;
        $user = User::findOrFail($id);


        $user->update([
            'name' => $attrs['name'],
            'address' => $attrs['address'],
            'contact' => $attrs['contact'],
            'image' => $image,
            'fileName' => $fileName
        ]);

        return response([
            'message' => 'User updated Successfully.',
            'user' => $user
        ], 200);
    }

    // get user details
    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }
}
