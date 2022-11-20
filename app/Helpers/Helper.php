<?php

namespace App\Helpers;

use App\Models\ModelHasRole;
use App\Support\ApiResponse\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use stdClass;
use App\Constants\RoleConstant;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

/**
 * Class Helper.
 *
 * @package App\Helpers
 */
class Helper
{
    /**
     * Converting an array to a collection.
     *
     * @param array $messages
     * @return Collection
     */
    public static function arrayToCollection(array $messages): Collection
    {
        $classMessages = new stdClass();
        foreach ($messages as $key => $text) {
            $classMessages->$key = $text;
        }
        return collect($classMessages);
    }

    /**
     * Makes a string out of a set of array elements.
     *
     * @param $objectContainsArrays
     * @return stdClass
     */
    public static function replaceArraysOnStrings( $objectContainsArrays )
    {
        if ( gettype( $objectContainsArrays ) === 'string' ) {
            return $objectContainsArrays;
        }

        if ( gettype( $objectContainsArrays ) === 'array') {
            $objectContainsStrings = new stdClass();
            foreach ($objectContainsArrays as $key => $value) {
                $objectContainsStrings->$key = $value;
            }
            return $objectContainsStrings;
        };

        $objectContainsStrings = new stdClass();
        foreach ($objectContainsArrays->toArray() as $key => $value) {
            $objectContainsStrings->$key = implode($value);
        }

        return $objectContainsStrings;
    }

    /**
     * Convert array keys from camelCase to snake_case.
     *
     * @param array $request
     * @return array
     */
    public static function formatSnakeCase(array $request): array
    {
        $replaced = [];
        foreach ($request as $key => $field) {
            $replaced[Str::snake($key)] = $field;
        }
        return $replaced;
    }

    /**
     * Get a link to reset your password.
     *
     * @param string $token
     * @param string $email
     * @return string
     */
    public static function getLinkResetPassword(string $token, string $email): string
    {
        $host = request()->getSchemeAndHttpHost();
        return "$host/reset-password/$token/$email";
    }
}
