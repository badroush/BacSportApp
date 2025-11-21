<?php

namespace App\Entity;

use App\Repository\ActionControleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionControleRepository::class)]
class ActionControle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $actions = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?bool $masque = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActions(): ?string
    {
        return $this->actions;
    }

    public function setActions(string $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function isMasque(): ?bool
    {
        return $this->masque;
    }

    public function setMasque(bool $masque): static
    {
        $this->masque = $masque;

        return $this;
    }
}
