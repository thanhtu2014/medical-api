<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\HospitalRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Hospital;

class HospitalRepository extends BaseRepository implements HospitalRepositoryInterface
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
    public function __construct(Hospital $model)
    {
        $this->model = $model;
    }
}