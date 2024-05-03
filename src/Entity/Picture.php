<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[UniqueEntity('title')]
#[UniqueEntity('image')]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: Types::TEXT, unique: true)]
    private string $title;

    #[ORM\Column(unique: true)]
    private string $image;

    #[ORM\Column]
    private string $imageMin;

    #[ORM\Column]
    private string $imageGray;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private int $drawnYear;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $drawnAt = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'pictures', cascade: ['persist'])]
    private Collection $tags;

    public function __construct(
        ?\DateTimeInterface $createdAt = null,
        ?\DateTimeInterface $updatedAt = null,
    ) {
        $this->tags = new ArrayCollection();

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
    }

    public function getId(): Uuid
    {
        return $this->id;
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

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getImageMin(): string
    {
        return $this->imageMin;
    }

    public function setImageMin(string $imageMin): static
    {
        $this->imageMin = $imageMin;

        return $this;
    }

    public function getImageGray(): string
    {
        return $this->imageGray;
    }

    public function setImageGray(string $imageGray): static
    {
        $this->imageGray = $imageGray;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDrawnAt(): ?\DateTimeImmutable
    {
        return $this->drawnAt;
    }

    public function setDrawnAt(?\DateTimeImmutable $drawnAt): static
    {
        $this->drawnAt = $drawnAt;
        $this->drawnYear = (int)$this->drawnAt->format('Y');

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
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
