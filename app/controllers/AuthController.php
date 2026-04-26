<?php

declare(strict_types=1);

require_once __DIR__ . '/../helpers/Csrf.php';
require_once __DIR__ . '/../models/managers/UserManager.php';

class AuthController
{
    private UserManager $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    public function login(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('dashboard', 'index');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::isValid($_POST['csrf_token'] ?? null)) {
                $error = 'Invalid request token. Please try again.';
            } else {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                $user = $this->userManager->findByEmail($email);

                if ($user && password_verify($password, $user->getPassword())) {
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => $user->getId(),
                        'fullname' => $user->getFullname(),
                        'email' => $user->getEmail(),
                    ];
                    $this->redirect('dashboard', 'index');
                }

                $error = 'Invalid credentials.';
            }
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Csrf::isValid($_POST['csrf_token'] ?? null)) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        unset($_SESSION['user']);
        session_regenerate_id(true);

        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    private function redirect(string $controller, string $action): void
    {
        header("Location: index.php?controller={$controller}&action={$action}");
        exit;
    }
}
