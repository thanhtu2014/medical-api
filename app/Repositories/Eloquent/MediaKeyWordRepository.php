<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\MediaKeyWordRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\MediaKeyWord;

class MediaKeyWordRepository implements MediaKeyWordRepositoryInterface
{
    public function store(array $data)
    {
        return MediaKeyWord::create($data);
    }

    public function delete($id)
    {
        MediaKeyWord::where('id', $id)->update(['chg' => CHG_DELETE_VALUE]);
    }
}