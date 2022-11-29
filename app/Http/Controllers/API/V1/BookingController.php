<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\BookingRequest;
use App\Http\Resources\API\V1\BookingResource;
use App\Jobs\ResearcherJob;
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
     * @OA\Get(
     * path="/front/booking",
     * summary="list",
     * tags={"booking"},
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(
     *          @OA\Property(property="succes", type="boolean", example="true"),
     *          @OA\Property(property="data", type="array",
     *              example={{"user_id"=2, "car_id"=3},{"user_id"=3, "car_id"=4}},
     *              @OA\Items(
     *                  @OA\Property(property="user_id", type="integer"),
     *                  @OA\Property(property="car_id", type="integer"),
     *              ),
     *          ),
     *       )
     *    )
     * )
     */
    public function index()
    {
        $booking = $this->repository->getList();
        ResearcherJob::dispatch();

        return ApiResponse::returnData(BookingResource::collection($booking));
    }

    /**
     * @OA\Post(
     * path="/front/booking",
     * summary="toAppoint",
     * tags={"booking"},
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *          required={"user_id", "car_id"},
     *          @OA\Property(property="user_id", type="integer", example=5),
     *          @OA\Property(property="car_id", type="integer", example=7),
     *     ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(
     *          @OA\Property(property="succes", type="boolean", example="true"),
     *          @OA\Property(property="data", type="array", @OA\Items(), example={}),
     *      )
     *   ),
     * )
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
     * @OA\Delete(
     * path="/front/booking",
     * summary="toFree",
     * tags={"booking"},
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *          required={"user_id", "car_id"},
     *          @OA\Property(property="user_id", type="integer", example=11),
     *          @OA\Property(property="car_id", type="integer", example=22),
     *     ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(
     *          @OA\Property(property="succes", type="boolean", example="true"),
     *          @OA\Property(property="data", type="array", @OA\Items(), example={}),
     *      )
     *   ),
     * )
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
