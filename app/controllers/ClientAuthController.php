<?php

declare(strict_types=1);

require_once __DIR__ . '/../helpers/Csrf.php';
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
            $this->redirect('clientportal', 'dashboard');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::isValid($_POST['csrf_token'] ?? null)) {
                $error = 'Invalid request token. Please try again.';
            } else {
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
                        $this->redirect('clientportal', 'dashboard');
                    }

                    $error = 'Invalid client credentials.';
                }
            }
        }

        require __DIR__ . '/../views/auth/client_login.php';
    }

    public function register(): void
    {
        if (isset($_SESSION['client'])) {
            $this->redirect('clientportal', 'dashboard');
        }

        $error = null;
        $success = null;

        $formData = [
            'first_name' => '',
            'last_name' => '',
            'phone' => '',
            'email' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::isValid($_POST['csrf_token'] ?? null)) {
                $error = 'Invalid request token. Please try again.';
            } else {
                $formData['first_name'] = trim($_POST['first_name'] ?? '');
                $formData['last_name'] = trim($_POST['last_name'] ?? '');
                $formData['phone'] = trim($_POST['phone'] ?? '');
                $formData['email'] = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                if ($formData['first_name'] === '' || $formData['last_name'] === '' || $formData['email'] === '' || $password === '') {
                    $error = 'First name, last name, email and password are required.';
                } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                    $error = 'Please enter a valid email address.';
                } elseif (strlen($password) < 6) {
                    $error = 'Password must be at least 6 characters.';
                } elseif ($this->clientManager->findByEmail($formData['email'])) {
                    $error = 'Email is already used by another client.';
                } else {
                    $client = new Client(
                        null,
                        $formData['first_name'],
                        $formData['last_name'],
                        $formData['phone'] !== '' ? $formData['phone'] : null,
                        $formData['email'],
                        password_hash($password, PASSWORD_DEFAULT)
                    );

                    if ($this->clientManager->create($client)) {
                        $success = 'Registration successful. You can login now.';
                        $formData = ['first_name' => '', 'last_name' => '', 'phone' => '', 'email' => ''];
                    } else {
                        $error = 'Unable to create account. Please try again.';
                    }
                }
            }
        }

        require __DIR__ . '/../views/auth/client_register.php';
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Csrf::isValid($_POST['csrf_token'] ?? null)) {
            $this->redirect('clientauth', 'login');
        }

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
