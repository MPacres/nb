<?php

namespace App\Repository;

use App\Entity\Users;

interface UsersRepositoryInterface
{
    public function save(Users $user): void;
}