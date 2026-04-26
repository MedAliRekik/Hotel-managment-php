<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <h2>Reserve Room <?= htmlspecialchars($room['room_number']) ?></h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <p><strong>Type:</strong> <?= htmlspecialchars($room['type']) ?></p>
    <p><strong>Price:</strong> $<?= number_format((float) $room['price'], 2) ?></p>

    <form method="post" class="form-grid">
        <?= Csrf::input(); ?>
        <input type="hidden" name="room_id" value="<?= (int) $room['id'] ?>">

        <label>Check-in</label>
        <input type="date" name="check_in" value="<?= htmlspecialchars($formData['check_in']) ?>" required>

        <label>Check-out</label>
        <input type="date" name="check_out" value="<?= htmlspecialchars($formData['check_out']) ?>" required>

        <button type="submit">Confirm Reservation</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
