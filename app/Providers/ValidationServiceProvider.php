<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('validationError', function (Request $request, Validator $validator) {
            throw new HttpResponseException(
                response()->json([
                    'success' => 0,
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'meta' => [
                        'method' => $request->getMethod(),
                        'endpoint' => $request->path(),
                        'limit' => $request->input('limit', 0),
                        'offset' => $request->input('offset', 0),
                        'total' => 0,
                    ],
                    'data' => [
                        'message' => 'The given data was invalid.',
                        'errors' => $validator->errors(),
                    ],
                    'duration' => (float)sprintf("%.3f", (microtime(true) - LARAVEL_START)),
                ], Response::HTTP_UNPROCESSABLE_ENTITY)
            );
        });
    }
}
