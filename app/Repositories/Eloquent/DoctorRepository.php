<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\DoctorRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\People;

class DoctorRepository implements DoctorRepositoryInterface
{
    public function getAll()
    {
        return People::where(['type' => HOSPITAL_OR_DOCTOR_KEY_VALUE, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->get();
    }

    public function getDetail($id)
    {
        return People::where(['id' => $id, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->first();
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
        People::where('id', $id)->update(['chg' => CHG_DELETE_VALUE]);
    }
}