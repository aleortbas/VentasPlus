<?php
class Comision {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerComisionesMensuales() {
        $sql = "
            SELECT vendedor, anio, mes,
                   SUM(ventas) AS total_ventas,
                   SUM(ventas) * 0.05 AS comision_base,
                   CASE WHEN SUM(ventas) > 50000000 THEN SUM(ventas) * 0.02 ELSE 0 END AS bono,
                   CASE WHEN (SUM(devoluciones)/SUM(ventas)) > 0.05 THEN SUM(ventas) * 0.01 ELSE 0 END AS penalizacion,
                   (SUM(ventas) * 0.05) 
                   + CASE WHEN SUM(ventas) > 50000000 THEN SUM(ventas) * 0.02 ELSE 0 END
                   - CASE WHEN (SUM(devoluciones)/SUM(ventas)) > 0.05 THEN SUM(ventas) * 0.01 ELSE 0 END AS comision_final
            FROM (
                SELECT ven.nombre vendedor, YEAR(v.fecha_venta) anio, MONTH(v.fecha_venta) mes, v.valor_vendido ventas, 0 devoluciones
                FROM ventas v
                JOIN vendedores ven ON ven.vendedor_id = v.vendedor_id
                UNION ALL
                SELECT ven.nombre vendedor, YEAR(d.fecha_venta) anio, MONTH(d.fecha_venta) mes, 0 ventas, d.valor_vendido devoluciones
                FROM devoluciones d
                JOIN vendedores ven ON ven.vendedor_id = d.vendedor_id
            ) AS t
            GROUP BY vendedor, anio, mes
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
