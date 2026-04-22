<?php

declare(strict_types=1);

class Reservation
{
    private ?int $id;
    private int $clientId;
    private int $roomId;
    private string $checkIn;
    private string $checkOut;
    private string $status;
    private ?string $createdAt;

    public function __construct(?int $id, int $clientId, int $roomId, string $checkIn, string $checkOut, string $status, ?string $createdAt = null)
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->roomId = $roomId;
        $this->checkIn = $checkIn;
        $this->checkOut = $checkOut;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function getClientId(): int { return $this->clientId; }
    public function setClientId(int $clientId): void { $this->clientId = $clientId; }
    public function getRoomId(): int { return $this->roomId; }
    public function setRoomId(int $roomId): void { $this->roomId = $roomId; }
    public function getCheckIn(): string { return $this->checkIn; }
    public function setCheckIn(string $checkIn): void { $this->checkIn = $checkIn; }
    public function getCheckOut(): string { return $this->checkOut; }
    public function setCheckOut(string $checkOut): void { $this->checkOut = $checkOut; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }
}
