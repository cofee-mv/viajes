<?php

require_once __DIR__ . '/../../config/conexion.php';

class Usuario {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function registrarUsuario($nombre, $correo, $password_hash, $rol) {
    
        $sqlRol = "SELECT id_rol FROM roles WHERE nombre_rol = ?";
        $stmtRol = $this->conexion->prepare($sqlRol);
        $stmtRol->bind_param("s", $rol);
        $stmtRol->execute();
        $resultadoRol = $stmtRol->get_result();
        $rol_id = $resultadoRol->fetch_assoc()['id_rol'] ?? 3; // Por defecto Cliente

        $sqlVerificar = "SELECT id_usuario FROM usuarios WHERE email = ?";
        $stmtVerificar = $this->conexion->prepare($sqlVerificar);
        $stmtVerificar->bind_param("s", $correo);
        $stmtVerificar->execute();
        $resultado = $stmtVerificar->get_result();

        if ($resultado->num_rows > 0) {
            echo "<script>alert('❌ Este correo ya está registrado. Intenta iniciar sesión.');</script>";
            return false;
        }

        
        $sql = "INSERT INTO usuarios (nombre, email, password_hash, rol_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $correo, $password_hash, $rol_id);

        if ($stmt->execute()) {
            echo "<script>
                alert('✅ Registro exitoso. Ahora puedes iniciar sesión.');
                window.location.href = 'index.php?page=login';
            </script>";
            return true;
        } else {
            echo "<script>alert('⚠️ Error al registrar el usuario.');</script>";
            return false;
        }
    }

    public function obtenerUsuarioPorCorreo($correo) {
        $sql = "SELECT 
                    u.id_usuario AS id, 
                    u.nombre, 
                    u.email, 
                    u.password_hash AS password, 
                    r.nombre_rol AS rol
                FROM usuarios u
                INNER JOIN roles r ON u.rol_id = r.id_rol
                WHERE u.email = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
}
?>