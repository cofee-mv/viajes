<?php
require_once __DIR__ . '/../app/models/Usuario.php';

$usuario = new Usuario();
$resultado = $usuario->obtenerUsuarios();

while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
    echo "👤 " . $fila['nombre'] . " - " . $fila['correo'] . "<br>";
}
?>
