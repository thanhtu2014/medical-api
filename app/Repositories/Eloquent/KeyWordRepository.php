<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\KeyWordRepositoryInterface;
use App\Models\KeyWord;

class KeyWordRepository implements KeyWordRepositoryInterface
{
    public function getAll($type)
    {
        return KeyWord::where(['type', $type])->get();
    }

    public function getDetail($id)
    {
        return KeyWord::where('id', $id)->first();
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
        KeyWord::destroy($id);
    }
}