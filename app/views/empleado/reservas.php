<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?page=login');
    exit;
}

// Verificar que el usuario sea Empleado (rol_id = 2)
if (empty($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2) {
    echo "<script>alert('⛔ Acceso denegado. Solo empleados pueden acceder a esta página.');</script>";
    header('Location: index.php?page=reservas');
    exit;
}

// Obtener todas las reservas
require_once __DIR__ . '/../../../config/conexion.php';
$conn = Conexion::conectar();

// Estadísticas básicas
$totalReservas = $conn->query("SELECT COUNT(*) as total FROM reservas")->fetch_assoc()['total'];
$totalPasajeros = $conn->query("SELECT SUM(num_pasajeros) as total FROM reservas")->fetch_assoc()['total'];
$ingresoTotal = $conn->query("SELECT SUM(total) as total FROM reservas")->fetch_assoc()['total'];

// Obtener todas las reservas con información detallada
$queryReservas = "SELECT 
                    res.id_reserva, 
                    u.nombre as usuario, 
                    u.email,
                    v.origen, 
                    v.destino,
                    v.fecha_salida,
                    v.fecha_regreso,
                    res.num_pasajeros, 
                    res.total, 
                    res.fecha_reserva
                  FROM reservas res
                  INNER JOIN usuarios u ON res.usuario_id = u.id_usuario
                  INNER JOIN viajes v ON res.viaje_id = v.id_viaje
                  ORDER BY res.fecha_reserva DESC";
$resultReservas = $conn->query($queryReservas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Panel de Empleado - Reservas</title>
</head>
<body class="bg-gray-100 min-h-screen p-6 mt-16">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h1 class="text-4xl font-bold text-blue-600 mb-2">👔 Panel de Empleado</h1>
            <p class="text-gray-600">Bienvenido, <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong></p>
            <p class="text-sm text-gray-500 mt-1">Vista de solo lectura - Todas las reservas del sistema</p>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-500 text-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold">Total Reservas</h3>
                <p class="text-4xl font-bold"><?= $totalReservas ?></p>
            </div>
            <div class="bg-green-500 text-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold">Total Pasajeros</h3>
                <p class="text-4xl font-bold"><?= $totalPasajeros ?? 0 ?></p>
            </div>
            <div class="bg-orange-500 text-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold">Ingresos Totales</h3>
                <p class="text-4xl font-bold">$<?= number_format($ingresoTotal ?? 0, 2, ',', '.') ?></p>
            </div>
        </div>

        <!-- Todas las Reservas -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">📋 Todas las Reservas del Sistema</h2>
            
            <?php if ($resultReservas->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="p-3 text-left">ID</th>
                                <th class="p-3 text-left">Cliente</th>
                                <th class="p-3 text-left">Email</th>
                                <th class="p-3 text-left">Ruta</th>
                                <th class="p-3 text-left">Fecha Viaje</th>
                                <th class="p-3 text-left">Pasajeros</th>
                                <th class="p-3 text-left">Total</th>
                                <th class="p-3 text-left">Fecha Reserva</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($reserva = $resultReservas->fetch_assoc()): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-semibold text-blue-600">#<?= htmlspecialchars($reserva['id_reserva']) ?></td>
                                    <td class="p-3"><?= htmlspecialchars($reserva['usuario']) ?></td>
                                    <td class="p-3 text-sm text-gray-600"><?= htmlspecialchars($reserva['email']) ?></td>
                                    <td class="p-3">
                                        <span class="font-semibold"><?= htmlspecialchars($reserva['origen']) ?></span>
                                        →
                                        <span class="font-semibold"><?= htmlspecialchars($reserva['destino']) ?></span>
                                    </td>
                                    <td class="p-3 text-sm">
                                        <div><?= date('d/m/Y', strtotime($reserva['fecha_salida'])) ?></div>
                                        <?php if ($reserva['fecha_regreso']): ?>
                                            <div class="text-gray-500 text-xs">Regreso: <?= date('d/m/Y', strtotime($reserva['fecha_regreso'])) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 text-center">
                                        <span class="bg-gray-200 px-2 py-1 rounded-full text-sm font-semibold">
                                            <?= htmlspecialchars($reserva['num_pasajeros']) ?> 👤
                                        </span>
                                    </td>
                                    <td class="p-3 font-bold text-green-600">$<?= number_format($reserva['total'], 2, ',', '.') ?></td>
                                    <td class="p-3 text-sm text-gray-600">
                                        <?= date('d/m/Y H:i', strtotime($reserva['fecha_reserva'])) ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">No hay reservas registradas en el sistema.</p>
            <?php endif; ?>
        </div>

        <div class="mt-6 text-center space-x-4">
            <a href="index.php?page=reservas" class="text-blue-600 hover:underline">← Volver a Mis Reservas</a>
            <span class="text-gray-400">|</span>
            <a href="index.php?page=logout" class="text-red-500 hover:underline">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>
