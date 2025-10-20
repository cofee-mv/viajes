<?php
require_once __DIR__ . '/../../../app/Controllers/UsuarioController.php';
require_once __DIR__ . '/../../../app/models/usuario.php';

$controller = new UsuarioController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->iniciarSesion();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login - Viajes</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form method="POST" class="bg-white p-8 rounded-xl shadow-lg w-80 space-y-4">
        <h2 class="text-2xl font-semibold text-center text-green-600">Iniciar Sesión</h2>
        <input type="correo" name="correo" placeholder="Correo" required class="border w-full p-2 rounded">
        <input type="password" name="password" placeholder="Contraseña" required class="border w-full p-2 rounded">
        <button type="submit" class="bg-green-600 text-white w-full py-2 rounded hover:bg-green-700">Entrar</button>
        <a href="?page=register" class="block text-center text-sm text-green-600 hover:underline">¿No tienes cuenta? Regístrate</a>
    </form>
</body>
</html>