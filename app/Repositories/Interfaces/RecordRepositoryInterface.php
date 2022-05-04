<?php

namespace App\Repositories\Interfaces;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Http\Request;

interface RecordRepositoryInterface extends EloquentRepositoryInterface
{
    public function Search(Request $request);
}