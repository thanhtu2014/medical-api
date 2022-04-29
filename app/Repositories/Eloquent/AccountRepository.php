<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Http\Request;

class AccountRepository extends BaseRepository implements AccountRepositoryInterface {
        /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function Search(Request $request)
    {
        $param = $request->q;
        // dd($request->all());
            
        $account = User::where('name', 'like', "%".$param."%")
            ->orwhere('email', 'like', "%".$param."%")
            ->get();

        return $account;
    }

}