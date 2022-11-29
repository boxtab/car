<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\UserStoreRequest;
use App\Http\Resources\API\V1\CarCollection;
use App\Http\Resources\API\V1\UserResource;
use App\Repositories\UserRepository;
use App\Support\ApiResponse\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Log;
use Ramsey\Collection\Collection;

/**
 * Class UserController
 * @package App\Http\Controllers\API\V1
 */
class UserController extends BaseApiController
{
    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * @OA\Get(
     * path="/admin/user/list",
     * summary="list",
     * tags={"user"},
     * @OA\Response(
     *    response=200,
     *    description="OK",
     *    @OA\JsonContent(
     *          @OA\Property(property="succes", type="boolean", example="true"),
     *          @OA\Property(property="data", type="array",
     *              example={{"id"=1, "name"="Ivan", "email"="ivan@gmail.com"},{"id"=2, "name"="vasya", "eamil"="vasya@gmail.com"}},
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="email", type="string"),
     *              ),
     *          ),
     *       )
     *    )
     * )
     */
    public function index()
    {
        $users = $this->repository->getList();

        return ApiResponse::returnData(UserResource::collection($users));
    }

    /**
     * @OA\Post(
     * path="/admin/user",
     * summary="create",
     * tags={"user"},
     * @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *     @OA\JsonContent(
     *          required={"name", "email", "password"},
     *          @OA\Property(property="name", type="string", example="Jim"),
     *          @OA\Property(property="email", type="string", example="jim@gmail.com"),
     *          @OA\Property(property="password", type="string", example="12345qwe"),
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
    public function create(UserStoreRequest $request)
    {
        try {
            $this->repository->createUser($request->all());
        } catch (Exception $e) {
            return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::returnData([]);
    }

    /**
     * @OA\Get(
     *     path="/admin/user/{id}",
     *     summary="read",
     *     tags={"user"},
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
     *              @OA\Property(property="data", type="array", @OA\Items(), example={"id"=1, "name"="smith", "email"="smith@gmail.com"},
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="name", type="string"),
     *                      @OA\Property(property="email", type="string"),
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
            $user = $this->repository->getUser($id);
        } catch (Exception $e) {
            return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::returnData(new UserResource($user));
    }

    /**
     * @OA\Put(
     *      path="/admin/user/{id}",
     *      tags={"user"},
     *      summary="update",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *     ),
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *          required={"name", "email", "password"},
     *          @OA\Property(property="name", type="string", example="sasha"),
     *          @OA\Property(property="email", type="string", example="sasha@gmail.com"),
     *          @OA\Property(property="password", type="string", example="zxc123asd"),
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
    public function update(UserStoreRequest $request, $id)
    {
        try {
            $this->findRecordByID($id);
            $this->repository->updateUser($request->all(), $id);

        } catch (Exception $e) {
            return ApiResponse::returnError($e->getMessage(), $e->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::returnData([]);
    }

    /**
     * @OA\Delete(
     *      path="/admin/user/{id}",
     *      tags={"user"},
     *      summary="delete",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
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
            $this->repository->deleteUser($id);
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
