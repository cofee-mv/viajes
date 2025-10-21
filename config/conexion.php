<?php
class Conexion {
    public static function conectar() {
        $host = "localhost";
        $usuario = "admin";
        $password = "clave123";
        $bd = "viajes_db";

        $conexion = new mysqli($host, $usuario, $password, $bd);

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        return $conexion;
    }
}
?>