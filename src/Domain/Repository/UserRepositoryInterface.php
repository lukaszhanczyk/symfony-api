<?php

namespace App\Domain\Repository;

use App\Domain\Model\User\User;

interface UserRepositoryInterface
{
    /**
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param User $user
     */
    public function delete(User $user): void;

    /**
     * @return User[]
     */
    public function findAll(): array;
}