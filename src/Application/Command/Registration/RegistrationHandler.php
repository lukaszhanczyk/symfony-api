<?php

namespace App\Application\Command\Registration;

use App\Domain\Model\User\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Throwable;

#[AsMessageHandler]
readonly class RegistrationHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(RegistrationCommand $command): void
    {
        try {
            $newUser = new User();
            $newUser->setEmail($command->getEmail());
            $newUser->setRoles(['ROLE_USER']);
            $newUser->setPassword(
                $this->passwordHasher->hashPassword(
                    $newUser,
                    $command->getPassword()
                )
            );

            $this->userRepository->save($newUser);
        } catch (Throwable $exception) {
            throw new $exception;
        }
    }
}