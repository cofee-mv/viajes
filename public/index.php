<!DOCTYPE html>
<link rel="icon" type="image/x-icon" href="../img/favicon_viajes.png">
<?php
require __DIR__ . '/../app/views/layout/header.php';
ini_set('display_errors', 0); 
 ini_set('display_startup_errors', 0);
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

    case 'admin':
        include '../app/views/admin/panel.php';
        break;

    case 'empleado':
        include '../app/views/empleado/reservas.php';
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