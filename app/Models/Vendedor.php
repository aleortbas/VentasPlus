<?php
class Vendedor {
    private $conn;
    private $table_name = "vendedores";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Inserta un vendedor si no existe y devuelve su ID
     */
    public function insertarSiNoExiste($nombre) {
        // 1. Buscar si ya existe
        $sql = "SELECT vendedor_id FROM {$this->table_name} WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['vendedor_id']; // Ya existe
        }

        // 2. Insertar si no existe
        $sql = "INSERT INTO {$this->table_name} (nombre) VALUES (:nombre)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->execute();

        return $this->conn->lastInsertId(); // Devolver el nuevo ID
    }

    /**
     * Obtener todos los vendedores
     */
    public function obtenerTodos() {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
