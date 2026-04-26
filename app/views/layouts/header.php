<?php
require_once __DIR__ . '/../../helpers/Csrf.php';

$controller = strtolower($_GET['controller'] ?? 'dashboard');
$action = $_GET['action'] ?? 'index';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
    <h1>Hotel Management</h1>
    <?php if (!empty($_SESSION['user'])): ?>
        <div class="user-box">
            <span>Hello, <?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
            <form method="post" action="index.php?controller=auth&action=logout" style="display:inline;">
                <?= Csrf::input(); ?>
                <button type="submit" class="link-button">Logout</button>
            </form>
        </div>
    <?php elseif (!empty($_SESSION['client'])): ?>
        <div class="user-box">
            <span>Hello, <?= htmlspecialchars($_SESSION['client']['full_name']) ?></span>
            <form method="post" action="index.php?controller=clientauth&action=logout" style="display:inline;">
                <?= Csrf::input(); ?>
                <button type="submit" class="link-button">Logout</button>
            </form>
        </div>
    <?php endif; ?>
</header>

<?php if (!empty($_SESSION['user'])): ?>
<nav class="navbar">
    <a class="<?= $controller === 'dashboard' ? 'active' : '' ?>" href="index.php?controller=dashboard&action=index">Dashboard</a>
    <a class="<?= $controller === 'client' ? 'active' : '' ?>" href="index.php?controller=client&action=index">Clients</a>
    <a class="<?= $controller === 'room' ? 'active' : '' ?>" href="index.php?controller=room&action=index">Rooms</a>
    <a class="<?= $controller === 'reservation' ? 'active' : '' ?>" href="index.php?controller=reservation&action=index">Reservations</a>
</nav>
<?php elseif (!empty($_SESSION['client'])): ?>
<nav class="navbar">
    <a class="<?= $controller === 'clientportal' && $action === 'dashboard' ? 'active' : '' ?>" href="index.php?controller=clientportal&action=dashboard">Client Dashboard</a>
    <a class="<?= $controller === 'clientportal' && $action === 'rooms' ? 'active' : '' ?>" href="index.php?controller=clientportal&action=rooms">Available Rooms</a>
    <a class="<?= $controller === 'clientportal' && $action === 'myReservations' ? 'active' : '' ?>" href="index.php?controller=clientportal&action=myReservations">My Reservations</a>
</nav>
<?php endif; ?>

<main class="container">
