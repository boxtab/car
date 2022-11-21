<?php

namespace App\Http\Requests\API\V1;

use App\Support\ApiRequest\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserStoreRequest
 * @package App\Http\Requests\API\V1
 */
class UserStoreRequest extends ApiRequest
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
            'name' => ['required', 'string', 'min:2', 'max:255'],
            // https://stackoverflow.com/questions/40942367/how-validate-unique-email-out-of-the-user-that-is-updating-it-in-laravel
            'email' => ['required', 'email:rfc,dns', 'min:5', 'max:255'],
            'password' => ['required', 'string', 'min:2', 'max:64'],
        ];
    }
}
