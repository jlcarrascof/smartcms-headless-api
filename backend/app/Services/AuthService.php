<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Attempt to authenticate a user with credentials and return the JWT token structure.
     *
     * @param array $credentials
     * @return array|null
     */
    public function login(array $credentials): ?array
    {
        // Guard attempt with JWT Auth
        if (!$token = auth()->attempt($credentials)) {
            return null;
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register a new user and return their authentication token structure.
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        // Create user with default editor role as per sprint requirements
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'editor',
        ]);

        // Login user right after registration
        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }

    /**
     * Standardize the authentication response structure with JWT payload.
     *
     * @param string $token
     * @return array
     */
    private function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => auth()->user(),
        ];
    }
}
