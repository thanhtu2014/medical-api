<?php

namespace App\Repositories\Interfaces;

interface KeyWordRepositoryInterface
{
    public function getListByType($type);

    public function getDetail($id, $type);

    public function create(array $data);

    public function update($id, array $data);
    
    public function delete($id);
}