<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\Repositories;
use App\Support\ApiResponse\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
//use phpDocumentor\Reflection\DocBlock\Tags\Param;
use stdClass;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Cars Project Documentation",
 *      description="Cars OpenApi description",
 *      @OA\Contact(
 *          email="boxtab@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Car License",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Car API Server"
 * )

 *
 * @OA\Tag(
 *     name="Projects Car",
 *     description="API Endpoints of cars"
 * )
 */
class BaseApiController extends Controller
{
    /**
     * @var Repositories
     */
    protected $repository;

    /**
     * Search for a record by ID.
     *
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    protected function findRecordByID(int $id)
    {
        $record = $this->repository->find($id);

        if ( is_null( $record ) ) {
            throw new \Exception('Record not found for this ID', Response::HTTP_NOT_FOUND);
        }

        return $record;
    }
}
