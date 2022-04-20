<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderRepository implements FolderRepositoryInterface
{
    public function getFolderListByUser()
    {
        return Folder::where(['user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->get();
    }

    public function getDetail($id)
    {
        return Folder::where(['id' => $id, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->first();
    }

    public function create(array $data)
    {
        return Folder::create($data);
    }

    public function update($id, array $data)
    {
        return Folder::whereId($id)->update($data);
    }

    public function delete($id)
    {
        Folder::where('id', $id)->update(['chg' => CHG_DELETE_VALUE]);
    }

    public function getlist($folderId)
    {
        return Folder::where('pid', '=', $folderId)->get();
    
    }

}