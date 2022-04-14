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

    public function create(array $data)
    {
        return Hospital::create($data);
    }

    public function update($id, array $data)
    {
        return Hospital::whereId($id)->update($data);
    }

    public function delete($id)
    {
        Hospital::destroy($id);
    }
}