<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <h2>Create Reservation</h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <label>Client</label>
        <select name="client_id" required>
            <option value="">Select client</option>
            <?php foreach ($clients as $client): ?>
                <option value="<?= (int) $client['id'] ?>"><?= htmlspecialchars($client['first_name'] . ' ' . $client['last_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Room</label>
        <select name="room_id" required>
            <option value="">Select room</option>
            <?php foreach ($rooms as $room): ?>
                <option value="<?= (int) $room['id'] ?>">Room <?= htmlspecialchars($room['room_number']) ?> - <?= htmlspecialchars($room['type']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Check-in</label>
        <input type="date" name="check_in" required>

        <label>Check-out</label>
        <input type="date" name="check_out" required>

        <label>Status</label>
        <select name="status">
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="cancelled">Cancelled</option>
            <option value="completed">Completed</option>
        </select>

        <button type="submit">Save</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
