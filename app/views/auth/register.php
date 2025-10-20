<?php
require_once __DIR__. '/../../../app/Controllers/UsuarioController.php';
$controller = new UsuarioController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->registrarUsuario();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">Crear cuenta</h2>

        <!-- ✅ Asegura que el formulario apunte al router principal -->
        <form action="index.php?page=register" method="POST" class="space-y-4">

            <input type="text" name="nombre" placeholder="Nombre completo" required
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">

            <input type="email" name="correo" placeholder="Correo electrónico" required
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">

            <input type="password" name="password" placeholder="Contraseña" required
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">

            <!-- ✅ Lista de roles -->
            <select name="rol" required
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                <option value="Cliente" selected>Cliente</option>
                <option value="Empleado">Empleado</option>
                <option value="Administrador">Administrador</option>
            </select>

            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                Registrarse
            </button>

            <a href="index.php?page=login"
                class="block text-center text-sm text-blue-600 hover:underline">
                ¿Ya tienes cuenta? Inicia sesión



        
            <a href="index.php?page=login"
                class="block text-center w-full mt-2 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400 transition">
                ← Volver al inicio de sesión
            </a>
        </form>
    </div>

</body>
</html>