<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\KeywordRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\KeyWord;

class KeywordRepository implements KeywordRepositoryInterface
{
    public function getListByType($type)
    {
        return KeyWord::where(['type' => $type, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->get();
    }

    public function getDetail($id, $type)
    {
        return KeyWord::where(['id' => $id, 'type' => $type, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->first();
    }

    public function create(array $data)
    {
        return KeyWord::create($data);
    }

    public function update($id, array $data)
    {
        return KeyWord::whereId($id)->update($data);
    }

    public function delete($id)
    {
        KeyWord::where('id', $id)->update(['chg' => CHG_DELETE_VALUE]);
    }
}