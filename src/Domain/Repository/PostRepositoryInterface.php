<?php

namespace App\Domain\Model\Repository;

use App\Domain\Model\Post\Post;
use Symfony\Component\Uid\Uuid;

interface PostRepositoryInterface
{
    /**
     * @param Uuid $id
     * @return Post|null
     */
    public function findById(Uuid $id): ?Post;

    /**
     * @param Post $post
     */
    public function save(Post $post): void;

    /**
     * @param Post $post
     */
    public function delete(Post $post): void;

    /**
     * @return Post[]
     */
    public function findAll(): array;
}