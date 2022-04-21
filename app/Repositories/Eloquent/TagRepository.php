<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
        public function getAll()
        {
            return Tag::where(['user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE] )->get();
        }
    
        public function getDetail($id)
        {
            return Tag::where(['id' => $id, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->first();
        }
    
        public function create(array $data)
        {
            return Tag::create($data);
        }
    
        public function update($id, array $data)
        {
            return Tag::whereId($id)->update($data);
        }
    
        public function delete($id)
        {
            Tag::where('id', $id)->update(['chg' => CHG_DELETE_VALUE]);
        }
}