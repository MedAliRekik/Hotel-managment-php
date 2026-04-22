<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card auth-card">
    <h2>Admin Login</h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p class="hint">Demo account: admin@hotel.com / admin123</p>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
