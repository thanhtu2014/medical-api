<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\HospitalRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Hospital;

class HospitalRepository implements HospitalRepositoryInterface
{
    public function getHospitalListByType($type)
    {
        return Hospital::where(['type' => $type, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->get();
    }

    public function getDetail($id, $type)
    {
        return Hospital::where(['id' => $id, 'type' => $type, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->first();
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
        Hospital::where('id', $id)->update(['chg' => CHG_DELETE_VALUE]);
    }
}