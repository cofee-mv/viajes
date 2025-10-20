<?php
session_start();

function verificarRol($rolesPermitidos = []) {
    if (!isset($_SESSION['rol'])) {
        header("Location: /app/views/auth/login.php");
        exit();
    }

    if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
        echo "<script>alert('Acceso denegado'); window.history.back();</script>";
        exit();
    }
}
?>