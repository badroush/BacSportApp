<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    private $sender;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    private $receiver;

    #[ORM\Column(type: "string", length: 500)]
    private $content;

    #[ORM\Column(type: "datetime_immutable")]
    private $createdAt;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isRead = false;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?Users
    {
        return $this->sender;
    }

    public function setSender(?Users $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?Users
    {
        return $this->receiver;
    }

    public function setReceiver(?Users $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function isRead(): bool   { return $this->isRead; }
public function setIsRead(bool $r): self { $this->isRead = $r; return $this; }
}
