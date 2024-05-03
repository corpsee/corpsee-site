<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PullRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PullRequestRepository::class)]
#[UniqueEntity(
    fields: ['platform', 'platform_id'],
    message: 'This platform_id is already in use on that platform.',
    errorPath: 'platform_id',
)]
class PullRequest
{
    public const PLATFORM_GITHUB = 'Github';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(unique: true)]
    private int $id;

    #[ORM\Column(length: 100)]
    private string $platform;

    #[ORM\Column]
    private string $repository;

    #[ORM\Column(length: 100)]
    private string $platformId;

    #[ORM\Column(type: Types::TEXT)]
    private string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    #[ORM\Column(length: 100)]
    private string $status;

    #[ORM\Column(nullable: true)]
    private ?int $commits = null;

    #[ORM\Column(nullable: true)]
    private ?int $additions = null;

    #[ORM\Column(nullable: true)]
    private ?int $deletions = null;

    #[ORM\Column(nullable: true)]
    private ?int $files = null;

    #[ORM\Column]
    private int $createdYear;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        ?\DateTimeInterface $createdAt = null,
        ?\DateTimeInterface $updatedAt = null,
    ) {
        $currentDateTime = new \DateTimeImmutable();

        if (!$createdAt) {
            $this->createdAt = $currentDateTime;
        } else {
            $this->createdAt = $createdAt;
        }

        if (!$updatedAt) {
            $this->updatedAt = $currentDateTime;
        } else {
            $this->updatedAt = $updatedAt;
        }

        $this->createdYear = (int)$this->createdAt->format('Y');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): static
    {
        $this->platform = $platform;

        return $this;
    }

    public function getRepository(): string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function getPlatformId(): string
    {
        return $this->platformId;
    }

    public function setPlatformId(string $platformId): static
    {
        $this->platformId = $platformId;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCommits(): ?int
    {
        return $this->commits;
    }

    public function setCommits(?int $commits): static
    {
        $this->commits = $commits;

        return $this;
    }

    public function getAdditions(): ?int
    {
        return $this->additions;
    }

    public function setAdditions(?int $additions): static
    {
        $this->additions = $additions;

        return $this;
    }

    public function getDeletions(): ?int
    {
        return $this->deletions;
    }

    public function setDeletions(?int $deletions): static
    {
        $this->deletions = $deletions;

        return $this;
    }

    public function getFiles(): ?int
    {
        return $this->files;
    }

    public function setFiles(?int $files): static
    {
        $this->files = $files;

        return $this;
    }

    public function getCreatedYear(): int
    {
        return $this->createdYear;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
