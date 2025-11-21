<?php

namespace App\Entity;

use App\Repository\NomClasseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NomClasseRepository::class)]
class NomClasse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
    public function __toString(): string
{
    return $this->nom ?? ''; // ou 'nom', ou ce que tu veux afficher comme texte
}
}
