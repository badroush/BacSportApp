<?php

namespace App\Entity;

use App\Repository\BaremeRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Epreuve; // Assurez-vous que c'est bien l'entité epreuve que vous utilisez

#[ORM\Entity(repositoryClass: BaremeRepository::class)]
class Bareme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // IMPORTANT: Assurez-vous que c'est bien 'epreuve::class' si c'est l'entité que vous utilisez partout ailleurs
    // Si votre entité d'épreuve s'appelle 'Epreuve', alors laissez tel quel.
    #[ORM\ManyToOne(targetEntity: Epreuve::class)] // <-- Très probablement epreuve::class ici
    #[ORM\JoinColumn(nullable: false)]
    private Epreuve $epreuve; // <-- Le type de la propriété doit correspondre au targetEntity


    #[ORM\Column(type: 'float')]
    private float $resultat;

    #[ORM\Column(type: 'float')]
    private float $note; // <-- Le type de la propriété doit être 'float'

    #[ORM\Column(type: 'string', length: 1)]
    private string $sex; // Le nom de la propriété est 'sex'

    // Getters & Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    // Le type de retour doit correspondre à la propriété (epreuve)
    public function getEpreuve(): Epreuve
    {
        return $this->epreuve;
    }

    // Le paramètre doit correspondre à la propriété (epreuve)
    public function setEpreuve(?Epreuve $epreuve): static // Utilisez 'static' pour Symfony 6+ si c'est pour chaîner les appels
    {
        $this->epreuve = $epreuve;
        return $this;
    }

    // Correction des types pour getter et setter de 'resultat'
    public function getResultat(): float // <-- Type de retour correct
    {
        return $this->resultat;
    }

    public function setResultat(float $resultat): self // <-- Type de paramètre correct
    {
        $this->resultat = $resultat;
        return $this;
    }

    // Correction des types pour getter et setter de 'note'
    public function getNote(): float // <-- Type de retour correct
    {
        return $this->note;
    }

    public function setNote(float $note): self // <-- Type de paramètre correct
    {
        $this->note = $note;
        return $this;
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;
        return $this;
    }
}
