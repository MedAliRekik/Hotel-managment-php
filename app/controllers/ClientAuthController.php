<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/entities/Client.php';
require_once __DIR__ . '/../models/managers/ClientManager.php';

class ClientAuthController
{
    private ClientManager $clientManager;

    public function __construct()
    {
        $this->clientManager = new ClientManager();
    }

    public function login(): void
    {
        if (isset($_SESSION['client'])) {
            $this->redirect('clientportal', 'rooms');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                $error = 'Email and password are required.';
            } else {
                $client = $this->clientManager->findByEmail($email);

                if ($client && !empty($client['password']) && password_verify($password, $client['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['client'] = [
                        'id' => (int) $client['id'],
                        'full_name' => $client['first_name'] . ' ' . $client['last_name'],
                        'email' => $client['email'],
                    ];
                    $this->redirect('clientportal', 'rooms');
                }

                $error = 'Invalid client credentials.';
            }
        }

        require __DIR__ . '/../views/auth/client_login.php';
    }

    public function register(): void
    {
        if (isset($_SESSION['client'])) {
            $this->redirect('clientportal', 'rooms');
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
                $error = 'First name, last name, email and password are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif ($this->clientManager->findByEmail($email)) {
                $error = 'Email is already used by another client.';
            } else {
                $client = new Client(
                    null,
                    $firstName,
                    $lastName,
                    $phone !== '' ? $phone : null,
                    $email,
                    password_hash($password, PASSWORD_DEFAULT)
                );

                if ($this->clientManager->create($client)) {
                    $success = 'Registration successful. You can login now.';
                } else {
                    $error = 'Unable to create account. Please try again.';
                }
            }
        }

        require __DIR__ . '/../views/auth/client_register.php';
    }

    public function logout(): void
    {
        unset($_SESSION['client']);
        session_regenerate_id(true);

        $this->redirect('clientauth', 'login');
    }

    private function redirect(string $controller, string $action): void
    {
        header("Location: index.php?controller={$controller}&action={$action}");
        exit;
    }
}
