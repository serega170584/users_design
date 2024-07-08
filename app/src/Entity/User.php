<?php

declare(strict_types=1);

namespace User\Entity;

final class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private \DateTimeImmutable $created;
    private ?\DateTimeImmutable $deleted;
    private ?string $notes;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @throws \Exception
     */
    public function setId(?int $id): void
    {
//        if ($this->id !== null) {
//            throw new IdRedefineIsNotPermittedException();
//        }

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

    public function setDeleted(?\DateTimeImmutable $deleted): void
    {
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