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
     *              example={{"id"=1, "name"="test-name", "email"="test1@gmail.com"},{"id"=2, "name"="test2", "email"="test2@gamil.com"}},
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
     * Show the form for creating a new resource.
     *
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\JsonResponse|Response
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
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|Response
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
     * Update the specified resource in storage.
     *
     * @param UserStoreRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|Response
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
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|Response
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
