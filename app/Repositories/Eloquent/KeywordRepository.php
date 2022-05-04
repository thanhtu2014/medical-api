<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\KeywordRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Keyword;

class KeywordRepository extends BaseRepository implements KeywordRepositoryInterface 
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
    public function __construct(Keyword $model)
    {
        $this->model = $model;
    }
}