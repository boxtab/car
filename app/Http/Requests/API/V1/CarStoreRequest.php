<?php

namespace App\Http\Requests\API\V1;

use App\Support\ApiRequest\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CarStoreRequest
 * @package App\Http\Requests\API\V1
 */
class CarStoreRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        return auth()->check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'brand' => 'required|string|min:2|max:64',
        ];
    }
}
