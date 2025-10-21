<?php
require_once __DIR__ . '/../../config/conexion.php';

class Reserva {
    private $conexion;

    public function __construct() {
       
        $this->conexion = Conexion::conectar();
    }

    //  Crear una reserva
    public function crear($usuario_id, $viaje_id, $num_pasajeros, $total) {
        $sql = "INSERT INTO reservas (usuario_id, viaje_id, num_pasajeros, total) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iiid", $usuario_id, $viaje_id, $num_pasajeros, $total);
        return $stmt->execute();
    }

    //  Listar reservas de un usuario
    public function listarPorUsuario($usuario_id) {
        $sql = "SELECT r.*, v.origen, v.destino 
                FROM reservas r
                JOIN viajes v ON r.viaje_id = v.id_viaje
                WHERE r.usuario_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    //  Obtener todos los viajes disponibles
    public function obtenerTodosLosViajes() {
        $sql = "SELECT * FROM viajes ORDER BY fecha_salida ASC";
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    //  Crear un nuevo viaje (solo admin)
    public function crearViaje($origen, $destino, $fecha_salida, $fecha_regreso, $precio) {
        $sql = "INSERT INTO viajes (origen, destino, fecha_salida, fecha_regreso, precio) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssd", $origen, $destino, $fecha_salida, $fecha_regreso, $precio);
        return $stmt->execute();
    }
}
?>