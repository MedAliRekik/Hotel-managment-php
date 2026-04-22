<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <h2>Edit Room</h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="form-grid">
        <label>Room Number</label>
        <input type="text" name="room_number" value="<?= htmlspecialchars($room['room_number']) ?>" required>

        <label>Type</label>
        <input type="text" name="type" value="<?= htmlspecialchars($room['type']) ?>" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars((string) $room['price']) ?>" required>

        <label>Status</label>
        <select name="status">
            <option value="available" <?= $room['status'] === 'available' ? 'selected' : '' ?>>Available</option>
            <option value="occupied" <?= $room['status'] === 'occupied' ? 'selected' : '' ?>>Occupied</option>
            <option value="maintenance" <?= $room['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
        </select>

        <?php if (!empty($room['image'])): ?>
            <p>Current image:</p>
            <img class="thumb" src="../uploads/rooms/<?= htmlspecialchars($room['image']) ?>" alt="Room">
        <?php endif; ?>

        <label>New Image</label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp">

        <button type="submit">Update</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
