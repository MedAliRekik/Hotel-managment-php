<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <h2>My Reservations</h2>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Room #</th>
            <th>Type</th>
            <th>Price</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td><?= (int) $reservation['id'] ?></td>
                <td><?= htmlspecialchars($reservation['room_number']) ?></td>
                <td><?= htmlspecialchars($reservation['type']) ?></td>
                <td>$<?= number_format((float) $reservation['price'], 2) ?></td>
                <td><?= htmlspecialchars($reservation['check_in']) ?></td>
                <td><?= htmlspecialchars($reservation['check_out']) ?></td>
                <td><?= htmlspecialchars($reservation['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
