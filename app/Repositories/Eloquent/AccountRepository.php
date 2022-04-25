<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use App\Models\User;

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
}