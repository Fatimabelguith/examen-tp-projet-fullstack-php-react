<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    /* CREATE */
    public function createPost(Request $request)
    {
        try {
            $userId = $request->userId;
            $description = $request->description;
    
            // Handle image upload
            // $picturePath = null;
            // if ($request->hasFile('picturePath')) {
            //     $picture = $request->file('picturePath');
            //     $picturePath = $picture->store('assets'); // Store the image in the 'images' directory
            // }
       /******* */         
        $picturePath = null;

        // Handle the image upload
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets'), $filename);
            $picturePath = asset('assets/' . $filename);
        }

       /******* */

            $user = User::find($userId);
            $newPost = new Post();
            $newPost->userId = $userId;
            $newPost->firstName = $user->firstName;
            $newPost->lastName = $user->lastName;
            $newPost->location = $user->location;
            $newPost->description = $description;
            $newPost->picturePath = $picturePath;  
            $newPost->userPicturePath = $user->picturePath;
            $newPost->likes = [];
            $newPost->comments = [];
            $newPost->save();
    
            $posts = Post::all();
            return response()->json($posts, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }
    

    /* READ */
    public function getFeedPosts()
    {
        try {
            $posts = Post::all();
            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function getUserPosts($userId)
    {
        try {
            $posts = Post::where('userId', $userId)->get();
            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /* UPDATE */
    public function likePost(Request $request, $id)
    {
        try {
            $userId = $request->userId;
            $post = Post::find($id);
            $postLikes = $post->likes;
            $isLiked = isset($postLikes[$userId]) ? $postLikes[$userId] : null;

            if ($isLiked !== null) {
                unset($postLikes[$userId]);
            } 
            
            if($isLiked == null){
                $postLikes[$userId] = true;
            }

            $post->likes=$postLikes;

            $post->save();

            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
