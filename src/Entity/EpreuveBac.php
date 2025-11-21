<?php

namespace App\Entity;

use App\Repository\EpreuveBacRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpreuveBacRepository::class)]
class EpreuveBac
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Epreuve::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Epreuve $epreuve = null;

    #[ORM\ManyToOne(targetEntity: Eleve::class)]
#[ORM\JoinColumn(name: "eleve_cin", referencedColumnName: "cin", nullable: false)]
private ?Eleve $eleve = null;

    // === Getters & Setters ===

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEpreuve(): ?Epreuve
    {
        return $this->epreuve;
    }

    public function setEpreuve(?Epreuve $epreuve): self
    {
        $this->epreuve = $epreuve;
        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf("Épreuve %s - Élève %s", $this->epreuve?->getId() ?? '', $this->eleve?->getCin() ?? '');
    }
}