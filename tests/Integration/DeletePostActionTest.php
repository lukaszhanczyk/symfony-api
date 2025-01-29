<?php

namespace App\Tests\Integration;

use App\Domain\Model\Post\Post;
use App\Domain\Repository\PostRepositoryInterface;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class DeletePostActionTest extends WebTestCase
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
        $repo = $this->container->get(PostRepositoryInterface::class);

        $id = Uuid::v1();
        $post = new Post(
            $id,
            'test',
            'test',
            new DateTimeImmutable()
        );

        $repo->save($post);

        $this->client->request(
            method: 'DELETE',
            uri: '/posts',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'id' => $id->toBase32(),
                ]
            )
        );

        $response = $this->client->getResponse();

        /** @var Post $post */
        $posts = $repo->findAll();

        $this->assertEmpty($posts);
        $this->assertEquals(Response::HTTP_ACCEPTED, $response->getStatusCode());
    }

    public function testInvokeError(): void
    {
        $mockRepo = $this->createMock(PostRepositoryInterface::class);
        $mockRepo->method('findById')
            ->willThrowException(new \Exception());
        $this->container->set(PostRepositoryInterface::class, $mockRepo);


        $this->client->request(
            method: 'DELETE',
            uri: '/posts',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'id' => 'test',
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
            method: 'DELETE',
            uri: '/posts',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(
                [
                    'id' => '',
                ]
            )
        );
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(
            json_encode(
                [
                    'errors' => [
                        'id' => 'This value should not be blank.'
                    ]
                ]
            ),
            $response->getContent()
        );
    }
}