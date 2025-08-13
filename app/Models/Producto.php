<?php
class Producto {
    private $conn;
    private $table_name = "productos";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Inserta un vendedor si no existe y devuelve su ID
     */
    public function insertarSiNoExiste($nombre,$referencia,$valorUnitario) {
        // 1. Buscar si ya existe
        $sql = "SELECT producto_id FROM {$this->table_name} WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['producto_id']; // Ya existe
        }

        // 2. Insertar si no existe
        $sql = "INSERT INTO productos (nombre,referencia,precio_base)
                VALUES (:nombre, :referencia, :precio_base)";
        echo"<pre> valorUnitario";
        print_r($valorUnitario);
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'referencia' => $referencia,
            'precio_base' => $valorUnitario
        ]);

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

    public function obtenerIdPorNombreYReferencia($nombre, $referencia) {
    $sql = "SELECT producto_id FROM {$this->table_name} 
            WHERE nombre = :nombre AND referencia = :referencia LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":referencia", $referencia);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['producto_id'] : null;
}

}
