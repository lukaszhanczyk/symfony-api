<?php

declare(strict_types=1);

namespace App\Domain\Model\Post;

use App\Domain\Model\User\User;
use DateTimeInterface;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'post')]
class Post implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'users')]
    private User $user;


    public function __construct(
        Uuid $id,
        string $title,
        string $content,
        User $user,
        \DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->user = $user;
        $this->createdAt = $createdAt;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $uuid): void
    {
        $this->id = $uuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->toBase32(),
            'title' => $this->title,
            'content' => $this->content,
            'createdAt' => $this->createdAt,
            'user' => $this->user->getEmail(),
        ];
    }
}