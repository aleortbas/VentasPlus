<?php
class Venta {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function insertarVenta($fecha, $vendedorId, $productoId, $referencia, $cantidad, $valorUnitario, $valorVendido, $impuesto) {
        $sql = "INSERT INTO ventas (fecha_venta, vendedor_id, producto, referencia, cantidad, valor_unitario, valor_vendido, impuesto)
                VALUES (:fecha, :vendedor_id, :producto, :referencia, :cantidad, :valor_unitario, :valor_vendido, :impuesto)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'fecha' => $fecha,
            'vendedor_id' => $vendedorId,
            'producto' => $productoId,
            'referencia' => $referencia,
            'cantidad' => $cantidad,
            'valor_unitario' => $valorUnitario,
            'valor_vendido' => $valorVendido,
            'impuesto' => $impuesto
        ]);
    }
}
