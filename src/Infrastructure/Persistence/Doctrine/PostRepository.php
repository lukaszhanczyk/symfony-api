<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Model\Post\Post;
use App\Domain\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Uid\Uuid;

class PostRepository implements PostRepositoryInterface
{
    private ObjectRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    ){
        $this->repository = $entityManager->getRepository(Post::class);
    }

    public function findById(Uuid $id): ?Post
    {
        return $this->repository->find($id);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findByTitle(string $title): ?Post
    {
        return $this->repository->findOneBy(['title' => $title]);
    }

    public function save(Post $post): void
    {
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function delete(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    public function findRecent(int $limit): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}