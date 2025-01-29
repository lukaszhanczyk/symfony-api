<?php

namespace App\Application\Command\AddPosts;

use App\Domain\Model\Post\Post;
use App\Domain\Repository\PostRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;
use Throwable;

#[AsMessageHandler]
readonly class AddPostsHandler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(AddPostsCommand $command): void
    {
        try {
            $newPost = new Post(
                Uuid::v1(),
                $command->getTitle(),
                $command->getContent(),
                new DateTimeImmutable()
            );

            $this->postRepository->save($newPost);
        } catch (Throwable $exception) {
            throw new $exception;
        }
    }
}