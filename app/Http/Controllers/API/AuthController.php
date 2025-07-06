<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Service\AuthenticationService;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    protected AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function login(Request $request)
    {
        $startTime = microtime(true);

        try {

            return  $this->authenticationService->login($request);
        } catch (Exception $e) {


            Log::channel('web_daily_error')->error('Error Retriving Users' . $e->getMessage());

            return response()->error($request, false, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }

    public function refresh(Request $request)
    {
        $startTime = microtime(true);

        $refreshToken = $request['refresh_token'];

        try {
            $data = $this->authenticationService->refresh($refreshToken);

            return response()->success($request, $data, 'Token refreshed successfully', 200, $startTime, 1);
        } catch (Exception $e) {
            Log::channel('web_daily_error')->error('Could not refresh token' . $e->getMessage());

            return response()->error($request, null, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }

    public function logout(Request $request)
    {
        $startTime = microtime(true);

        try {

            return  $this->authenticationService->logout($request);
        } catch (Exception $e) {

            Log::channel('web_daily_error')->error('Error Logging out' . $e->getMessage());

            return response()->error($request, false, $e->getMessage(), $e->getCode() ? $e->getCode() : 500, $startTime);
        }
    }
}
