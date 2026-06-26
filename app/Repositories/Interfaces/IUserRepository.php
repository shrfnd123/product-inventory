<?php
namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IUserRepository extends IBaseRepository {
    /**
     * Find using email
     * 
     * @param string $email
     */
    public function findByEmail(string $email) :? Model;
}