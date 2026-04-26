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
                    <form method="post" action="index.php?controller=client&action=delete" style="display:inline;" onsubmit="return confirm('Delete this client?')">
                        <?= Csrf::input(); ?>
                        <input type="hidden" name="id" value="<?= (int) $client['id'] ?>">
                        <button type="submit" class="danger link-button">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
