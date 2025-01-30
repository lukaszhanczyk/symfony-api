<?php

namespace App\Tests\Integration;

use App\Domain\Model\User\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;

class RegistrationActionTest extends WebTestCase
{
    private KernelBrowser $client;
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->container = self::getContainer();
    }

    public function testInvoke(): void
    {
        $repo = $this->container->get(UserRepositoryInterface::class);

        $this->client->request(
            method: 'POST',
            uri: '/registration',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'email' => 'test@test.com',
                    'password' => 'test',
                ]
            )
        );

        $response = $this->client->getResponse();

        /** @var User $user */
        $user = $repo->findAll()[0];
        $repo->delete($user);

        $this->assertEquals('test@test.com', $user->getEmail());
        $this->assertEquals(Response::HTTP_ACCEPTED, $response->getStatusCode());
    }

    public function testInvokeError(): void
    {
        $mockRepo = $this->createMock(UserRepositoryInterface::class);
        $mockRepo->method('save')
            ->willThrowException(new \Exception());
        $this->container->set(UserRepositoryInterface::class, $mockRepo);


        $this->client->request(
            method: 'POST',
            uri: '/registration',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'email' => 'test@test.com',
                    'password' => 'test',
                ]
            )
        );
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'HTTP_INTERNAL_SERVER_ERROR']), $response->getContent());
    }

    public function testValidationError(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/registration',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'email' => 'test',
                    'password' => 'test',
                ]
            )
        );
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(
            json_encode(
                [
                    'errors' => [
                        'email' => 'This value is not a valid email address.'
                    ]
                ]
            ),
            $response->getContent()
        );
    }
}