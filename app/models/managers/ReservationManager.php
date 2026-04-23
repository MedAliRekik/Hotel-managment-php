<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../entities/Reservation.php';

class ReservationManager
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllWithRelations(): array
    {
        $sql = 'SELECT
                    r.id,
                    r.client_id,
                    r.room_id,
                    r.check_in,
                    r.check_out,
                    r.status,
                    CONCAT(c.first_name, " ", c.last_name) AS client_full_name,
                    rm.room_number
                FROM reservations r
                INNER JOIN clients c ON c.id = r.client_id
                INNER JOIN rooms rm ON rm.id = r.room_id
                ORDER BY r.id DESC';

        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM reservations WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $reservation = $stmt->fetch();

        return $reservation ?: null;
    }

    public function create(Reservation $reservation): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO reservations (client_id, room_id, check_in, check_out, status) VALUES (:client_id, :room_id, :check_in, :check_out, :status)');

        return $stmt->execute([
            'client_id' => $reservation->getClientId(),
            'room_id' => $reservation->getRoomId(),
            'check_in' => $reservation->getCheckIn(),
            'check_out' => $reservation->getCheckOut(),
            'status' => $reservation->getStatus(),
        ]);
    }

    public function update(Reservation $reservation): bool
    {
        $stmt = $this->pdo->prepare('UPDATE reservations SET client_id = :client_id, room_id = :room_id, check_in = :check_in, check_out = :check_out, status = :status WHERE id = :id');

        return $stmt->execute([
            'id' => $reservation->getId(),
            'client_id' => $reservation->getClientId(),
            'room_id' => $reservation->getRoomId(),
            'check_in' => $reservation->getCheckIn(),
            'check_out' => $reservation->getCheckOut(),
            'status' => $reservation->getStatus(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM reservations WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function search(array $filters): array
    {
        $sql = 'SELECT
                    r.id,
                    r.client_id,
                    r.room_id,
                    r.check_in,
                    r.check_out,
                    r.status,
                    CONCAT(c.first_name, " ", c.last_name) AS client_full_name,
                    rm.room_number
                FROM reservations r
                INNER JOIN clients c ON c.id = r.client_id
                INNER JOIN rooms rm ON rm.id = r.room_id
                WHERE 1=1';

        $params = [];

        if (!empty($filters['client_name'])) {
            $sql .= ' AND CONCAT(c.first_name, " ", c.last_name) LIKE :client_name';
            $params['client_name'] = '%' . $filters['client_name'] . '%';
        }

        if (!empty($filters['room_number'])) {
            $sql .= ' AND rm.room_number LIKE :room_number';
            $params['room_number'] = '%' . $filters['room_number'] . '%';
        }

        if (!empty($filters['check_in'])) {
            $sql .= ' AND r.check_in >= :check_in';
            $params['check_in'] = $filters['check_in'];
        }

        if (!empty($filters['check_out'])) {
            $sql .= ' AND r.check_out <= :check_out';
            $params['check_out'] = $filters['check_out'];
        }

        if (!empty($filters['status'])) {
            $sql .= ' AND r.status = :status';
            $params['status'] = $filters['status'];
        }

        $sql .= ' ORDER BY r.id DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
