<?php

namespace App\Repositories\Interfaces;

interface UserInterface
{
    /**
     * Get user information
     *
     * @param $email
     * @return string
     *
     */
    public function getUserInformationByEmail($email);
}
