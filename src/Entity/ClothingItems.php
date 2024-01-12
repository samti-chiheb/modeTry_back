<?php

namespace App\Entity;

use App\Repository\ClothingItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClothingItemsRepository::class)]
class ClothingItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $sexe = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Photo $photo = null;

    #[ORM\ManyToMany(targetEntity: TryonResult::class, mappedBy: 'clothingItems')]
    private Collection $tryonResults;

    public function __construct()
    {
        $this->tryonResults = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(?Photo $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, TryonResult>
     */
    public function getTryonResults(): Collection
    {
        return $this->tryonResults;
    }

    public function addTryonResult(TryonResult $tryonResult): static
    {
        if (!$this->tryonResults->contains($tryonResult)) {
            $this->tryonResults->add($tryonResult);
            $tryonResult->addClothingItem($this);
        }

        return $this;
    }

    public function removeTryonResult(TryonResult $tryonResult): static
    {
        if ($this->tryonResults->removeElement($tryonResult)) {
            $tryonResult->removeClothingItem($this);
        }

        return $this;
    }
}
