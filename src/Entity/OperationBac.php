<?php

namespace App\Entity;

use App\Repository\OperationBacRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationBacRepository::class)]
class OperationBac
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Eleve::class)]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'cin')]
    private ?Eleve $eleve = null; // Initialiser à null car nullable = true

    // IMPORTANT: Assurez-vous que c'est bien 'EpreuveBac::class' si c'est l'entité que vous utilisez.
    // Si votre entité d'épreuve s'appelle 'Epreuve', alors laissez tel quel et assurez l'import.
    #[ORM\ManyToOne(targetEntity: Epreuve::class)] // <-- Très probablement EpreuveBac::class ici
    #[ORM\JoinColumn(nullable: false)]
    private ?Epreuve $epreuve = null; // <-- Le type de la propriété doit correspondre au targetEntity et initialiser à null

    // Correction pour 'resultat' :
    // - type: 'float' est correct.
    // - L'attribut 'length' n'est pas valide pour les types numériques. Supprimez-le.
    #[ORM\Column(type: 'float')]
    private float $resultat; // <-- Le type de la propriété doit être 'float'

    // Correction pour 'note' :
    // - type: 'float' est correct.
    // - L'attribut 'length' n'est pas valide pour les types numériques. Supprimez-le.
    #[ORM\Column(type: 'float')]
    private float $note; // <-- Le type de la propriété doit être 'float'

    #[ORM\Column(type: 'string', length: 10)]
    private string $par;

    // Getters & Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEleve(): ?Eleve // Type de retour doit être nullable si la propriété l'est
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self // Paramètre doit être nullable si la propriété l'est
    {
        $this->eleve = $eleve;
        return $this;
    }

    public function getEpreuve(): ?Epreuve // Type de retour doit être nullable et correspondre au targetEntity
    {
        return $this->epreuve;
    }

    public function setEpreuve(?Epreuve $epreuve): self // Paramètre doit être nullable et correspondre au targetEntity
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

    public function getPar(): string
    {
        return $this->par;
    }

    public function setPar(string $par): self
    {
        $this->par = $par;
        return $this;
    }
}
