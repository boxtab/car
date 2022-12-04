<?php

namespace App\Http\Requests\API\V1;

use App\Support\ApiRequest\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserRegistrationRequest
 * @package App\Http\Requests\API\V1
 */
class UserRegistrationRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name'      => 'nullable|min:1|max:255',
            'email'     => 'required|email:rfc,dns|min:5|max:255',
            'password'  => 'required|min:6|max:64',
        ];
    }
}
