<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
#[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Lycee::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Lycee $lycee;

    #[ORM\ManyToOne(targetEntity: NomClasse::class)]
#[ORM\JoinColumn(nullable: false)]
private ?NomClasse $nomClasse = null;

#[ORM\Column(type: 'integer',nullable: true)]
    private ?int $num = null;
    // Getters & Setters
function getNum(): ?int
    {
        return $this->num;
}
function setNum(int $num): self
    {
        $this->num = $num;
        return $this;
}
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClasse(): ?NomClasse
    {
        return $this->nomClasse;
    }

    public function setNomClasse(?NomClasse $nomClasse): self
    {
        $this->nomClasse = $nomClasse;
        return $this;
    }

    public function getLycee(): Lycee
    {
        return $this->lycee;
    }

    public function setLycee(Lycee $lycee): self
    {
        $this->lycee = $lycee;
        return $this;
    }

   public function __toString(): string
{
    return $this->nomClasse ? $this->nomClasse->getNom() : '';
}
}