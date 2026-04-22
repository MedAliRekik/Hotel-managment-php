<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <h2>Create Room</h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="form-grid">
        <label>Room Number</label>
        <input type="text" name="room_number" required>

        <label>Type</label>
        <input type="text" name="type" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" required>

        <label>Status</label>
        <select name="status">
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="maintenance">Maintenance</option>
        </select>

        <label>Image</label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp">

        <button type="submit">Save</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
