<?php

declare(strict_types=1);

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
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userManager->findByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'fullname' => $user->getFullname(),
                    'email' => $user->getEmail(),
                ];
                $this->redirect('dashboard', 'index');
            }

            $error = 'Invalid credentials.';
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    private function redirect(string $controller, string $action): void
    {
        header("Location: index.php?controller={$controller}&action={$action}");
        exit;
    }
}
