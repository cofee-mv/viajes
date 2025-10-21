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

// Verificar que el usuario sea Administrador (rol_id = 1)
if (empty($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    echo "<script>alert('⛔ Acceso denegado. Solo administradores pueden acceder a esta página.');</script>";
    header('Location: index.php?page=reservas');
    exit;
}

// Manejar eliminación de reservas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar_reserva') {
    require_once __DIR__ . '/../../controllers/ReservaController.php';
    
    $id_reserva = $_POST['id_reserva'] ?? 0;
    
    if ($id_reserva > 0) {
        $reservaController = new ReservaController();
        if ($reservaController->eliminarReserva($id_reserva)) {
            echo "<script>alert('✅ Reserva eliminada exitosamente.');</script>";
            header('Location: index.php?page=admin');
            exit;
        } else {
            $errorEliminar = "⚠️ Error al eliminar la reserva.";
        }
    } else {
        $errorEliminar = "⚠️ ID de reserva inválido.";
    }
}

// Manejar creación de nuevo viaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear_viaje') {
    require_once __DIR__ . '/../../models/reserva.php';
    
    $origen = $_POST['origen'] ?? '';
    $destino = $_POST['destino'] ?? '';
    $fecha_salida = $_POST['fecha_salida'] ?? '';
    $fecha_regreso = $_POST['fecha_regreso'] ?? null;
    $precio = $_POST['precio'] ?? 0;

    if (!empty($origen) && !empty($destino) && !empty($fecha_salida) && !empty($precio)) {
        $reservaModel = new Reserva();
        if ($reservaModel->crearViaje($origen, $destino, $fecha_salida, $fecha_regreso, $precio)) {
            echo "<script>alert('✅ Viaje creado exitosamente.');</script>";
            header('Location: index.php?page=admin');
            exit;
        } else {
            $errorViaje = "⚠️ Error al crear el viaje.";
        }
    } else {
        $errorViaje = "⚠️ Por favor completa todos los campos obligatorios.";
    }
}

// Obtener datos para el panel admin
require_once __DIR__ . '/../../../config/conexion.php';
$conn = Conexion::conectar();

// Estadísticas básicas
$totalUsuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
$totalReservas = $conn->query("SELECT COUNT(*) as total FROM reservas")->fetch_assoc()['total'];
$totalViajes = $conn->query("SELECT COUNT(*) as total FROM viajes")->fetch_assoc()['total'];

// Obtener todos los usuarios
$queryUsuarios = "SELECT u.id_usuario, u.nombre, u.email, r.nombre_rol 
                  FROM usuarios u 
                  INNER JOIN roles r ON u.rol_id = r.id_rol 
                  ORDER BY u.id_usuario DESC";
$resultUsuarios = $conn->query($queryUsuarios);

// Obtener todas las reservas
$queryReservas = "SELECT res.id_reserva, u.nombre as usuario, v.origen, v.destino, 
                  res.num_pasajeros, res.total, res.fecha_reserva
                  FROM reservas res
                  INNER JOIN usuarios u ON res.usuario_id = u.id_usuario
                  INNER JOIN viajes v ON res.viaje_id = v.id_viaje
                  ORDER BY res.fecha_reserva DESC";
$resultReservas = $conn->query($queryReservas);

