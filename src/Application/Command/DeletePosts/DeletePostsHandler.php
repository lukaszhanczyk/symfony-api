<?php

namespace App\Application\Command\DeletePosts;

use App\Domain\Repository\PostRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
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