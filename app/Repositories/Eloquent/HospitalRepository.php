<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\HospitalRepositoryInterface;
use App\Models\Hospital;

class HospitalRepository implements HospitalRepositoryInterface
{
    public function getAll()
    {
        return Hospital::all();
    }

    public function getDetail($id)
    {
        return Hospital::where('id', $id)->first();
    }

    public function create(array $hospitalDetails)
    {
        return Hospital::create($hospitalDetails);
    }

    public function update($id, array $newData)
    {
        return Hospital::whereId($id)->update($newData);
    }

    public function delete($id)
    {
        Hospital::destroy($id);
    }
}