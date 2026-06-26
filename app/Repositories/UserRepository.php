<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements IUserRepository
{
    public $model;

    /**
     * UserRepository constructor
     * 
     * @param Model $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Find using email
     * 
     * @param string $email
     */
    public function findByEmail(string $email) :? Model
    {
        return $this->model->whereEmail($email)->first();
    }
}