<?php

namespace App\Repositories\Interfaces;

interface PeopleRepositoryInterface
{
    public function getPeopleListByType($type);

    public function getDetail($id, $type);

    public function create(array $data);

    public function update($id, array $data);
    
    public function delete($id);
}