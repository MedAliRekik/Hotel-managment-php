<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/entities/Reservation.php';
require_once __DIR__ . '/../models/managers/ReservationManager.php';
require_once __DIR__ . '/../models/managers/ClientManager.php';
require_once __DIR__ . '/../models/managers/RoomManager.php';

class ReservationController
{
    private ReservationManager $reservationManager;
    private ClientManager $clientManager;
    private RoomManager $roomManager;

    public function __construct()
    {
        $this->reservationManager = new ReservationManager();
        $this->clientManager = new ClientManager();
        $this->roomManager = new RoomManager();
        $this->ensureAuthenticated();
    }

    public function index(): void
    {
        $filters = [
            'client_name' => trim($_GET['client_name'] ?? ''),
            'room_number' => trim($_GET['room_number'] ?? ''),
            'check_in' => trim($_GET['check_in'] ?? ''),
            'check_out' => trim($_GET['check_out'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
        ];

        $hasFilters = implode('', $filters) !== '';
        $reservations = $hasFilters
            ? $this->reservationManager->search($filters)
            : $this->reservationManager->getAllWithRelations();

        require __DIR__ . '/../views/reservations/index.php';
    }

    public function create(): void
    {
        $clients = $this->clientManager->getAll();
        $rooms = $this->roomManager->getAll();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientId = (int) ($_POST['client_id'] ?? 0);
            $roomId = (int) ($_POST['room_id'] ?? 0);
            $checkIn = trim($_POST['check_in'] ?? '');
            $checkOut = trim($_POST['check_out'] ?? '');
            $status = trim($_POST['status'] ?? 'pending');

            if ($clientId <= 0 || $roomId <= 0 || $checkIn === '' || $checkOut === '') {
                $error = 'All fields are required.';
            } elseif ($checkOut < $checkIn) {
                $error = 'Check-out date must be after check-in date.';
            } else {
                $reservation = new Reservation(null, $clientId, $roomId, $checkIn, $checkOut, $status);
                $this->reservationManager->create($reservation);
                $this->redirect('reservation', 'index');
            }
        }

        require __DIR__ . '/../views/reservations/create.php';
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $reservation = $this->reservationManager->getById($id);

        if (!$reservation) {
            $this->redirect('reservation', 'index');
        }

        $clients = $this->clientManager->getAll();
        $rooms = $this->roomManager->getAll();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientId = (int) ($_POST['client_id'] ?? 0);
            $roomId = (int) ($_POST['room_id'] ?? 0);
            $checkIn = trim($_POST['check_in'] ?? '');
            $checkOut = trim($_POST['check_out'] ?? '');
            $status = trim($_POST['status'] ?? 'pending');

            if ($clientId <= 0 || $roomId <= 0 || $checkIn === '' || $checkOut === '') {
                $error = 'All fields are required.';
            } elseif ($checkOut < $checkIn) {
                $error = 'Check-out date must be after check-in date.';
            } else {
                $updated = new Reservation($id, $clientId, $roomId, $checkIn, $checkOut, $status);
                $this->reservationManager->update($updated);
                $this->redirect('reservation', 'index');
            }
        }

        require __DIR__ . '/../views/reservations/edit.php';
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->reservationManager->delete($id);
        }

        $this->redirect('reservation', 'index');
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
