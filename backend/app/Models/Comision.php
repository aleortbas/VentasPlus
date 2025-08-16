<?php
class Comision
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function obtenerComisionesMensuales()
    {
        $sql = "
            SELECT 
    vendedor, 
    anio, 
    mes,
    ROUND(SUM(ventas)) AS total_ventas,
    ROUND(SUM(ventas) * 0.05) AS comision_base,
    ROUND(CASE WHEN SUM(ventas) > 50000000 THEN SUM(ventas) * 0.02 ELSE 0 END) AS bono,
    ROUND(CASE WHEN (SUM(devoluciones)/SUM(ventas)) > 0.05 THEN SUM(ventas) * 0.01 ELSE 0 END) AS penalizacion,
    ROUND(
        (SUM(ventas) * 0.05) 
        + CASE WHEN SUM(ventas) > 50000000 THEN SUM(ventas) * 0.02 ELSE 0 END
        - CASE WHEN (SUM(devoluciones)/SUM(ventas)) > 0.05 THEN SUM(ventas) * 0.01 ELSE 0 END
    ) AS comision_final
FROM (
    SELECT 
        ven.nombre AS vendedor, 
        YEAR(v.fecha_venta) AS anio, 
        MONTH(v.fecha_venta) AS mes, 
        v.valor_vendido AS ventas, 
        0 AS devoluciones
    FROM ventas v
    JOIN vendedores ven ON ven.vendedor_id = v.vendedor_id
    UNION ALL
    SELECT 
        ven.nombre AS vendedor, 
        YEAR(d.fecha_venta) AS anio, 
        MONTH(d.fecha_venta) AS mes, 
        0 AS ventas, 
        d.valor_vendido AS devoluciones
    FROM devoluciones d
    JOIN vendedores ven ON ven.vendedor_id = d.vendedor_id
) AS t
GROUP BY vendedor, anio, mes;

        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function topCincoComision()
    {
        $sql = "
            SELECT 
                vendedor,
                ROUND(
                    comision_base +
                    bono -
                    penalizacion
                ) AS comision_final_pagar
            FROM (
                SELECT 
                    vendedor,
                    SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) AS total_ventas,
                    SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.05 AS comision_base,
                    CASE 
                        WHEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) > 50000000 
                        THEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.02
                        ELSE 0
                    END AS bono,
                    CASE 
                        WHEN (SUM(CASE WHEN tipo_operacion = 'DEVOLUCION' THEN valor_vendido ELSE 0 END) /
                              SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END)) > 0.05
                        THEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.01
                        ELSE 0
                    END AS penalizacion
                FROM (
                    -- Ventas
                    SELECT 
                        v.fecha_venta,
                        ven.nombre AS vendedor,
                        v.valor_vendido,
                        'VENTA' AS tipo_operacion
                    FROM ventas v
                    JOIN vendedores ven ON ven.vendedor_id = v.vendedor_id
                
                    UNION ALL
                
                    -- Devoluciones
                    SELECT 
                        d.fecha_venta,
                        ven.nombre AS vendedor,
                        d.valor_vendido,
                        'DEVOLUCION' AS tipo_operacion
                    FROM devoluciones d
                    JOIN vendedores ven ON ven.vendedor_id = d.vendedor_id
                ) AS operaciones
                GROUP BY vendedor
            ) AS resumen
            ORDER BY comision_final_pagar DESC
            LIMIT 5;

        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalComisionMes()
    {
        $sql = "
            SELECT 
                anio,
                mes,
                ROUND(
                    comision_base +
                    bono -
                    penalizacion
                ) AS comision_final_pagar
            FROM (
                SELECT
                    anio,
                    mes,
                    SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) AS total_ventas,
                    SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.05 AS comision_base,
                    CASE 
                        WHEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) > 50000000
                        THEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.02
                        ELSE 0
                    END AS bono,
                    CASE 
                        WHEN (SUM(CASE WHEN tipo_operacion = 'DEVOLUCION' THEN valor_vendido ELSE 0 END) /
                              SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END)) > 0.05
                        THEN SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) * 0.01
                        ELSE 0
                    END AS penalizacion
                FROM (
                    SELECT 
                        YEAR(v.fecha_venta) AS anio,
                        MONTH(v.fecha_venta) AS mes,
                        v.valor_vendido,
                        'VENTA' AS tipo_operacion
                    FROM ventas v
                    JOIN vendedores ven ON ven.vendedor_id = v.vendedor_id
                
                    UNION ALL
                
                    SELECT 
                        YEAR(d.fecha_venta) AS anio,
                        MONTH(d.fecha_venta) AS mes,
                        d.valor_vendido,
                        'DEVOLUCION' AS tipo_operacion
                    FROM devoluciones d
                    JOIN vendedores ven ON ven.vendedor_id = d.vendedor_id
                ) AS operaciones
                GROUP BY anio, mes
            ) AS resumen
            ORDER BY anio DESC, mes DESC;
 
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function porcentajeVendedoresConBono()
    {
        $sql = "
            -- Subconsulta: calcular ventas por vendedor y mes
            WITH ventas_por_vendedor AS (
                SELECT 
                    vendedor,
                    anio,
                    mes,
                    SUM(CASE WHEN tipo_operacion = 'VENTA' THEN valor_vendido ELSE 0 END) AS total_ventas
                FROM (
                    -- Ventas
                    SELECT 
                        ven.nombre AS vendedor,
                        YEAR(v.fecha_venta) AS anio,
                        MONTH(v.fecha_venta) AS mes,
                        v.valor_vendido,
                        'VENTA' AS tipo_operacion
                    FROM ventas v
                    JOIN vendedores ven ON ven.vendedor_id = v.vendedor_id

                    UNION ALL

                    -- Devoluciones
                    SELECT 
                        ven.nombre AS vendedor,
                        YEAR(d.fecha_venta) AS anio,
                        MONTH(d.fecha_venta) AS mes,
                        d.valor_vendido,
                        'DEVOLUCION' AS tipo_operacion
                    FROM devoluciones d
                    JOIN vendedores ven ON ven.vendedor_id = d.vendedor_id
                ) AS operaciones
                GROUP BY vendedor, anio, mes
            )

            -- Query principal: calcular porcentaje
            SELECT 
                anio,
                mes,
                COUNT(CASE WHEN total_ventas > 50000000 THEN 1 END) AS vendedores_con_bono,
                COUNT(*) AS total_vendedores,
                (COUNT(CASE WHEN total_ventas > 50000000 THEN 1 END) / COUNT(*)) * 100 AS porcentaje_con_bono
            FROM ventas_por_vendedor
            GROUP BY anio, mes
            ORDER BY anio, mes;
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
