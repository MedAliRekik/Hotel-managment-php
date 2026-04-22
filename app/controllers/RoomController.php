<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/entities/Room.php';
require_once __DIR__ . '/../models/managers/RoomManager.php';
require_once __DIR__ . '/../models/Uploader.php';

class RoomController
{
    private RoomManager $roomManager;
    private Uploader $uploader;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';

        $this->roomManager = new RoomManager();
        $this->uploader = new Uploader($config['app']['upload_dir'], (int) $config['app']['max_upload_size']);
        $this->ensureAuthenticated();
    }

    public function index(): void
    {
        $rooms = $this->roomManager->getAll();
        require __DIR__ . '/../views/rooms/index.php';
    }

    public function create(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomNumber = trim($_POST['room_number'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $price = (float) ($_POST['price'] ?? 0);
            $status = trim($_POST['status'] ?? 'available');
            $image = null;

            if (!empty($_FILES['image']['name'])) {
                $upload = $this->uploader->upload($_FILES['image']);

                if (!$upload['success']) {
                    $error = $upload['message'];
                } else {
                    $image = $upload['filename'];
                }
            }

            if (!$error && ($roomNumber === '' || $type === '' || $price <= 0)) {
                $error = 'Room number, type and valid price are required.';
            }

            if (!$error) {
                $room = new Room(null, $roomNumber, $type, $price, $status, $image);
                $this->roomManager->create($room);
                $this->redirect('room', 'index');
            }
        }

        require __DIR__ . '/../views/rooms/create.php';
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $room = $this->roomManager->getById($id);

        if (!$room) {
            $this->redirect('room', 'index');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomNumber = trim($_POST['room_number'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $price = (float) ($_POST['price'] ?? 0);
            $status = trim($_POST['status'] ?? 'available');
            $image = $room['image'];

            if (!empty($_FILES['image']['name'])) {
                $upload = $this->uploader->upload($_FILES['image']);

                if (!$upload['success']) {
                    $error = $upload['message'];
                } else {
                    $image = $upload['filename'];
                }
            }

            if (!$error && ($roomNumber === '' || $type === '' || $price <= 0)) {
                $error = 'Room number, type and valid price are required.';
            }

            if (!$error) {
                $updated = new Room($id, $roomNumber, $type, $price, $status, $image);
                $this->roomManager->update($updated);
                $this->redirect('room', 'index');
            }
        }

        require __DIR__ . '/../views/rooms/edit.php';
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->roomManager->delete($id);
        }

        $this->redirect('room', 'index');
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
