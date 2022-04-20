<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\PeopleRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\People;

class PeopleRepository implements PeopleRepositoryInterface
{
    public function getListByType($type)
    {
        return People::where(['type' => $type, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->get();
    }

    public function getDetail($id, $type)
    {
        return People::where(['id' => $id, 'type' => $type, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->first();
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