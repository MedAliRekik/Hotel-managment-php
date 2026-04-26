<?php

declare(strict_types=1);

require_once __DIR__ . '/../helpers/Csrf.php';
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

        $formData = [
            'client_id' => 0,
            'room_id' => 0,
            'check_in' => '',
            'check_out' => '',
            'status' => 'pending',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::isValid($_POST['csrf_token'] ?? null)) {
                $error = 'Invalid request token. Please try again.';
            } else {
                $formData['client_id'] = (int) ($_POST['client_id'] ?? 0);
                $formData['room_id'] = (int) ($_POST['room_id'] ?? 0);
                $formData['check_in'] = trim($_POST['check_in'] ?? '');
                $formData['check_out'] = trim($_POST['check_out'] ?? '');
                $formData['status'] = trim($_POST['status'] ?? 'pending');

                if ($formData['client_id'] <= 0 || $formData['room_id'] <= 0 || $formData['check_in'] === '' || $formData['check_out'] === '') {
                    $error = 'All fields are required.';
                } elseif (!$this->isValidDate($formData['check_in']) || !$this->isValidDate($formData['check_out'])) {
                    $error = 'Invalid date format.';
                } elseif ($formData['check_out'] <= $formData['check_in']) {
                    $error = 'Check-out date must be after check-in date.';
                } elseif ($this->reservationManager->hasRoomConflict($formData['room_id'], $formData['check_in'], $formData['check_out'])) {
                    $error = 'The selected room is already booked for that period.';
                } else {
                    $reservation = new Reservation(
                        null,
                        $formData['client_id'],
                        $formData['room_id'],
                        $formData['check_in'],
                        $formData['check_out'],
                        $formData['status']
                    );
                    $this->reservationManager->create($reservation);
                    $this->redirect('reservation', 'index');
                }
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

        $formData = [
            'client_id' => (int) $reservation['client_id'],
            'room_id' => (int) $reservation['room_id'],
            'check_in' => (string) $reservation['check_in'],
            'check_out' => (string) $reservation['check_out'],
            'status' => (string) $reservation['status'],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::isValid($_POST['csrf_token'] ?? null)) {
                $error = 'Invalid request token. Please try again.';
            } else {
                $formData['client_id'] = (int) ($_POST['client_id'] ?? 0);
                $formData['room_id'] = (int) ($_POST['room_id'] ?? 0);
                $formData['check_in'] = trim($_POST['check_in'] ?? '');
                $formData['check_out'] = trim($_POST['check_out'] ?? '');
                $formData['status'] = trim($_POST['status'] ?? 'pending');

                if ($formData['client_id'] <= 0 || $formData['room_id'] <= 0 || $formData['check_in'] === '' || $formData['check_out'] === '') {
                    $error = 'All fields are required.';
                } elseif (!$this->isValidDate($formData['check_in']) || !$this->isValidDate($formData['check_out'])) {
                    $error = 'Invalid date format.';
                } elseif ($formData['check_out'] <= $formData['check_in']) {
                    $error = 'Check-out date must be after check-in date.';
                } elseif ($this->reservationManager->hasRoomConflict($formData['room_id'], $formData['check_in'], $formData['check_out'], $id)) {
                    $error = 'The selected room is already booked for that period.';
                } else {
                    $updated = new Reservation(
                        $id,
                        $formData['client_id'],
                        $formData['room_id'],
                        $formData['check_in'],
                        $formData['check_out'],
                        $formData['status']
                    );
                    $this->reservationManager->update($updated);
                    $this->redirect('reservation', 'index');
                }
            }
        }

        require __DIR__ . '/../views/reservations/edit.php';
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Csrf::isValid($_POST['csrf_token'] ?? null)) {
            $this->redirect('reservation', 'index');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->reservationManager->delete($id);
        }

        $this->redirect('reservation', 'index');
    }

    private function isValidDate(string $date): bool
    {
        $dateTime = DateTime::createFromFormat('Y-m-d', $date);

        return $dateTime !== false && $dateTime->format('Y-m-d') === $date;
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
