<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[UniqueEntity('name')]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Picture::class, mappedBy: 'tags')]
    private Collection $pictures;

    public function __construct(
        ?\DateTimeInterface $createdAt = null,
        ?\DateTimeInterface $updatedAt = null,
    ) {
        $this->pictures = new ArrayCollection();

        $currentDateTime = new \DateTimeImmutable();

        $this->createdAt = $createdAt;
        if (!$createdAt) {
            $this->createdAt = $currentDateTime;
        }

        $this->updatedAt = $updatedAt;
        if (!$updatedAt) {
            $this->updatedAt = $currentDateTime;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->addTag($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            $picture->removeTag($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
