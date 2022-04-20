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

    public function getUserById($id)
    {
        return User::where('id', $id)->first();
    }

    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function getUserByCode($code)
    {
        return User::where(['code' => $code, 'chg' => CHG_VALID_VALUE])->first();
    }

    public function getUserByGoogleId($googleId)
    {
        return User::where('google_id', $googleId)->first();
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