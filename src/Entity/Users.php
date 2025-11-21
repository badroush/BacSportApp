<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\Table(name: 'users')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $user;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    // === CHANGEMENT ICI : Type JSON pour les rôles ===
    #[ORM\Column(type: Types::JSON)]
    private array $type = []; // exemple: ["ROLE_ADMIN"]

    #[ORM\Column(length: 10)]
    private string $etat; // exemple: actif, bloqué

    #[ORM\Column(length: 200)]
    private string $photo;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;
    
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $pwdmodif = null;
    public function __construct()
    {
        $this->createdAt = new \DateTime();  // Définit la date courante par défaut
        $this->pwdmodif = new \DateTime();  // Définit la date courante par défaut
        $this->photo = 'default.jpg'; // Chemin par défaut pour la photo
        $this->etat = 'inactif'; // État par défaut
        # hacher le mot de passe par défaut
        
    }

    #[ORM\ManyToOne(targetEntity: Etablissement::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etablissement $etablissement = null;

    #[ORM\Column(length: 20)]
    private string $mobile;

    private ?string $plainPassword = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
private ?\DateTimeInterface $lastLogin = null;

public function getLastLogin(): ?\DateTimeInterface
{
    return $this->lastLogin;
}

public function setLastLogin(?\DateTimeInterface $lastLogin): self
{
    $this->lastLogin = $lastLogin;
    return $this;
}
public function getPlainPassword(): ?string
{
    return $this->plainPassword;
}

public function setPlainPassword(?string $plainPassword): self
{
    $this->plainPassword = $plainPassword;
    return $this;
}

    

    #[ORM\Column(length: 10, unique: true)]
    private string $cnrps;

    #[ORM\Column(length: 200, unique: true)]
    private string $email;

    // === Implémentation des méthodes Symfony ===

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        // === CHANGEMENT ICI : retourne un tableau de rôles ===
        return $this->type;
    }

    public function setRoles(array $roles): self
    {
        $this->type = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Si vous stockez des données temporaires sensibles, effacez-les ici
        // Par exemple : $this->plainPassword = null;
    }

    // === Getters & Setters personnalisés ===

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getType(): array
    {
        return $this->type;
    }

    public function setType(array $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getEtat(): string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }


     // Getter
public function getCreatedAt(): ?\DateTimeImmutable
{
    if ($this->createdAt === null) {
        return null;
    }

    // Si c’est un DateTime mutable, on le convertit en DateTimeImmutable
    if ($this->createdAt instanceof \DateTimeImmutable) {
        return $this->createdAt;
    }

    return \DateTimeImmutable::createFromMutable($this->createdAt);
}

// Setter
public function setCreatedAt(?\DateTimeImmutable $createdAt): self
{
    $this->createdAt = $createdAt;
    return $this;
}
    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;
        return $this;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function getPwdmodif(): ?\DateTimeImmutable
{
    if ($this->pwdmodif === null) {
        return null;
    }

    // Si c’est un DateTime mutable, on le convertit en DateTimeImmutable
    if ($this->pwdmodif instanceof \DateTimeImmutable) {
        return $this->pwdmodif;
    }

    return \DateTimeImmutable::createFromMutable($this->pwdmodif);
}

    public function setPwdmodif(\DateTimeInterface $pwdmodif): self
    {
        $this->pwdmodif = $pwdmodif;
        return $this;
    }

    public function getCnrps(): string
    {
        return $this->cnrps;
    }

    public function setCnrps(string $cnrps): self
    {
        $this->cnrps = $cnrps;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
}