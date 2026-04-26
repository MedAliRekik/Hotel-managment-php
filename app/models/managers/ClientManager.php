<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../entities/Client.php';

class ClientManager
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM clients ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM clients WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $client = $stmt->fetch();

        return $client ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM clients WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $client = $stmt->fetch();

        return $client ?: null;
    }

    public function create(Client $client): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO clients (first_name, last_name, phone, email, password) VALUES (:first_name, :last_name, :phone, :email, :password)');

        return $stmt->execute([
            'first_name' => $client->getFirstName(),
            'last_name' => $client->getLastName(),
            'phone' => $client->getPhone(),
            'email' => $client->getEmail(),
            'password' => $client->getPassword(),
        ]);
    }

    public function update(Client $client): bool
    {
        $stmt = $this->pdo->prepare('UPDATE clients SET first_name = :first_name, last_name = :last_name, phone = :phone, email = :email WHERE id = :id');

        return $stmt->execute([
            'id' => $client->getId(),
            'first_name' => $client->getFirstName(),
            'last_name' => $client->getLastName(),
            'phone' => $client->getPhone(),
            'email' => $client->getEmail(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM clients WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
