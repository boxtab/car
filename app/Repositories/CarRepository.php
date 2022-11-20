<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Models\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CarRepository
 * @package App\Repositories
 */
class CarRepository extends Repositories
{
    /**
     * @var Car
     */
    protected $model;

    /**
     * CarRepository constructor.
     *
     * @param Car $model
     */
    public function __construct(Car $model)
    {
        parent::__construct($model);
    }

    /**
     * Create car.
     *
     * @param array $car
     * @throws \Exception
     */
    public function createCar(array $car)
    {
        $this->create([
            'brand' => $car['brand'],
        ]);
    }

    /**
     * @param int $carId
     * @return mixed
     */
    public function getCar(int $carId)
    {
        return $this->model->whereId($carId)->first();
    }

    /**
     * @param array $car
     * @param int $carId
     */
    public function updateCar(array $car, int $carId)
    {
        $this->model->whereId($carId)->update($car);
    }

    /**
     * @param int $carId
     */
    public function deleteCar(int $carId)
    {
        $this->model->where('id', $carId)->delete();
    }
}
