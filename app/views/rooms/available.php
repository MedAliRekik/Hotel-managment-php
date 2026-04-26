<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <div class="page-head">
        <h2>Available Rooms</h2>
    </div>

    <table>
        <thead>
        <tr>
            <th>Room #</th>
            <th>Type</th>
            <th>Price</th>
            <th>Status</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rooms as $room): ?>
            <tr>
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
                    <a class="btn" href="index.php?controller=clientportal&action=reserve&room_id=<?= (int) $room['id'] ?>">Reserve</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
