<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function delete($id)
    {
        User::destroy($id);
    }

    public function create(array $orderDetails)
    {
        return User::create($orderDetails);
    }

    public function update($id, array $newDetails)
    {
        return User::whereId($id)->update($newDetails);
    }

}