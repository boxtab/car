<?php

use App\Http\Controllers\API\V1\CarController;
use App\Http\Controllers\API\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


/**
 * =====================================================================================================================
 * SHARED
 * =====================================================================================================================
 */
Route::group(['prefix' => 'shared/v1'], function () {
    /**
     * Entity: Cars
     * Table: cars
     */
    Route::group(['prefix' => 'car'], function () {

        // POST /api/front/v1/car
        Route::post('/', [CarController::class, 'create']);

        // GET /api/admin/v1/car/:id
        Route::get('{id}', [CarController::class, 'show'])->where('id', '[0-9]+');

        // PUT /api/admin/v1/car/:id
        Route::put('{id}', [CarController::class, 'update'])->where('id', '[0-9]+');

        // DELETE /api/admin/v1/car/:id
        Route::delete('{id}', [CarController::class, 'destroy'])->where('id', '[0-9]+');

        // GET /api/admin/v1/car/list
        Route::get('list', [CarController::class, 'index']);
    });

    /**
     * Entity: Users
     * Table: users
     */
    Route::group(['prefix' => 'user'], function () {

        // POST /api/front/v1/user
        Route::post('/', [UserController::class, 'create']);

        // GET /api/admin/v1/user/:id
        Route::get('{id}', [UserController::class, 'show'])->where('id', '[0-9]+');

        // PUT /api/admin/v1/user/:id
        Route::put('{id}', [UserController::class, 'update'])->where('id', '[0-9]+');

        // DELETE /api/admin/v1/user/:id
        Route::delete('{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+');

        // GET /api/admin/v1/user/list
        Route::get('list', [UserController::class, 'index']);
    });
});


/**
 * =====================================================================================================================
 * ADMIN
 * =====================================================================================================================
 */
Route::group(['prefix' => 'admin/v1', 'middleware' => ['auth:api', 'user_already_logged_in', 'cors']], function () {

});
