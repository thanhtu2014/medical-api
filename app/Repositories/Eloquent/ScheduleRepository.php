<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use App\Models\Schedule;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function getAll()
    {
        return Schedule::all('title', 'date', 'hospital', 'people');
    }

    public function getDetail($id)
    {
        return Schedule::where('id', $id)->first();
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
        Schedule::destroy($id);
    }
}