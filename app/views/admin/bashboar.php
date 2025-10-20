<?php
require_once _DIR_ . '/../../helpers/auth.php';
verificarRol(['admin']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?> (Administrador)</h1>
    <p>Aquí puedes gestionar usuarios, reservas, auditoría, etc.</p>
</body>
</html>