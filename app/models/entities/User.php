<?php

declare(strict_types=1);

class User
{
    private ?int $id;
    private string $fullname;
    private string $email;
    private string $password;
    private ?string $createdAt;

    public function __construct(?int $id, string $fullname, string $email, string $password, ?string $createdAt = null)
    {
        $this->id = $id;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function getFullname(): string { return $this->fullname; }
    public function setFullname(string $fullname): void { $this->fullname = $fullname; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }
}
