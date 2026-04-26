<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="card auth-card">
    <h2>Client Registration</h2>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <?= Csrf::input(); ?>
        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($formData['first_name'] ?? '') ?>" required>

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($formData['last_name'] ?? '') ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>">

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>

        <label>Password</label>
        <input type="password" name="password" minlength="6" required>

        <button type="submit">Register</button>
    </form>

    <p class="hint">Already registered? <a href="index.php?controller=clientauth&action=login">Login here</a></p>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
