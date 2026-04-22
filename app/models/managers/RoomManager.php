<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../entities/Room.php';

class RoomManager
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM rooms ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM rooms WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $room = $stmt->fetch();

        return $room ?: null;
    }

    public function create(Room $room): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO rooms (room_number, type, price, status, image) VALUES (:room_number, :type, :price, :status, :image)');

        return $stmt->execute([
            'room_number' => $room->getRoomNumber(),
            'type' => $room->getType(),
            'price' => $room->getPrice(),
            'status' => $room->getStatus(),
            'image' => $room->getImage(),
        ]);
    }

    public function update(Room $room): bool
    {
        $stmt = $this->pdo->prepare('UPDATE rooms SET room_number = :room_number, type = :type, price = :price, status = :status, image = :image WHERE id = :id');

        return $stmt->execute([
            'id' => $room->getId(),
            'room_number' => $room->getRoomNumber(),
            'type' => $room->getType(),
            'price' => $room->getPrice(),
            'status' => $room->getStatus(),
            'image' => $room->getImage(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM rooms WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
