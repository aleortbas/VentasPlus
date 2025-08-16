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