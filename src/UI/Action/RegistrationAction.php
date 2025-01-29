<?php

namespace App\UI\Action;

use App\Application\Command\Registration\RegistrationCommand;
use App\UI\Request\RegistrationRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class RegistrationAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] RegistrationRequest $request
    ): JsonResponse {
        try {
            $this->messageBus->dispatch(
                new RegistrationCommand(
                    $request->email,
                    $request->password,
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