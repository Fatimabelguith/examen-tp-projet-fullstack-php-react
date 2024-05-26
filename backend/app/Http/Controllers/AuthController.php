<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    /* REGISTER USER */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'email' => 'required|email|unique:users|max:50',
                'password' => 'required|string|min:5',
                'picturePath' => 'nullable|string',
                'friends' => 'nullable|array',
                'location' => 'nullable|string',
                'occupation' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $picturePath = null;

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets'), $filename);
                $picturePath = asset('assets/' . $filename);
            }

            $user = new User();
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->picturePath = $picturePath;
            $user->friends = $request->friends;
            $user->location = $request->location;
            $user->occupation = $request->occupation;
            $user->viewedProfile = mt_rand(0, 9999);
            $user->impressions = mt_rand(0, 9999);
            $user->save();

            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), "e"=>$user], 500);
        }
    }

    /* LOGGING IN */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user  ) {
                return response()->json(['error' => 'User does not exist.'], 400);
            }
           
             if (!Hash::check($request->password, $user->password)) {
                 return response()->json(['error' => 'Invalid credentials.'], 400);
             }

             $token = JWT::encode(['id' => $user->id], env('JWT_SECRET'), 'HS256');

             unset($user->password);

             return response()->json(['token' => $token, 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
