<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $conn;
    private $table = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los usuarios
    public function obtenerUsuarios() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
