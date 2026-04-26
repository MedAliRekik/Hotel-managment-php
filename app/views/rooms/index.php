<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <div class="page-head">
        <h2>Rooms</h2>
        <a class="btn" href="index.php?controller=room&action=create">+ Add Room</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Room #</th>
            <th>Type</th>
            <th>Price</th>
            <th>Status</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?= (int) $room['id'] ?></td>
                <td><?= htmlspecialchars($room['room_number']) ?></td>
                <td><?= htmlspecialchars($room['type']) ?></td>
                <td>$<?= number_format((float) $room['price'], 2) ?></td>
                <td><?= htmlspecialchars($room['status']) ?></td>
                <td>
                    <?php if (!empty($room['image'])): ?>
                        <img class="thumb" src="../uploads/rooms/<?= htmlspecialchars($room['image']) ?>" alt="Room">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="index.php?controller=room&action=edit&id=<?= (int) $room['id'] ?>">Edit</a>
                    <form method="post" action="index.php?controller=room&action=delete" style="display:inline;" onsubmit="return confirm('Delete this room?')">
                        <?= Csrf::input(); ?>
                        <input type="hidden" name="id" value="<?= (int) $room['id'] ?>">
                        <button type="submit" class="danger link-button">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
