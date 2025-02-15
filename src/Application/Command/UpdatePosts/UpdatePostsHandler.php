<?php

namespace App\Application\Command\UpdatePosts;

use App\Domain\Repository\PostRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
readonly class UpdatePostsHandler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(UpdatePostsCommand $command): void
    {
        try {
            $post = $this->postRepository->findById($command->getId());

            $post->setTitle($command->getTitle());
            $post->setContent($command->getContent());

            $this->postRepository->save($post);
        } catch (Throwable $exception) {
            throw new $exception;
        }
    }
}