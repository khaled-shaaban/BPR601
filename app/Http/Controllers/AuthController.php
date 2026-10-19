<?php

namespace App\Http\Controllers;

use App\Enums\AppRoles;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\NotificationPreferencesRequest;
use App\Http\Requests\Auth\PasswordRequest;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $user->assignRole(AppRoles::USER->value);
        $user->load('roles');

        $token = $user->createToken('auth')->plainTextToken;

        return $this->respondWithData([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('phone', $request->validated('phone'))->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            return $this->respondWithFailure('The provided credentials are incorrect.', 401);
        }

        if (! $user->active) {
            return $this->respondWithFailure('This account has been disabled.', 403);
        }

        $user->load('roles');

        $token = $user->createToken('auth')->plainTextToken;

        return $this->respondWithData([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function me()
    {
        return $this->respondWithData([
            'user' => request()->user()->load(['roles', 'authority:id,name']),
        ]);
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();

        return $this->respondWithSuccess('Logged out successfully.');
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        return $this->respondWithData([
            'user' => $user->fresh()->load('roles'),
        ]);
    }

    public function updateNotifications(NotificationPreferencesRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        return $this->respondWithData([
            'user' => $user->fresh()->load(['roles', 'authority:id,name']),
        ]);
    }

    public function updatePassword(PasswordRequest $request)
    {
        $user = $request->user();

        if (! Hash::check($request->validated('current_password'), $user->password)) {
            return $this->respondWithFailure('The current password is incorrect.', 422);
        }

        $user->update(['password' => $request->validated('password')]);

        return $this->respondWithSuccess('Password updated successfully.');
    }
}
