<?php

namespace App\Tests\Integration;

use App\Domain\Model\Post\Post;
use App\Domain\Model\User\User;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class UpdatePostActionTest extends WebTestCase
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
        $repoPost = $this->container->get(PostRepositoryInterface::class);
        $repoUser = $this->container->get(UserRepositoryInterface::class);

        $newUser = new User();
        $newUser->setEmail('test@test.com');
        $newUser->setRoles(['ROLE_USER']);
        $newUser->setPassword('test');

        $repoUser->save($newUser);

        $user = $repoUser->findAll()[0];

        $id = Uuid::v1();
        $post = new Post(
            $id,
            'test',
            'test',
            $user,
            new DateTimeImmutable()
        );

        $repoPost->save($post);

        $this->client->request(
            method: 'PUT',
            uri: '/posts',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'id' => $id->toBase32(),
                    'title' => 'test1',
                    'postContent' => 'test1',
                ]
            )
        );

        $response = $this->client->getResponse();

        /** @var Post $post */
        $post = $repoPost->findAll()[0];
        $repoPost->delete($post);
        $repoUser->delete($user);

        $this->assertEquals('test1', $post->getTitle());
        $this->assertEquals('test1', $post->getContent());
        $this->assertEquals(Response::HTTP_ACCEPTED, $response->getStatusCode());
    }

    public function testInvokeError(): void
    {
        $mockRepo = $this->createMock(PostRepositoryInterface::class);
        $mockRepo->method('findById')
            ->willThrowException(new \Exception());
        $this->container->set(PostRepositoryInterface::class, $mockRepo);


        $this->client->request(
            method: 'PUT',
            uri: '/posts',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'id' => 'test',
                    'title' => 'test1',
                    'postContent' => 'test1',
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
            uri: '/posts',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'id' => 'test',
                    'title' => 'test',
                    'postContent' => '',
                ]
            )
        );
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(
            json_encode(
                [
                    'errors' => [
                        'postContent' => 'This value should not be blank.',
                        'userId' => 'This value should not be blank.',
                    ]
                ]
            ),
            $response->getContent()
        );
    }
}