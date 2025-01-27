<?php

namespace App\UI\Action;

use App\Application\Query\GetPosts\GetPostsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class GetPostsAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $posts = $this->messageBus->dispatch(
            new GetPostsQuery()
        );

        return new JsonResponse(
            $posts->last(HandledStamp::class)->getResult(),
            Response::HTTP_OK
        );
    }
}