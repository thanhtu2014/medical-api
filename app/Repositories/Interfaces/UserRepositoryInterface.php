<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function getAllUsers();

    public function getUserByEmail($email);

    public function delete($id);

    public function create(array $orderDetails);

    public function update($id, array $newDetails);
}