<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <h2>Create Client</h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <label>First Name</label>
        <input type="text" name="first_name" required>

        <label>Last Name</label>
        <input type="text" name="last_name" required>

        <label>Phone</label>
        <input type="text" name="phone">

        <label>Email</label>
        <input type="email" name="email">

        <button type="submit">Save</button>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
