<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\BookingRequest;
use App\Http\Resources\API\V1\BookingResource;
use App\Repositories\BookingRepository;
use App\Repositories\CarRepository;
use App\Support\ApiResponse\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class BookingController
 * @package App\Http\Controllers\API\V1
 */
class BookingController extends BaseApiController
{
    /**
     * BookingController constructor.
     *
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking = $this->repository->getList();

        return ApiResponse::returnData(BookingResource::collection($booking));
    }

    /**
     * Assign a car to the user.
     *
     * @param BookingRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function toAppoint(BookingRequest $request)
    {
        try {
            $this->repository->toAppoint($request->all());
        } catch (Exception $e) {
            if ($e->getCode() == 23000) {
                return ApiResponse::returnError('The car is already booked.');
            } else {
                return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return ApiResponse::returnData([]);
    }

    /**
     * The user free the car.
     *
     * @param BookingRequest $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function toFree(BookingRequest $request)
    {
        try {
            $this->repository->toFree($request->all());
        } catch (Exception $e) {
            return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::returnData([]);
    }
}
