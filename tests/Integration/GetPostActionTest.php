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
        $repoPost = $this->container->get(PostRepositoryInterface::class);
        $repoUser = $this->container->get(UserRepositoryInterface::class);

        $newUser = new User();
        $newUser->setEmail('test@test.com');
        $newUser->setRoles(['ROLE_USER']);
        $newUser->setPassword('test');

        $repoUser->save($newUser);

        $user = $repoUser->findAll()[0];

        $post = new Post(
            Uuid::v1(),
            'test',
            'test',
            $user,
            new DateTimeImmutable()
        );

        $repoPost->save($post);

        $this->client->request('GET', '/posts');
        $response = $this->client->getResponse();
        $repoPost->delete($post);
        $repoUser->delete($user);

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