<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card auth-card">
    <h2>Client Login</h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <?= Csrf::input(); ?>
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p class="hint">No account yet? <a href="index.php?controller=clientauth&action=register">Register here</a></p>
    <p class="hint">Admin? <a href="index.php?controller=auth&action=login">Go to admin login</a></p>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
