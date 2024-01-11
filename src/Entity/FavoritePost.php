<?php

namespace App\Entity;

use App\Repository\FavoritePhotosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritePhotosRepository::class)]
class FavoritePost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserPhotos $photo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPhoto(): ?UserPhotos
    {
        return $this->photo;
    }

    public function setPhoto(?UserPhotos $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
}
