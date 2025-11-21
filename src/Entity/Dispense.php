<?php

namespace App\Entity;

use App\Repository\DispenseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DispenseRepository::class)]
class Dispense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'dispenses')]
    #[ORM\JoinColumn(name: 'eleve_cin', referencedColumnName: 'cin', nullable: false)]
    private ?Eleve $eleve = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): static
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }
}
