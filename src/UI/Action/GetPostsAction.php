<?php

namespace App\UI\Action;

use App\Application\Enum\Error;
use App\Application\Query\GetPosts\GetPostsQuery;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

class GetPostsAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $posts = $this->messageBus->dispatch(
                new GetPostsQuery()
            );

            return new JsonResponse(
                $posts->last(HandledStamp::class)->getResult(),
                Response::HTTP_OK
            );
        } catch (Throwable $throwable) {
            $this->logger->error(
                Error::GET_POST_ACTION_ERROR->value,
                [
                    'message' => $throwable->getMessage(),
                    'code' => $throwable->getCode(),
                ]
            );

            return new JsonResponse(
                ['error' => 'HTTP_INTERNAL_SERVER_ERROR'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}