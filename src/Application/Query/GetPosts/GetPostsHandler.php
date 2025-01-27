<?php

namespace App\Application\Query\GetPosts;

use App\Domain\Repository\PostRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
readonly class GetPostsHandler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(GetPostsQuery $query): array
    {
        try {
            return $this->postRepository->findAll();
        } catch (Throwable $exception) {
            throw new $exception;
        }
    }
}