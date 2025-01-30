<?php

namespace App\Application\Command\AddPosts;

use App\Domain\Model\Post\Post;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;
use Throwable;

#[AsMessageHandler]
readonly class AddPostsHandler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(AddPostsCommand $command): void
    {
        try {
            $user = $this->userRepository->findById($command->getUserId());

            $newPost = new Post(
                Uuid::v1(),
                $command->getTitle(),
                $command->getContent(),
                $user,
                new DateTimeImmutable()
            );

            $this->postRepository->save($newPost);
        } catch (Throwable $exception) {
            throw new $exception;
        }
    }
}