<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <div class="page-head">
        <h2>Reservations</h2>
        <a class="btn" href="index.php?controller=reservation&action=create">+ Add Reservation</a>
    </div>

    <form method="get" class="search-grid">
        <input type="hidden" name="controller" value="reservation">
        <input type="hidden" name="action" value="index">
        <input type="text" name="client_name" placeholder="Client name" value="<?= htmlspecialchars($filters['client_name'] ?? '') ?>">
        <input type="text" name="room_number" placeholder="Room number" value="<?= htmlspecialchars($filters['room_number'] ?? '') ?>">
        <input type="date" name="check_in" value="<?= htmlspecialchars($filters['check_in'] ?? '') ?>">
        <input type="date" name="check_out" value="<?= htmlspecialchars($filters['check_out'] ?? '') ?>">
        <select name="status">
            <option value="">All statuses</option>
            <?php foreach (['pending', 'confirmed', 'cancelled', 'completed'] as $status): ?>
                <option value="<?= $status ?>" <?= (($filters['status'] ?? '') === $status) ? 'selected' : '' ?>><?= ucfirst($status) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Room</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td><?= (int) $reservation['id'] ?></td>
                <td><?= htmlspecialchars($reservation['client_full_name']) ?></td>
                <td><?= htmlspecialchars($reservation['room_number']) ?></td>
                <td><?= htmlspecialchars($reservation['check_in']) ?></td>
                <td><?= htmlspecialchars($reservation['check_out']) ?></td>
                <td><?= htmlspecialchars($reservation['status']) ?></td>
                <td>
                    <a href="index.php?controller=reservation&action=edit&id=<?= (int) $reservation['id'] ?>">Edit</a>
                    <form method="post" action="index.php?controller=reservation&action=delete" style="display:inline;" onsubmit="return confirm('Delete this reservation?')">
                        <?= Csrf::input(); ?>
                        <input type="hidden" name="id" value="<?= (int) $reservation['id'] ?>">
                        <button type="submit" class="danger link-button">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
