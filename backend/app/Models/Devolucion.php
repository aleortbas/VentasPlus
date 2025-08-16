<?php
class Devolucion {
    private $conn;
    private $table_name = "devoluciones";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertarDevolucion($fecha, $vendedorId, $productoId, $referencia, $cantidad, $valorUnitario, $valorVendido, $impuesto, $tipoOperacion, $motivo) {
        $sql = "INSERT INTO {$this->table_name}
                (fecha_venta, vendedor_id, producto, referencia, cantidad, valor_unitario, valor_vendido, impuesto, tipo_operacion, motivo)
                VALUES
                (:fecha, :vendedor_id, :producto, :referencia, :cantidad, :valor_unitario, :valor_vendido, :impuesto, :tipo_operacion, :motivo)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'fecha' => $fecha,
            'vendedor_id' => $vendedorId,
            'producto' => $productoId,
            'referencia' => $referencia,
            'cantidad' => $cantidad,
            'valor_unitario' => $valorUnitario,
            'valor_vendido' => $valorVendido,
            'impuesto' => $impuesto,
            'tipo_operacion' => $tipoOperacion,
            'motivo' => $motivo
        ]);
    }
}
