<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\EmailChangeVerificationMail;
use App\Models\User;

class EmailChangeController extends Controller
{

    public function requestEmailChange(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'new_email' => ['required', 'email', 'unique:users,email'],
        ]);
        $token = Str::random(60);
        DB::table('email_changes')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'old_email' => $user->email,
                'new_email' => $request->new_email,
                'token' => $token,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        Mail::to($user->email)->send(new EmailChangeVerificationMail($token));

        return response()->json(['message' => 'Verification link sent to your current email.']);
    }

    public function updateEmailConfirmed(Request $request)
    {

        $token = $request->input('token');
        $emailChange = DB::table('email_changes')->where('token', $token)->first();
        if (!$emailChange) {
            return response()->json(['message' => 'Invalid or expired token.'], 422);
        }

        $user = User::find($emailChange->user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->email = $emailChange->new_email;
        $user->save();

        // DB::table('email_changes')->where('token', $token)->delete();
        DB::table('email_changes')->where('token', $token)->update([
            'token' => null,
            'changed_at' => now(),
            'updated_at' => now(), // Update the updated_at timestamp
        ]);
        return response()->json(['message' => 'Email updated successfully.'], 201);
    }
}
