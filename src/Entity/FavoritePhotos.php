<?php

namespace App\Entity;

use App\Repository\FavoritePhotosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritePhotosRepository::class)]
class FavoritePhotos
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
    private ?UserPhotos $photoId = null;

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

    public function getPhotoId(): ?UserPhotos
    {
        return $this->photoId;
    }

    public function setPhotoId(?UserPhotos $photoId): static
    {
        $this->photoId = $photoId;

        return $this;
    }
}
