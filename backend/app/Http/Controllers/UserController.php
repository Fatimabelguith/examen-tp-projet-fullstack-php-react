<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /* READ */
    public function getUser($id)
    {
        try {
            $user = User::find($id);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function getUserFriends($id)
    {
        try {
            $user = User::find($id);

            $friends = User::whereIn('id', $user->friends)->get();
            $formattedFriends = $friends->map(function ($friend) {
                return [
                    'id' => $friend->id,
                    'firstName' => $friend->firstName,
                    'lastName' => $friend->lastName,
                    'occupation' => $friend->occupation,
                    'location' => $friend->location,
                    'picturePath' => $friend->picturePath,
                ];
            });

            return response()->json($formattedFriends, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /* UPDATE */
    public function addRemoveFriend(Request $request, $id, $friendId)
    {
        try {
            $user = User::find($id);
            $friend = User::find($friendId);

    
            // Ensure $user->friends and $friend->friends are arrays or initialize as empty arrays if null
            $user->friends = $user->friends ?? [];
            $friend->friends = $friend->friends ?? [];

            $userFriends = $user->friends;
            $FriendFriends = $friend->friends;

            $userFriends[] = $friendId;
            $FriendFriends[] = $id;
            
            
    
            if (in_array($friendId, $userFriends)) {
                $userFriends = array_diff($userFriends, [$friendId]);
                $FriendFriends = array_diff($FriendFriends, [$id]);
                $user->friends = $userFriends;
                $friend->friends = $FriendFriends;
            } 
            
            if (!in_array($friendId, $userFriends)){

                $userFriends[] = $friendId;
                $FriendFriends[] = $id;
                $user->friends = $userFriends;
                $friend->friends = $FriendFriends;
            }
            
            // $userFriends[] = $friendId;
            // $FriendFriends[] = $id;
            // $user->friends = $userFriends;
            // $friend->friends = $FriendFriends;
            // return response()->json(["user:"=>$user,"friend:"=> $friend, "test"=>in_array($friendId, $user->friends)], 200);
            // Use push and pull methods to update relationships
            $user->push();
            $friend->push();
    
            $friends = User::whereIn('id', $user->friends)->get();
            $formattedFriends = $friends->map(function ($friend) {
                return [
                    'id' => $friend->id, // Assuming 'id' is the primary key
                    'firstName' => $friend->firstName,
                    'lastName' => $friend->lastName,
                    'occupation' => $friend->occupation,
                    'location' => $friend->location,
                    'picturePath' => $friend->picturePath,
                ];
            });
    
            return response()->json($formattedFriends, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    



}
