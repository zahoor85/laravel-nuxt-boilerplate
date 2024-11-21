<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\API\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json(new UserResource($user));
    }

    public function fetchUsers(Request $request)
    {
        $perPage = $request->get('per_page', 2); // Default to 3 items per page
        $searchQuery = $request->get('search', '');

        $query = User::with('profile')
            ->select(['id', 'name', 'email', 'email_verified_at'])
            ->orderBy('created_at', 'desc');

        if (!empty($searchQuery)) {
            $query->where('name', 'LIKE', '%' . $searchQuery . '%')
                ->orWhereHas('profile', function ($query) use ($searchQuery) {
                    $query->where('full_name', 'LIKE', '%' . $searchQuery . '%');
                });
        }

        $users = $query->paginate($perPage);

        return response()->json([
            "message" => "Fetch Users",
            "users" => UserResource::collection($users),
            "users_meta" => [
                "next_page_url" => $users->nextPageUrl() ? $users->nextPageUrl() . "&search=" . urlencode($searchQuery) : null,
                "prev_page_url" => $users->previousPageUrl() ? $users->previousPageUrl() . "&search=" . urlencode($searchQuery) : null,
            ]
        ]);
    }

    public function updateUsername(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . auth()->id(),
        ]);
        $user = auth()->user();

        if ($user->id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user->name = $request->name;
        $user->save();
        return response()->json(new UserResource($user));
    }

}
