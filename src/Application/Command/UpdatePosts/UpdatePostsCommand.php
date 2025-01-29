<?php

namespace App\Application\Command\UpdatePosts;

use Symfony\Component\Uid\Uuid;

readonly class UpdatePostsCommand
{
    public function __construct(
        private Uuid $id,
        private string $title,
        private string $content,
    ) {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}