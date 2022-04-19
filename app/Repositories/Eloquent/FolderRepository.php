<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Models\Folder;

class FolderRepository implements FolderRepositoryInterface
{
    
    public function getAll()
    {
        return Folder::all();
    }

    public function getDetail($id)
    {
        return Folder::where('id', $id)->first();
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
        Folder::destroy($id);
    }

    public function getlist($folderId)
    {
        return Folder::where('pid', '=', $folderId)->get();
    
    }

}