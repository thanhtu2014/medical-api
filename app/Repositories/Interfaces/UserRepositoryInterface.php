<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function getAllUsers();

    public function getUserById($id);

    public function getUserByEmail($email);

    public function getUserByCode($code);

    public function getUserByGoogleId($googleId);

    public function delete($id);

    public function create(array $orderDetails);

    public function update($id, array $newDetails);
}