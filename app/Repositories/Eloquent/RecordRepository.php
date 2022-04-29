<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\RecordRepositoryInterface;
use App\Models\Record;

class RecordRepository extends BaseRepository implements RecordRepositoryInterface
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
    public function __construct(Record $model)
    {
        $this->model = $model;
    }
}