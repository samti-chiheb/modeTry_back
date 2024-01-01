<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    // Les types de photos disponibles
    const TYPE_PROFILE = 'profile';
    const TYPE_TRYON_PROFILE = 'tryon_profile';
    const TYPE_CLOTHING_ITEM = 'clothing_item';
    const TYPE_OTHER = 'other';

    // Les états de visibilité disponibles
    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_PRIVATE = 'private';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    // Utilisation de columnDefinition pour le type ENUM
    #[ORM\Column(type: "string", columnDefinition: "ENUM('profile', 'tryon_profile', 'clothing_item', 'other')")]
    private ?string $type = null;

    // Utilisation de columnDefinition pour le type ENUM
    #[ORM\Column(type: "string", columnDefinition: "ENUM('public', 'private')")]
    private ?string $visibility = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if (!in_array($type, self::getTypes())) {
            throw new \InvalidArgumentException("Invalid type");
        }
        $this->type = $type;
        return $this;
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_PROFILE,
            self::TYPE_TRYON_PROFILE,
            self::TYPE_CLOTHING_ITEM,
            self::TYPE_OTHER
        ];
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): self
    {
        if (!in_array($visibility, self::getVisibilities())) {
            throw new \InvalidArgumentException("Invalid visibility");
        }
        $this->visibility = $visibility;
        return $this;
    }

    public static function getVisibilities(): array
    {
        return [
            self::VISIBILITY_PUBLIC,
            self::VISIBILITY_PRIVATE
        ];
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
