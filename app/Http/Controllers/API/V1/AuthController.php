<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\AuthLoginRequest;
use App\Http\Resources\API\V1\AuthLoginResource;
use App\Support\ApiResponse\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthController
 * @package App\Http\Controllers\API\V1
 */
class AuthController extends BaseApiController
{
    /**
     * Token lifetime in days.
     */
    const EXPIRATION_TIME = 30;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth:api', ['except' => ['login']]);
//    }

    /**
     * Get a JWT via given credentials.
     *
     * @param \App\Http\Requests\API\V1\AuthLoginRequest $request
     * @return JsonResponse|Response
     */
    public function login(AuthLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $expirationTime = ['exp' => Carbon::now()->addDays(self::EXPIRATION_TIME)->timestamp];

        try {
            $token = JWTAuth::attempt($credentials, $expirationTime);
            if ( ! $token ) {
                return ApiResponse::returnError(
                    ['password' => 'Invalid password!'],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        } catch (JWTException $e) {
            return ApiResponse::returnError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $authLoginResource = new AuthLoginResource($token);

        return ApiResponse::returnData($authLoginResource);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse|Response
     */
    public function logout()
    {
        try {

            JWTAuth::invalidate( JWTAuth::getToken() );
            return ApiResponse::returnData('User successfully signed out');

        } catch (JWTException $exception) {
            return ApiResponse::returnError('Sorry, the user cannot be logged out', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
