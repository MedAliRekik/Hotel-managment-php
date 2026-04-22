<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/entities/Client.php';
require_once __DIR__ . '/../models/managers/ClientManager.php';

class ClientController
{
    private ClientManager $clientManager;

    public function __construct()
    {
        $this->clientManager = new ClientManager();
        $this->ensureAuthenticated();
    }

    public function index(): void
    {
        $clients = $this->clientManager->getAll();
        require __DIR__ . '/../views/clients/index.php';
    }

    public function create(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if ($firstName === '' || $lastName === '') {
                $error = 'First name and last name are required.';
            } else {
                $client = new Client(null, $firstName, $lastName, $phone ?: null, $email ?: null);
                $this->clientManager->create($client);
                $this->redirect('client', 'index');
            }
        }

        require __DIR__ . '/../views/clients/create.php';
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $client = $this->clientManager->getById($id);

        if (!$client) {
            $this->redirect('client', 'index');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if ($firstName === '' || $lastName === '') {
                $error = 'First name and last name are required.';
            } else {
                $updated = new Client($id, $firstName, $lastName, $phone ?: null, $email ?: null);
                $this->clientManager->update($updated);
                $this->redirect('client', 'index');
            }
        }

        require __DIR__ . '/../views/clients/edit.php';
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->clientManager->delete($id);
        }

        $this->redirect('client', 'index');
    }

    private function ensureAuthenticated(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    private function redirect(string $controller, string $action): void
    {
        header("Location: index.php?controller={$controller}&action={$action}");
        exit;
    }
}
