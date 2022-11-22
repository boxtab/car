<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CarRepository
 * @package App\Repositories
 */
class BookingRepository extends Repositories
{
    /**
     * @var Booking
     */
    protected $model;

    /**
     * BookingRepository constructor.
     *
     * @param Booking $model
     */
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }


    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->model->get();
    }

    /**
     * @param array $booking
     */
    public function toAppoint(array $booking)
    {
        $this->create([
            'user_id' => $booking['user_id'],
            'car_id' => $booking['car_id'],
        ]);
    }

    /**
     * @param array $booking
     */
    public function toFree(array $booking)
    {
        $this->model
            ->where('user_id', $booking['user_id'])
            ->where('car_id', $booking['car_id'])
            ->delete();
    }
}
