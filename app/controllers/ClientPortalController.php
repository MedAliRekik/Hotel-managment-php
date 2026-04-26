<?php

declare(strict_types=1);

require_once __DIR__ . '/../helpers/Csrf.php';
require_once __DIR__ . '/../models/entities/Reservation.php';
require_once __DIR__ . '/../models/managers/RoomManager.php';
require_once __DIR__ . '/../models/managers/ReservationManager.php';

class ClientPortalController
{
    private RoomManager $roomManager;
    private ReservationManager $reservationManager;

    public function __construct()
    {
        $this->roomManager = new RoomManager();
        $this->reservationManager = new ReservationManager();
        $this->ensureClientAuthenticated();
    }

    public function dashboard(): void
    {
        $availableRooms = $this->roomManager->getAvailableRooms();
        $reservations = $this->reservationManager->getByClientId((int) $_SESSION['client']['id']);

        require __DIR__ . '/../views/client_portal/dashboard.php';
    }

    public function rooms(): void
    {
        $rooms = $this->roomManager->getAvailableRooms();
        require __DIR__ . '/../views/rooms/available.php';
    }

    public function reserve(): void
    {
        $roomId = (int) ($_GET['room_id'] ?? $_POST['room_id'] ?? 0);
        $room = $this->roomManager->getById($roomId);

        if (!$room || $room['status'] !== 'available') {
            $this->redirect('clientportal', 'rooms');
        }

        $error = null;

        $formData = [
            'check_in' => '',
            'check_out' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::isValid($_POST['csrf_token'] ?? null)) {
                $error = 'Invalid request token. Please try again.';
            } else {
                $formData['check_in'] = trim($_POST['check_in'] ?? '');
                $formData['check_out'] = trim($_POST['check_out'] ?? '');

                if ($formData['check_in'] === '' || $formData['check_out'] === '') {
                    $error = 'Check-in and check-out dates are required.';
                } elseif (!$this->isValidDate($formData['check_in']) || !$this->isValidDate($formData['check_out'])) {
                    $error = 'Invalid date format.';
                } elseif ($formData['check_out'] <= $formData['check_in']) {
                    $error = 'Check-out date must be after check-in date.';
                } elseif ($this->reservationManager->hasRoomConflict($roomId, $formData['check_in'], $formData['check_out'])) {
                    $error = 'This room is already booked for the selected dates.';
                } else {
                    $reservation = new Reservation(
                        null,
                        (int) $_SESSION['client']['id'],
                        $roomId,
                        $formData['check_in'],
                        $formData['check_out'],
                        'pending'
                    );

                    if ($this->reservationManager->create($reservation)) {
                        $this->redirect('clientportal', 'myReservations');
                    }

                    $error = 'Reservation failed. Please try again.';
                }
            }
        }

        require __DIR__ . '/../views/reservations/client_create.php';
    }

    public function myReservations(): void
    {
        $reservations = $this->reservationManager->getByClientId((int) $_SESSION['client']['id']);
        require __DIR__ . '/../views/reservations/client_index.php';
    }

    private function isValidDate(string $date): bool
    {
        $dateTime = DateTime::createFromFormat('Y-m-d', $date);

        return $dateTime !== false && $dateTime->format('Y-m-d') === $date;
    }

    private function ensureClientAuthenticated(): void
    {
        if (!isset($_SESSION['client'])) {
            header('Location: index.php?controller=clientauth&action=login');
            exit;
        }
    }

    private function redirect(string $controller, string $action): void
    {
        header("Location: index.php?controller={$controller}&action={$action}");
        exit;
    }
}
