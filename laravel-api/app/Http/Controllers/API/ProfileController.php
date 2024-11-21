<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\API\UserResource;
use App\Http\Requests\API\UpdateProfileRequest;
use App\Http\Resources\API\ProfileResource;
use App\Models\Profile;
use App\Services\ProfileImageService;

class ProfileController extends Controller
{
    protected $profileImageService;

    public function __construct(ProfileImageService $profileImageService)
    {
        $this->profileImageService = $profileImageService;
    }

    public function getProfile($id){
        $user = User::with('profile')->find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return new UserResource($user);
    }

    public function update(UpdateProfileRequest $request, $id){

        if (auth()->id() !== $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $profile = Profile::where('user_id', $id)->first();
        if (!$profile) {
            return response()->json(['message' => 'Profile not found.'], 404);
        }

        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($profile->profile_picture) {
                $this->profileImageService->deleteImage($profile->profile_picture);
            }
            // Upload and save the new profile picture
            $imagePath = $this->profileImageService->handleImageUpload($request->file('profile_picture'));
            $data['profile_picture'] = $imagePath['profile_picture'];
        }

        $profile->update($data);
        $updatedProfile = Profile::where('user_id', $id)->first();

        return response()->json(["success" => true, "msg" => "Profile updated successfully", "profile" => new ProfileResource($updatedProfile)]);

    }
}
