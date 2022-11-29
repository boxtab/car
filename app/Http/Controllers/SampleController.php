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
        return url()->current();
    }
}
