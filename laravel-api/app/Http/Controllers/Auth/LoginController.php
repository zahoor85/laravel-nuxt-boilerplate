<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = User::where("email", $request->email)->first();

        $deviceName = $request->input('device_name');
        if ($deviceName) {
            $deviceName = preg_replace('/[^a-zA-Z0-9_-]/', '', $deviceName);
        } else {
            $deviceName = 'web';
            $request->session()->regenerate();
        }

        // Generate Token for the API
        $token = $deviceName !== 'web' ? $user->createToken($deviceName . ':' . $user->name)->plainTextToken : null;
        $data = [
            "user" => [
                "token" => $token,
                "userId" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "email_verified_at" => $user->email_verified_at
            ]
        ];
        return response()->json($data, 201);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        // Clear Session in-case exists
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $plainToken = $request->bearerToken();
        if ($plainToken) {
            // Laravel return ID|TOKEN
            $tokenParts = explode('|', $plainToken);
            $tokenValue = $tokenParts[1] ?? null;
            if ($tokenValue) {
                $hashedToken = hash('sha256', $tokenValue);
                $request->user()->tokens()->where('token', $hashedToken)->delete();
            }
        } else {
            $request->user()->tokens()
                ->where('name', 'like', 'web:%')
                ->delete();
        }

        return response()->noContent();
    }
}
