<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): array
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => 'The provided credentials are incorrect.']);
        }

        return [
            'token' => $user->createToken('ris-v1')->plainTextToken,
            'user' => $user->load('roles'),
        ];
    }

    public function me(Request $request): User
    {
        return $request->user()->load('roles');
    }

    public function logout(Request $request): array
    {
        $request->user()->currentAccessToken()?->delete();

        return ['message' => 'Logged out.'];
    }
}
