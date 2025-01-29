<?php

namespace App\UI\Action;

use App\Application\Command\AddPosts\AddPostsCommand;
use App\UI\Request\AddPostsRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

class AddPostsAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
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