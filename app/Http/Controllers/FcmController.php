<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserFcmToken;
use Illuminate\Support\Facades\Auth;

class FcmController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'device_type' => 'nullable|string',
        ]);

        $userId = Auth::id();
        $token = $request->token;
        $deviceType = $request->device_type ?? 'web';

        // Remove token association from any other users first (prevent shared browser token leakage)
        UserFcmToken::where('token', $token)
            ->where('user_id', '!=', $userId)
            ->delete();

        // Store or update the token for this user
        UserFcmToken::updateOrCreate(
            [
                'user_id' => $userId,
                'token' => $token,
            ],
            [
                'device_type' => $deviceType,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'FCM Token stored successfully.',
        ]);
    }
}
