<?php

namespace App\Application\Query\GetPosts;

use App\Domain\Repository\PostRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetPostsHandler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {
    }

    public function __invoke(GetPostsQuery $query): array
    {
       return $this->postRepository->findAll();
    }
}