<?php

namespace App\Service;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthenticationService
{

    public function login($validateData)
    {

        $startTime = microTime(true);

        $credentials = $validateData->only('identifier', 'password');

        if (filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $credentials['identifier'])->first();

        } else {
            $user = User::where('name', $credentials['identifier'])->first();
        }


        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->error($validateData, false, 'Invalid credentials', 401, $startTime);
        }

        if ($user->status === 0) {
            throw new Exception('Unauthorize to login');
        }

//        dd($user);


        $payload = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];


        $accessToken = JWTAuth::customClaims($payload)->attempt(['email' => $user->email, 'password' => $credentials['password']]);

        $refreshToken = JWTAuth::fromUser($user, ['refresh' => true]);

        $ttl = JWTAuth::factory()->getTTL();
        $expirationDateTime = Carbon::now()->addMinutes($ttl);

        $data = [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in' => $expirationDateTime->toDateTimeString()
        ];

        return response()->success($validateData, $data, 'Login Successfully', 200, $startTime, 1);
    }

    public function refresh($refreshToken)
    {
        $newAccessToken = JWTAuth::setToken($refreshToken)->refresh();

        $ttl = JWTAuth::factory()->getTTL();

        $expirationDateTime = Carbon::now()->addMinutes($ttl);

        $data = [
            'access_token' => $newAccessToken,
            'expires_in' => $expirationDateTime->toDateTimeString()
        ];

        return $data;
    }

    public function logout(Request $request)
    {
        $startTime = microtime(true);

        $user = Auth::guard('api')->user();

        if ($user) {
            Auth::guard('api')->logout();

            return response()->success($request, true, 'Logged out successfully', 200, $startTime, 1);
        } else {
            return response()->error($request, false, 'User not authenticated!', 401, $startTime);
        }
    }
}
