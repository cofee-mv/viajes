<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?page=login');
    exit;
}


require_once __DIR__ . '/../../../app/controllers/ReservaController.php';


$controller = new ReservaController();

// ✅ Si se envió el formulario, crear la reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $usuario_id = $_SESSION['usuario_id'];
    $viaje_id = $_POST['viaje_id'];
    $num_pasajeros = $_POST['num_pasajeros'];
    $total = $_POST['total'];

    // Validar campos
    if (!empty($viaje_id) && !empty($num_pasajeros) && !empty($total)) {
        $controller->crearReserva($usuario_id, $viaje_id, $num_pasajeros, $total);
        // 🔄 Redirigir para evitar reenvío del formulario
        header('Location: index.php?page=reservas');
        exit;
    } else {
        $error = "⚠️ Por favor completa todos los campos.";
    }
}

// Obtener las reservas del usuario
$reservas = $controller->listarReservas($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Mis Reservas</title>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-3xl font-bold text-green-600 mb-4">✈️ Mis Reservas</h1>

        <!-- 🔔 Mostrar error si existe -->
        <?php if (isset($error)): ?>
            <p class="bg-red-100 text-red-600 p-2 mb-4 rounded"><?= $error ?></p>
        <?php endif; ?>

        <!-- 📝 Formulario para crear nueva reserva -->
        <form method="POST" action="" class="space-y-3 mb-6">
            <h2 class="text-lg font-semibold text-gray-700">Crear nueva reserva</h2>
            <input type="hidden" name="accion" value="crear">
            <input type="number" name="viaje_id" placeholder="ID del viaje" required class="border p-2 rounded w-full">
            <input type="number" name="num_pasajeros" placeholder="Número de pasajeros" required class="border p-2 rounded w-full">
            <input type="number" step="0.01" name="total" placeholder="Total ($)" required class="border p-2 rounded w-full">
            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Reservar</button>
        </form>

        <!-- 📋 Tabla de reservas -->
        <table class="w-full border-collapse">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Origen</th>
                    <th class="p-2 border">Destino</th>
                    <th class="p-2 border">Pasajeros</th>
                    <th class="p-2 border">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservas)): ?>
                    <?php foreach ($reservas as $r): ?>
                        <tr class="text-center border-b">
                            <td class="p-2"><?= htmlspecialchars($r['id_reserva']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($r['origen']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($r['destino']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($r['num_pasajeros']) ?></td>
                            <td class="p-2">$<?= number_format($r['total'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">No tienes reservas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="index.php?page=logout" class="block text-center mt-6 text-red-500 hover:underline">Cerrar sesión</a>
    </div>
</body>
</html>