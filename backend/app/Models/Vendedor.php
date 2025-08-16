<?php
class Vendedor
{
    private $conn;
    private $table_name = "vendedores";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Inserta un vendedor si no existe y devuelve su ID
     */
    public function insertarSiNoExiste($nombre)
    {
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
    public function obtenerTodos()
    {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerIdPorNombre($nombre)
    {
        $sql = "SELECT vendedor_id,nombre FROM {$this->table_name} WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    
    public function consolidadoPorVendedor($vendedorId)
    {
        $sql = "
            SELECT 
                vendedor, 
                anio, 
                mes,
                SUM(ventas) AS total_ventas,
                ROUND(SUM(ventas) * 0.05) AS comision_base,
                CASE WHEN SUM(ventas) > 50000000 THEN SUM(ventas) * 0.02 ELSE 0 END AS bono,
                CASE WHEN (SUM(devoluciones)/SUM(ventas)) > 0.05 THEN SUM(ventas) * 0.01 ELSE 0 END AS penalizacion,
                (SUM(ventas) * 0.05) 
                + CASE WHEN SUM(ventas) > 50000000 THEN SUM(ventas) * 0.02 ELSE 0 END
                - CASE WHEN (SUM(devoluciones)/SUM(ventas)) > 0.05 THEN SUM(ventas) * 0.01 ELSE 0 END AS comision_final
            FROM (
                SELECT 
                    ven.nombre AS vendedor, 
                    YEAR(v.fecha_venta) AS anio, 
                    MONTH(v.fecha_venta) AS mes, 
                    v.valor_vendido AS ventas, 
                    0 AS devoluciones
                FROM ventas v
                JOIN vendedores ven ON ven.vendedor_id = v.vendedor_id
                WHERE v.vendedor_id = :vendedorId

                UNION ALL

                SELECT 
                    ven.nombre AS vendedor, 
                    YEAR(d.fecha_venta) AS anio, 
                    MONTH(d.fecha_venta) AS mes, 
                    0 AS ventas, 
                    d.valor_vendido AS devoluciones
                FROM devoluciones d
                JOIN vendedores ven ON ven.vendedor_id = d.vendedor_id
                WHERE d.vendedor_id = :vendedorId
            ) AS t
            GROUP BY vendedor, anio, mes
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':vendedorId', $vendedorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
