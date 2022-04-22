<?php

namespace App\Repositories\Interfaces;

interface AccountRepositoryInterface
{
    public function getDetail($id);

    public function update($id, array $data);
    
    public function delete($id);
}