<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $etablissement;

    #[ORM\Column(length: 200)]
    private string $adresse;

    #[ORM\Column(length: 20)]
    private string $telephone;

    // === Getters & Setters ===

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtablissement(): string
    {
        return $this->etablissement;
    }

    public function setEtablissement(string $etablissement): self
    {
        $this->etablissement = $etablissement;
        return $this;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }
    public function __toString(): string
{
    return $this->getEtablissement(); // ou une autre propriété qui identifie clairement l’établissement
}
}
