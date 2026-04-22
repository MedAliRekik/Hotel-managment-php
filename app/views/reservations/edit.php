<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <h2>Edit Reservation</h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <label>Client</label>
        <select name="client_id" required>
            <?php foreach ($clients as $client): ?>
                <option value="<?= (int) $client['id'] ?>" <?= (int) $reservation['client_id'] === (int) $client['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($client['first_name'] . ' ' . $client['last_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Room</label>
        <select name="room_id" required>
            <?php foreach ($rooms as $room): ?>
                <option value="<?= (int) $room['id'] ?>" <?= (int) $reservation['room_id'] === (int) $room['id'] ? 'selected' : '' ?>>
                    Room <?= htmlspecialchars($room['room_number']) ?> - <?= htmlspecialchars($room['type']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Check-in</label>
        <input type="date" name="check_in" value="<?= htmlspecialchars($reservation['check_in']) ?>" required>

        <label>Check-out</label>
        <input type="date" name="check_out" value="<?= htmlspecialchars($reservation['check_out']) ?>" required>

        <label>Status</label>
        <select name="status">
            <?php foreach (['pending', 'confirmed', 'cancelled', 'completed'] as $status): ?>
                <option value="<?= $status ?>" <?= $reservation['status'] === $status ? 'selected' : '' ?>><?= ucfirst($status) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
