<?php
require_once __DIR__ . '/../models/reserva.php';

class ReservaController {

    // 🔹 Listar reservas del usuario actual
    public function listarReservas($usuario_id) {
        $reservaModel = new Reserva();
        return $reservaModel->listarPorUsuario($usuario_id);
    }

    // 🔹 Obtener todos los viajes disponibles
    public function obtenerViajes() {
        $reservaModel = new Reserva();
        return $reservaModel->obtenerTodosLosViajes();
    }

    // 🔹 Crear nueva reserva
    public function crearReserva() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario_id = $_SESSION['usuario_id'] ?? null;
            $viaje_id = $_POST['viaje_id'] ?? '';
            $num_pasajeros = $_POST['num_pasajeros'] ?? '';
            $total = $_POST['total'] ?? '';

            if ($usuario_id && $viaje_id && $num_pasajeros && $total) {
                $reservaModel = new Reserva();
                $reservaModel->crear($usuario_id, $viaje_id, $num_pasajeros, $total);
                echo "<script>alert('✅ Reserva creada correctamente');</script>";
            } else {
                echo "<script>alert('⚠️ Completa todos los campos');</script>";
            }
        }
    }
}
?>