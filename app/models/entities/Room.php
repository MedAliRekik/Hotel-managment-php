<?php

declare(strict_types=1);

class Room
{
    private ?int $id;
    private string $roomNumber;
    private string $type;
    private float $price;
    private string $status;
    private ?string $image;
    private ?string $createdAt;

    public function __construct(?int $id, string $roomNumber, string $type, float $price, string $status, ?string $image, ?string $createdAt = null)
    {
        $this->id = $id;
        $this->roomNumber = $roomNumber;
        $this->type = $type;
        $this->price = $price;
        $this->status = $status;
        $this->image = $image;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function getRoomNumber(): string { return $this->roomNumber; }
    public function setRoomNumber(string $roomNumber): void { $this->roomNumber = $roomNumber; }
    public function getType(): string { return $this->type; }
    public function setType(string $type): void { $this->type = $type; }
    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): void { $this->price = $price; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): void { $this->image = $image; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }
}