// Obtener todos los viajes
$queryViajes = "SELECT * FROM viajes ORDER BY fecha_salida DESC";
$resultViajes = $conn->query($queryViajes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Panel de Administración</title>
</head>
<body class="bg-gray-100 min-h-screen p-6 mt-16">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h1 class="text-4xl font-bold text-purple-600 mb-2">🔐 Panel de Administración</h1>
            <p class="text-gray-600">Bienvenido, <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong></p>
        </div>

        <!-- Formulario para crear nuevo viaje -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">➕ Crear Nuevo Viaje</h2>
            
            <?php if (isset($errorViaje)): ?>
                <p class="bg-red-100 text-red-600 p-3 mb-4 rounded-lg"><?= $errorViaje ?></p>
            <?php endif; ?>

            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="accion" value="crear_viaje">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Origen *</label>
                    <input 
                        type="text" 
                        name="origen" 
                        placeholder="Ciudad de origen"
                        required 
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Destino *</label>
                    <input 
                        type="text" 
                        name="destino" 
                        placeholder="Ciudad de destino"
                        required 
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Salida *</label>
                    <input 
                        type="date" 
                        name="fecha_salida" 
                        required 
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Regreso (opcional)</label>
                    <input 
                        type="date" 
                        name="fecha_regreso" 
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Precio *</label>
                    <input 
                        type="number" 
                        name="precio" 
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        required 
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>

                <div class="flex items-end">
                    <button 
                        type="submit" 
                        class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition font-semibold"
                    >
                        ✅ Crear Viaje
                    </button>
                </div>
            </form>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-500 text-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold">Total Usuarios</h3>
                <p class="text-4xl font-bold"><?= $totalUsuarios ?></p>
            </div>
            <div class="bg-green-500 text-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold">Total Reservas</h3>
                <p class="text-4xl font-bold"><?= $totalReservas ?></p>
            </div>
            <div class="bg-orange-500 text-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold">Total Viajes</h3>
                <p class="text-4xl font-bold"><?= $totalViajes ?></p>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">👥 Usuarios Registrados</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-purple-600 text-white">
                        <tr>
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Nombre</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3"><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($usuario['nombre']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($usuario['email']) ?></td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        <?= $usuario['nombre_rol'] === 'Administrador' ? 'bg-purple-200 text-purple-800' : 
                                            ($usuario['nombre_rol'] === 'Empleado' ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-800') ?>">
                                        <?= htmlspecialchars($usuario['nombre_rol']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reservas -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">✈️ Todas las Reservas</h2>
            
            <?php if (isset($errorEliminar)): ?>
                <p class="bg-red-100 text-red-600 p-3 mb-4 rounded-lg"><?= $errorEliminar ?></p>
            <?php endif; ?>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Usuario</th>
                            <th class="p-3 text-left">Origen</th>
                            <th class="p-3 text-left">Destino</th>
                            <th class="p-3 text-left">Pasajeros</th>
                            <th class="p-3 text-left">Total</th>
                            <th class="p-3 text-left">Fecha</th>
                            <th class="p-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($reserva = $resultReservas->fetch_assoc()): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3"><?= htmlspecialchars($reserva['id_reserva']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($reserva['usuario']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($reserva['origen']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($reserva['destino']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($reserva['num_pasajeros']) ?></td>
                                <td class="p-3">$<?= number_format($reserva['total'], 2, ',', '.') ?></td>
                                <td class="p-3"><?= date('d/m/Y H:i', strtotime($reserva['fecha_reserva'])) ?></td>
                                <td class="p-3 text-center">
                                    <form method="POST" onsubmit="return confirm('⚠️ ¿Estás seguro de eliminar esta reserva? Esta acción no se puede deshacer.');" style="display: inline;">
                                        <input type="hidden" name="accion" value="eliminar_reserva">
                                        <input type="hidden" name="id_reserva" value="<?= $reserva['id_reserva'] ?>">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-semibold transition">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Viajes -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">🌍 Viajes Disponibles</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-orange-600 text-white">
                        <tr>
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Origen</th>
                            <th class="p-3 text-left">Destino</th>
                            <th class="p-3 text-left">Fecha Salida</th>
                            <th class="p-3 text-left">Fecha Regreso</th>
                            <th class="p-3 text-left">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($viaje = $resultViajes->fetch_assoc()): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3"><?= htmlspecialchars($viaje['id_viaje']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($viaje['origen']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($viaje['destino']) ?></td>
                                <td class="p-3"><?= date('d/m/Y', strtotime($viaje['fecha_salida'])) ?></td>
                                <td class="p-3"><?= $viaje['fecha_regreso'] ? date('d/m/Y', strtotime($viaje['fecha_regreso'])) : 'N/A' ?></td>
                                <td class="p-3">$<?= number_format($viaje['precio'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center">
            <a href="index.php?page=reservas" class="inline-block bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                ← Volver a Reservas
            </a>
        </div>
    </div>
</body>
</html>
