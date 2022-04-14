<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\PeopleRepositoryInterface;
use App\Models\People;

class PeopleRepository implements PeopleRepositoryInterface
{
    public function getPeopleListByType($type)
    {
        return People::where('type', $type)->get();
    }

    public function getDetail($id, $type)
    {
        return People::where(['id' => $id, 'type' => $type])->first();
    }

    public function create(array $data)
    {
        return People::create($data);
    }

    public function update($id, array $data)
    {
        return People::whereId($id)->update($data);
    }

    public function delete($id)
    {
        People::destroy($id);
    }
}