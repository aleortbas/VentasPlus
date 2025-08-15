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

    public function obtenerIdPorNombre($nombre) {
    $sql = "SELECT vendedor_id FROM {$this->table_name} WHERE nombre = :nombre LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['vendedor_id'] : null;
}
public function consolidadoPorVendedor($vendedorId){
    $sql = "
        SELECT 
            vendedor,
            -- Totales
            SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) AS Total_de_ventas,

            -- Comisión base (5%)
            SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.05 AS comision_calculada,

            -- Bono adicional (+2% si supera 50M en ventas en el mes)
            CASE 
                WHEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) > 50000000 
                THEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.02
                ELSE 0
            END AS bono_adicional,

            -- Comisión final
            (SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.05) +
            CASE 
                WHEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) > 50000000 
                THEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.02
                ELSE 0
            END -
            CASE 
                WHEN (SUM(CASE WHEN tipo_operacion = 'DEVOLUCION' THEN valor_vendido ELSE 0 END) /
                      SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END)) > 0.05
                THEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.01
                ELSE 0
            END AS comision_final_pagar

        FROM (
            -- Ventas
            SELECT 
                v.fecha_venta,
                ven.nombre AS vendedor,
                YEAR(v.fecha_venta) AS anio,
                MONTH(v.fecha_venta) AS mes,
                v.valor_vendido,
                'VENTA' AS tipo_operacion
            FROM ventas v
            JOIN vendedores ven ON ven.vendedor_id = v.vendedor_id
            WHERE ven.vendedor_id = :vendedorId

            UNION ALL

            -- Devoluciones
            SELECT 
                d.fecha_venta,
                ven.nombre AS vendedor,
                YEAR(d.fecha_venta) AS anio,
                MONTH(d.fecha_venta) AS mes,
                d.valor_vendido,
                'DEVOLUCION' AS tipo_operacion
            FROM devoluciones d
            JOIN vendedores ven ON ven.vendedor_id = d.vendedor_id
            WHERE ven.vendedor_id = :vendedorId
        ) AS operaciones
        GROUP BY vendedor";

        $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':vendedorId', $vendedorId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
