<?php

namespace App\Entity;

use App\Repository\VirtualTryOnsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VirtualTryOnsRepository::class)]
class VirtualTryOns
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $userId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ClothingItems $clothingItemId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserPhotos $photoId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?Users
    {
        return $this->userId;
    }

    public function setUserId(?Users $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getClothingItemId(): ?ClothingItems
    {
        return $this->clothingItemId;
    }

    public function setClothingItemId(?ClothingItems $clothingItemId): static
    {
        $this->clothingItemId = $clothingItemId;

        return $this;
    }

    public function getPhotoId(): ?UserPhotos
    {
        return $this->photoId;
    }

    public function setPhotoId(?UserPhotos $photoId): static
    {
        $this->photoId = $photoId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
