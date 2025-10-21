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

// Obtener todos los viajes disponibles
$viajesDisponibles = $controller->obtenerViajes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Mis Reservas</title>
</head>
<body class="bg-gray-100 min-h-screen p-6 mt-16">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold text-green-600 mb-6">✈️ Sistema de Reservas</h1>

        <!-- 🔔 Mostrar error si existe -->
        <?php if (isset($error)): ?>
            <p class="bg-red-100 text-red-600 p-3 mb-4 rounded-lg"><?= $error ?></p>
        <?php endif; ?>

        <!-- 🌍 VIAJES DISPONIBLES -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">🌍 Viajes Disponibles</h2>
            
            <?php if (!empty($viajesDisponibles)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($viajesDisponibles as $viaje): ?>
                        <div class="border rounded-lg p-4 hover:shadow-xl transition-shadow bg-gradient-to-br from-green-50 to-blue-50">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">
                                        <?= htmlspecialchars($viaje['origen']) ?> → <?= htmlspecialchars($viaje['destino']) ?>
                                    </h3>
                                </div>
                                <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    $<?= number_format($viaje['precio'], 0, ',', '.') ?>
                                </span>
                            </div>
                            
                            <div class="text-sm text-gray-600 space-y-1 mb-4">
                                <p>📅 Salida: <strong><?= date('d/m/Y', strtotime($viaje['fecha_salida'])) ?></strong></p>
                                <?php if ($viaje['fecha_regreso']): ?>
                                    <p>🔙 Regreso: <strong><?= date('d/m/Y', strtotime($viaje['fecha_regreso'])) ?></strong></p>
                                <?php endif; ?>
                            </div>

                            <!-- Formulario de reserva -->
                            <form method="POST" class="space-y-2">
                                <input type="hidden" name="accion" value="crear">
                                <input type="hidden" name="viaje_id" value="<?= $viaje['id_viaje'] ?>">
                                <input type="hidden" name="precio_unitario" value="<?= $viaje['precio'] ?>">
                                
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Número de pasajeros:</label>
                                    <input 
                                        type="number" 
                                        name="num_pasajeros" 
                                        min="1" 
                                        max="10" 
                                        value="1"
                                        required 
                                        class="w-full border rounded px-3 py-2 text-sm"
                                        onchange="calcularTotal(this, <?= $viaje['precio'] ?>)"
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Total a pagar:</label>
                                    <input 
                                        type="number" 
                                        name="total" 
                                        step="0.01"
                                        value="<?= $viaje['precio'] ?>"
                                        readonly 
                                        required 
                                        class="w-full border rounded px-3 py-2 text-sm bg-gray-100 font-bold"
                                    >
                                </div>
                                
                                <button 
                                    type="submit" 
                                    class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-semibold"
                                >
                                    Reservar Ahora
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">No hay viajes disponibles en este momento.</p>
            <?php endif; ?>
        </div>

        <!-- 📋 MIS RESERVAS -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">📋 Mis Reservas</h2>
            
            <?php if (!empty($reservas)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="p-3 text-left">ID</th>
                                <th class="p-3 text-left">Origen</th>
                                <th class="p-3 text-left">Destino</th>
                                <th class="p-3 text-left">Pasajeros</th>
                                <th class="p-3 text-left">Total</th>
                                <th class="p-3 text-left">Fecha Reserva</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $r): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3"><?= htmlspecialchars($r['id_reserva']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($r['origen']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($r['destino']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($r['num_pasajeros']) ?></td>
                                    <td class="p-3 font-bold text-green-600">$<?= number_format($r['total'], 2, ',', '.') ?></td>
                                    <td class="p-3 text-sm text-gray-600">
                                        <?= date('d/m/Y H:i', strtotime($r['fecha_reserva'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">No tienes reservas registradas aún. ¡Reserva tu primer viaje arriba!</p>
            <?php endif; ?>
        </div>

        <div class="mt-6 text-center">
            <a href="index.php?page=logout" class="text-red-500 hover:underline">Cerrar sesión</a>
        </div>
    </div>

    <script>
        function calcularTotal(input, precioUnitario) {
            const numPasajeros = parseInt(input.value) || 1;
            const total = numPasajeros * precioUnitario;
            const totalInput = input.closest('form').querySelector('input[name="total"]');
            totalInput.value = total.toFixed(2);
        }
    </script>
</body>
</html>