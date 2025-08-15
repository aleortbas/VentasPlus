SELECT 
     anio,
    mes,
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
) AS operaciones
GROUP BY  anio,mes
ORDER BY  anio,mes DESC 