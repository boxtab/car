<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends Repositories
{
    /**
     * @var User
     */
    protected $model;

    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $credentials
     */
    public function createUser(array $credentials)
    {
        $this->model->create([
            'name'      => $credentials['name'],
            'password'  => bcrypt($credentials['password']),
            'email'     => $credentials['email'],
        ]);
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function getUser(int $userId)
    {
        return $this->model->whereId($userId)->first();
    }

    /**
     * @param array $credentials
     * @param int $userId
     */
    public function updateUser(array $credentials, int $userId)
    {
        $this->model->whereId($userId)->update([
            'name'      => $credentials['name'],
            'password'  => bcrypt($credentials['password']),
            'email'     => $credentials['email'],
        ]);
    }

    /**
     * @param int $userId
     */
    public function deleteUser(int $userId)
    {
        $this->model->where('id', $userId)->delete();
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->model->select('id', 'name', 'email')->get();
    }
}
