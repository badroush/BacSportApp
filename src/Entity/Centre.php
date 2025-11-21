<?php

namespace App\Entity;

use App\Repository\CentreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CentreRepository::class)]
class Centre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; // idcentre devient id

    #[ORM\Column(length: 200)]
    private string $centre;

    #[ORM\Column(length: 100)]
    private string $chefcentre;

    #[ORM\Column(length: 255)]
    private string $description;

    // === Getters & Setters ===

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCentre(): string
    {
        return $this->centre;
    }

    public function setCentre(string $centre): self
    {
        $this->centre = $centre;

        return $this;
    }

    public function getChefcentre(): string
    {
        return $this->chefcentre;
    }

    public function setChefcentre(string $chefcentre): self
    {
        $this->chefcentre = $chefcentre;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString(): string
    {
        return $this->centre;
    }
}
