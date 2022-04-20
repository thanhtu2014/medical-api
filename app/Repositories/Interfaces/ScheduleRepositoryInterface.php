<?php

namespace App\Repositories\Interfaces;

interface ScheduleRepositoryInterface
{
    public function getAll();

    public function getDetail($id);

    public function create(array $scheduleDetails);

    public function update($id, array $newData);
    
    public function delete($id);
}