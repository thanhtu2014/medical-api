<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AccountRepository implements AccountRepositoryInterface
{
    public function getDetail($id)
    {
        return User::where(['id' => $id, 'chg' => CHG_VALID_VALUE])->first();
    }
    
    public function update($id, array $data)
    {
        return User::whereId($id)->update($data);
    }

    public function delete($id)
    {
        User::where('id', $id)->update(['chg' => CHG_DELETE_VALUE]);
    }
}