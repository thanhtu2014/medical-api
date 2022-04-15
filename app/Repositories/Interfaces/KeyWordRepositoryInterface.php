<?php

namespace App\Repositories\Interfaces;

interface KeyWordRepositoryInterface
{
    public function getAll();

    public function getDetail($id);

    public function create(array $data);

    public function update($id, array $data);
    
    public function delete($id);
}