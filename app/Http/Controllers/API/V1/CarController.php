<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\CarStoreRequest;
use App\Http\Resources\API\V1\CarResource;
use App\Repositories\CarRepository;
use App\Support\ApiResponse\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseApiController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class CarController
 * @package App\Http\Controllers\API\V1
 */
class CarController extends BaseApiController
{
    /**
     * CarController constructor.
     *
     * @param CarRepository $carRepository
     */
    public function __construct(CarRepository $carRepository)
    {
        $this->repository = $carRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CarStoreRequest $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function create(CarStoreRequest $request)
    {
        try {
            $this->repository->createCar([
                'brand' => $request->input('brand')
            ]);
        } catch (Exception $e) {
            return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::returnData([]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function show($id)
    {
        try {
            $this->findRecordByID($id);
            $car = $this->repository->getCar($id);
        } catch (Exception $e) {
            return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::returnData(new CarResource($car));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CarStoreRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function update(CarStoreRequest $request, $id)
    {
        try {
            $this->findRecordByID($id);
            $this->repository->updateCar($request->all(), $id);

        } catch (Exception $e) {
            return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return ApiResponse::returnData([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->findRecordByID($id);
            $this->repository->deleteCar($id);
        } catch (Exception $e) {
            if ($e->getCode() == 23000) {
                return ApiResponse::returnError('The car cannot be deleted there are links to it.');
            } else {
                return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return ApiResponse::returnData([]);
    }
}
