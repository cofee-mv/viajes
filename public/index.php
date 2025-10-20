<?php
session_start();

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        include '../app/views/auth/login.php';
        break;

    case 'register':
        include '../app/views/auth/register.php';
        break;

    case 'reservas':
        include '../app/views/reservas/index.php';
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?page=login');
        exit;

    default:
        include '../app/views/auth/login.php';
        break;
}
?>