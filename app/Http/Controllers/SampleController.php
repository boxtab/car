<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Class SampleController
 * @package App\Http\Controllers
 */
class SampleController extends Controller
{
    public function index()
    {
        return Hash::make('123qwe+++');

//        return bcrypt('123qwe+++');

//        phpinfo();
//        $length = 10;
//        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//        $charactersLength = strlen($characters);
//        $randomString = '';
//        for ($i = 0; $i < $length; $i++) {
//            $randomString .= $characters[rand(0, $charactersLength - 1)];
//        }
//        Log::info('sample');
//        return $randomString;
    }
}
