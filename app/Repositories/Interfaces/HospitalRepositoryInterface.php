<?php

namespace App\Repositories\Interfaces;

interface HospitalRepositoryInterface
{
    public function getAll();

    public function getDetail($id);

    public function create(array $hospitalDetails);

    public function update($id, array $newData);
    
    public function delete($id);
}