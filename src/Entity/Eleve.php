<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Persistence\Event\PrePersistEventArgs;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Eleve
{
   #[ORM\Id]
    #[ORM\Column(type: 'string', length: 10)]
    private ?string $cin = null;


    #[ORM\Column(type: 'string', length: 100)]
    private string $nomPrenom;

    #[ORM\Column(type: 'string', length: 1)]
    private string $sexe;

    #[ORM\ManyToOne(targetEntity: Classe::class)]
    #[ORM\JoinColumn(nullable: false)]
private ?Classe $classe = null;

    #[ORM\ManyToOne(targetEntity: Lycee::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Lycee $lycee;

    # matricule de l'eleve varchar de 10
    #[ORM\Column(type: 'string', length: 10, unique: true)]
    private string $matricule;

    /**
     * @var Collection<int, Dispense>
     */
    #[ORM\OneToMany(targetEntity: Dispense::class, mappedBy: 'eleve')]
    private Collection $dispenses;

    public function __construct()
    {
        $this->dispenses = new ArrayCollection();
    }


    public function generateCin(PrePersistEventArgs $args): void
    {
        $matricule = $this->getMatricule();
        if (!$matricule || strlen($matricule) < 4) {
            return;
        }

        $baseCin = substr($matricule, -4);
        $entityManager = $args->getObjectManager();

        $suffix = 1;
        $cinCandidate = $baseCin . $suffix;

        // boucle jusqu'à trouver un cin non utilisé
        while ($entityManager->getRepository(self::class)->findOneBy(['cin' => $cinCandidate])) {
            $suffix++;
            $cinCandidate = $baseCin . $suffix;
        }

        $this->setCin($cinCandidate);
    }
    // Getters & Setters
    public function getCin(): ?string
{
    return $this->cin;
}

public function setCin(string $cin): self
{
    $this->cin = $cin;
    return $this;
}

    public function getNomPrenom(): string
    {
        return $this->nomPrenom;
    }

    public function setNomPrenom(string $nomPrenom): self
    {
        $this->nomPrenom = $nomPrenom;
        return $this;
    }

    public function getSexe(): string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;
        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(Classe $classe): self
    {
        $this->classe = $classe;
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
    public function getMatricule(): string
    {
        return $this->matricule;
    }
    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;
        return $this;
    }

    public function __toString(): string
    {
        return $this->nomPrenom . ' (' . $this->matricule . ')';
    }

    /**
     * @return Collection<int, Dispense>
     */
    public function getDispenses(): Collection
    {
        return $this->dispenses;
    }

    public function addDispense(Dispense $dispense): static
    {
        if (!$this->dispenses->contains($dispense)) {
            $this->dispenses->add($dispense);
            $dispense->setEleve($this);
        }

        return $this;
    }

    public function removeDispense(Dispense $dispense): static
    {
        if ($this->dispenses->removeElement($dispense)) {
            // set the owning side to null (unless already changed)
            if ($dispense->getEleve() === $this) {
                $dispense->setEleve(null);
            }
        }

        return $this;
    }
}
