<?php

namespace App\UI\Action;

use App\Application\Command\DeletePosts\DeletePostsCommand;
use App\UI\Request\DeletePostsRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;
use Throwable;

class DeletePostsAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] DeletePostsRequest $request
    ): JsonResponse {
        try {
            $this->messageBus->dispatch(
                new DeletePostsCommand(
                    Uuid::fromString($request->id),
                )
            );

            return new JsonResponse(
                status: Response::HTTP_ACCEPTED
            );
        } catch (Throwable $throwable) {
            return new JsonResponse(
                ['error' => 'HTTP_INTERNAL_SERVER_ERROR'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}