<?php

namespace App\Http\Controllers\Api;

use App\Enums\Users\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Sign\SignRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SignController extends Controller
{
    public function signIn(SignRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::whereStatus(UserStatus::ADMIN)
            ->whereIsActivated(true)
            ->where('email', $validated['email'])
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'data' => [
                'token' => $user->createToken('cms')->plainTextToken,
            ],
        ]);
    }

    public function signOut(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['data' => null]);
    }
}
