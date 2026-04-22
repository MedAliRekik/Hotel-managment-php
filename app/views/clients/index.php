<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <div class="page-head">
        <h2>Clients</h2>
        <a class="btn" href="index.php?controller=client&action=create">+ Add Client</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= (int) $client['id'] ?></td>
                <td><?= htmlspecialchars($client['first_name']) ?></td>
                <td><?= htmlspecialchars($client['last_name']) ?></td>
                <td><?= htmlspecialchars((string) $client['phone']) ?></td>
                <td><?= htmlspecialchars((string) $client['email']) ?></td>
                <td>
                    <a href="index.php?controller=client&action=edit&id=<?= (int) $client['id'] ?>">Edit</a>
                    <a class="danger" onclick="return confirm('Delete this client?')" href="index.php?controller=client&action=delete&id=<?= (int) $client['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
