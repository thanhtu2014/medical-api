<?php

namespace App\Repositories\Interfaces;
use App\Repositories\EloquentRepositoryInterface;

interface MediaRepositoryInterface extends EloquentRepositoryInterface
{
    public function importFile($file);
}