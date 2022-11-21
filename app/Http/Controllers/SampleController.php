<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class SampleController
 * @package App\Http\Controllers
 */
class SampleController extends Controller
{
    public function index()
    {
        $credentials = [
            'name' => 'test-name',
            'email' => 'test-email@gmail.com',
            'password' => 'test-password',
        ];

//        User::on()->create([
//            'name'      => $credentials['firstName'],
//            'password'  => bcrypt($credentials['password']),
//            'email'     => $credentials['email'],
//        ]);


        return 123;
    }
}
