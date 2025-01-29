<?php

namespace App\Application\Command\DeletePost;

use App\Application\Command\AddPosts\AddPostsCommand;
use App\Domain\Model\Post\Post;
use App\Domain\Repository\PostRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;
use Throwable;

#[AsMessageHandler]
readonly class DeletePostsHandler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(DeletePostsCommand $command): void
    {
        try {
            $post = $this->postRepository->findById($command->getId());
            $this->postRepository->delete($post);
        } catch (Throwable $exception) {
            throw new $exception;
        }
    }
}