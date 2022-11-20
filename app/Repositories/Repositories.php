<?php

namespace App\Repositories;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repositories
 * @package App\Repositories
 */
abstract class Repositories
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Repositories constructor.
     *
     * @param Model $model
     */
    public function __construct( Model $model )
    {
        $this->model = $model;
    }

    public function __call( $name, $arguments )
    {
        return call_user_func_array( [$this->model, $name], $arguments );
    }
}
