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

class GetPostActionTest extends WebTestCase
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

        $post = new Post(
            'test',
            'test',
            new DateTimeImmutable()
        );

        $post->setId(Uuid::v1());

        $repo->save($post);

        $this->client->request('GET', '/posts');
        $response = $this->client->getResponse();
        $repo->delete($post);

        $this->assertEquals(json_encode([$post->jsonSerialize()]), $response->getContent());
    }

    public function testInvokeError(): void
    {
        $mockRepo = $this->createMock(PostRepositoryInterface::class);
        $mockRepo->method('findAll')
            ->willThrowException(new \Exception());
        $this->container->set(PostRepositoryInterface::class, $mockRepo);

        $this->client->request('GET', '/posts');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'HTTP_INTERNAL_SERVER_ERROR']), $response->getContent());
    }

}