<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PullRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PullRequestRepository::class)]
#[ORM\UniqueConstraint(name: 'pull_request_platform_repository_external_id_uidx', fields: ['platform', 'repository', 'externalId'])]
#[UniqueEntity(
    fields: ['platform', 'repository', 'externalId'],
    message: 'This externalId is already in use on that platform and repository.',
    errorPath: 'externalId',
)]
class PullRequest
{
    public const PLATFORM_GITHUB = 'Github';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(length: 100)]
    private string $platform;

    #[ORM\Column]
    private string $repository;

    #[ORM\Column(length: 100)]
    private string $externalId;

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

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $externalCreatedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $updatedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

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

    public function getId(): Uuid
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

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): static
    {
        $this->externalId = $externalId;

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

    public function setExternalCreatedAt(\DateTimeInterface $externalCreatedAt): static
    {
        $this->externalCreatedAt = $externalCreatedAt;

        return $this;
    }

    public function getExternalCreatedAt(): \DateTimeInterface
    {
        return $this->externalCreatedAt;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
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

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
