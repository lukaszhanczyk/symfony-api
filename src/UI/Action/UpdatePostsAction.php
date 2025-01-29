<?php

namespace App\UI\Action;

use App\Application\Command\UpdatePosts\UpdatePostsCommand;
use App\UI\Request\UpdatePostsRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;
use Throwable;

class UpdatePostsAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] UpdatePostsRequest $request
    ): JsonResponse {
        try {
            $this->messageBus->dispatch(
                new UpdatePostsCommand(
                    Uuid::fromString($request->id),
                    $request->title,
                    $request->postContent,
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