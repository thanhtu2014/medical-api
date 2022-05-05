<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\MediaKeywordRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\MediaKeyword;

class MediaKeywordRepository extends BaseRepository implements MediaKeywordRepositoryInterface
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
    public function __construct(MediaKeyword $model)
    {
        $this->model = $model;
    }
}