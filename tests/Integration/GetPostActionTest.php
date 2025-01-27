<?php

namespace App\Tests\Integration;

use App\Domain\Model\Post\Post;
use App\Domain\Repository\PostRepositoryInterface;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class GetPostActionTest extends WebTestCase
{
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

}