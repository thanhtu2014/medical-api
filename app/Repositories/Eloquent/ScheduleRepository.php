<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function getAll()
    {
        return Schedule::where(['user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE] )->get();
    }

    public function getDetail($id)
    {
        return Schedule::where(['id' => $id, 'user' => Auth::user()->id, 'chg' => CHG_VALID_VALUE])->first();
    }

    public function create(array $scheduleDetails)
    {
        return Schedule::create($scheduleDetails);
    }

    public function update($id, array $newData)
    {
        return Schedule::whereId($id)->update($newData);
    }

    public function delete($id)
    {
        Schedule::where('id', $id)->update(['chg' => CHG_DELETE_VALUE]);
    }
}