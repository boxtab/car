<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\CarStoreRequest;
use App\Http\Resources\API\V1\CarCollection;
use App\Http\Resources\API\V1\CarResource;
use App\Repositories\CarRepository;
use App\Support\ApiResponse\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseApiController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
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
     * @OA\Get(
     * path="/admin/car/list",
     * summary="list",
     * tags={"car"},
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(
     *          @OA\Property(property="succes", type="boolean", example="true"),
     *          @OA\Property(property="data", type="array",
     *              example={{"id"=4, "Brand"="Audi"},{"id"=5, "Brand"="Chevrolet"}},
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="brand", type="string"),
     *              ),
     *          ),
     *       )
     *    )
     * )
     * @OA\Response(response="404", description="fail")
     */
    public function index()
    {
        $cars = $this->repository->getList();
        $carCollection = new CarCollection($cars);

        return ApiResponse::returnData($carCollection);
    }

    /**
     * @OA\Post(
     * path="/admin/car",
     * summary="create",
     * tags={"car"},
     * @OA\RequestBody(
     *     required=true,
     *     description="Pass car credentials",
     *     @OA\JsonContent(
     *          required={"brand"},
     *          @OA\Property(property="brand", type="string", example="Mercedes"),
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
     * @OA\Response(
     *     response=500,
     *     description="My intenal server error",
     *     @OA\JsonContent(
     *      )
     * )
     * )
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
     * @OA\Get(
     *     path="/admin/car/{id}",
     *     summary="read",
     *     tags={"car"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="succes", type="boolean", example="true"),
     *              @OA\Property(property="data", type="array", @OA\Items(), example={"id"=3, "brand"="Ford"},
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="brand", type="string"),
     *                  ),
     *              )
     *          )
     *     ),
     * )
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
     * @OA\Put(
     *      path="/admin/car/{id}",
     *      tags={"car"},
     *      summary="update",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *     ),
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *          required={"brand"},
     *          @OA\Property(property="brand", type="string", example="Volvo"),
     *     ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     * )
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
     * @AO\Delete(
     *     path="/admin/car/{id}",
     *     tags={"car"},
     *     summary="delete",
     *     @AO\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          type="string",
     *     ),
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
