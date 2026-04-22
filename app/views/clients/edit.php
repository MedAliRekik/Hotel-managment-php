<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <h2>Edit Client</h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($client['first_name']) ?>" required>

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($client['last_name']) ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars((string) $client['phone']) ?>">

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars((string) $client['email']) ?>">

        <button type="submit">Update</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
