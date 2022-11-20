<?php

use App\Http\Controllers\API\V1\CarController;
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
    });
});


/**
 * =====================================================================================================================
 * ADMIN
 * =====================================================================================================================
 */
Route::group(['prefix' => 'admin/v1', 'middleware' => ['auth:api', 'user_already_logged_in', 'cors']], function () {

});
