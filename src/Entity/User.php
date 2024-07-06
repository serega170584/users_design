<?php

declare(strict_types=1);

namespace User\Entity;

class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private \DateTimeImmutable $created;
    private ?\DateTimeImmutable $deleted;
    private ?string $notes;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @throws \Exception
     */
    public function setId(?int $id): void
    {
        if ($this->id !== null) {
            throw new \Exception('Id redefine is not permitted');
        }

        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(\DateTimeImmutable $created): void
    {
        $this->created = $created;
    }

    public function getDeleted(): ?\DateTimeImmutable
    {
        return $this->deleted;
    }

    /**
     * @throws \Exception
     */
    public function setDeleted(?\DateTimeImmutable $deleted): void
    {
        if ($this->deleted < $this->created) {
            throw new \Exception(sprintf('Deleted field can not be more than created field for user %s', $this->id));
        }

        $this->deleted = $deleted;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }
}