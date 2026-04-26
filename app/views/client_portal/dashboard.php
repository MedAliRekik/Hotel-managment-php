<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <h2>Client Dashboard</h2>
    <p>Welcome back, <?= htmlspecialchars($_SESSION['client']['full_name']) ?>.</p>

    <div class="page-head">
        <p><strong>Available rooms:</strong> <?= count($availableRooms) ?></p>
        <p><strong>My reservations:</strong> <?= count($reservations) ?></p>
    </div>

    <p>
        <a class="btn" href="index.php?controller=clientportal&action=rooms">View Available Rooms</a>
        <a class="btn" href="index.php?controller=clientportal&action=myReservations">View My Reservations</a>
    </p>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
