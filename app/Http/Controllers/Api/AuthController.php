<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreRegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(StoreRegisterRequest $request): JsonResponse {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success('Registrasi berhasil.', [
            'user'  => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    public function login(StoreLoginRequest $request): JsonResponse {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error('Email atau password salah.', 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success('Login berhasil.', [
            'user'  => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logout berhasil.');
    }

    public function me(Request $request): JsonResponse {
        return $this->success('Profil berhasil diambil.', [
            'user' => new UserResource($request->user()->load('roles')),
        ]);
    }

    public function refresh(Request $request): JsonResponse {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success('Token berhasil diperpanjang.', [
            'user'  => new UserResource($user),
            'token' => $token,
        ]);
    }
}