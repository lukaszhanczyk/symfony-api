<?php

namespace App\Application\Command\DeletePost;

use Symfony\Component\Uid\Uuid;

readonly class DeletePostsCommand
{
    public function __construct(
        private Uuid $id,
    ) {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}