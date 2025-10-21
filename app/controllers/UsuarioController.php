<?php
require_once __DIR__ . '/../models/usuario.php';

class UsuarioController {

    // 👉 Registrar usuario nuevo
    public function registrarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol = 'Cliente'; // Rol por defecto para todos los nuevos usuarios

            if (!empty($nombre) && !empty($correo) && !empty($password)) {
                $usuario = new Usuario();
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $resultado = $usuario->registrarUsuario($nombre, $correo, $hash, $rol);

                if ($resultado) {
                    echo "<script>
                        alert('Registro exitoso. Ahora puedes iniciar sesión.');
                        window.location.href = 'index.php?page=login';
                    </script>";
                } else {
                    echo "<script>alert('Error al registrar usuario.');</script>";
                }
            } else {
                echo "<script>alert('Por favor completa todos los campos.');</script>";
            }
        }
    }

    // 👉 Iniciar sesión (login)
    public function iniciarSesion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($correo) && !empty($password)) {
                $usuario = new Usuario();
                $userData = $usuario->obtenerUsuarioPorCorreo($correo);

                if ($userData && password_verify($password, $userData['password'])) {
                    // Guardar datos en sesión
                    $_SESSION['usuario_id'] = $userData['id'];
                    $_SESSION['nombre'] = $userData['nombre'];
                    $_SESSION['rol_id'] = $userData['rol_id'];
                    $_SESSION['rol'] = $userData['rol'];

                    // Redirigir según rol
                    if ($userData['rol_id'] == 1) {
                        header('Location: index.php?page=admin');
                    } elseif ($userData['rol_id'] == 2) {
                        header('Location: index.php?page=empleado');
                    } else {
                        header('Location: index.php?page=reservas');
                    }
                    exit;
                } else {
                    echo "<script>alert('Correo o contraseña incorrectos.');</script>";
                }
            } else {
                echo "<script>alert('Completa todos los campos.');</script>";
            }
        }
    }

    // 👉 Cerrar sesión
    public function cerrarSesion() {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
?>