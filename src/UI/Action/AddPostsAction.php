<?php

namespace App\UI\Action;

use App\Application\Command\AddPosts\AddPostsCommand;
use App\Application\Enum\Error;
use App\UI\Request\AddPostsRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class AddPostsAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] AddPostsRequest $request
    ): JsonResponse {
        try {
            $this->messageBus->dispatch(
                new AddPostsCommand(
                    $request->title,
                    $request->postContent,
                    $request->userId,
                )
            );

            return new JsonResponse(
                status: Response::HTTP_ACCEPTED
            );

        } catch (Throwable $throwable) {
            $this->logger->error(
                Error::ADD_POST_ACTION_ERROR->value,
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