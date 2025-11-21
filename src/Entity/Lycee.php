<?php

namespace App\Entity;

use App\Repository\LyceeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: LyceeRepository::class)]
class Lycee
{
      #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $nom;

    #[ORM\ManyToOne(targetEntity: Centre::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Centre $centre;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateEpreuve = null;

    #[ORM\ManyToOne(targetEntity: Etablissement::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Etablissement $etablissement;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $heureEpreuve = null;

    // Getters & Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getCentre(): Centre
    {
        return $this->centre;
    }

    public function setCentre(Centre $centre): self
    {
        $this->centre = $centre;
        return $this;
    }

    public function getDateEpreuve(): ?\DateTimeInterface
    {
        return $this->dateEpreuve;
    }

    public function setDateEpreuve(?\DateTimeInterface $dateEpreuve): self
    {
        $this->dateEpreuve = $dateEpreuve;
        return $this;
    }

    public function getEtablissement(): Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getHeureEpreuve(): ?\DateTimeInterface
    {
        return $this->heureEpreuve;
    }

    public function setHeureEpreuve(?\DateTimeInterface $heureEpreuve): self
    {
        $this->heureEpreuve = $heureEpreuve;
        return $this;
    }

    public function __toString(): string
{
    return $this->getNom();

}
}
