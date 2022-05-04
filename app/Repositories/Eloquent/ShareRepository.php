<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ShareRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Share;

class ShareRepository extends BaseRepository implements ShareRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Share $model)
    {
        $this->model = $model;
    }
}