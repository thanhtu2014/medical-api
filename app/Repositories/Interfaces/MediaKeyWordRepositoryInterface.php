<?php

namespace App\Repositories\Interfaces;

interface MediaKeyWordRepositoryInterface
{
    public function store(array $data);

    public function delete($id);
}