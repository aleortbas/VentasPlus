CREATE TABLE vendedores (
    vendedor_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    codigo_externo VARCHAR(50), -- si en el CSV hay un código único
    email VARCHAR(100),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE ventas (
    venta_id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_venta DATE NOT NULL,
    vendedor_id INT NOT NULL,
    producto VARCHAR(100),
    referencia VARCHAR(50),
    cantidad INT NOT NULL,
    valor_unitario DECIMAL(15,2) NOT NULL,
    valor_vendido DECIMAL(15,2) NOT NULL,
    impuesto DECIMAL(15,2) DEFAULT 0,
    FOREIGN KEY (vendedor_id) REFERENCES vendedores(vendedor_id)
);
CREATE TABLE devoluciones (
    devolucion_id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_venta DATE NOT NULL,
    vendedor_id INT NOT NULL,
    producto VARCHAR(100),
    referencia VARCHAR(50),
    cantidad INT NOT NULL,
    valor_unitario DECIMAL(15,2) NOT NULL,
    valor_vendido DECIMAL(15,2) NOT NULL,
    impuesto DECIMAL(15,2) DEFAULT 0,
    tipo_operacion VARCHAR(50),
    motivo TEXT,
    FOREIGN KEY (vendedor_id) REFERENCES vendedores(vendedor_id)
);
CREATE TABLE comisiones (
    comision_id INT AUTO_INCREMENT PRIMARY KEY,
    vendedor_id INT NOT NULL,
    anio INT NOT NULL,
    mes INT NOT NULL,
    total_ventas DECIMAL(15,2) NOT NULL,
    total_devoluciones DECIMAL(15,2) NOT NULL,
    porcentaje_base DECIMAL(5,2) NOT NULL,       -- 5.00 por el 5%
    bono DECIMAL(5,2) DEFAULT 0,                 -- 2.00 por el 2%
    penalizacion DECIMAL(5,2) DEFAULT 0,         -- -1.00 por el -1%
    porcentaje_final DECIMAL(5,2) NOT NULL,
    valor_comision DECIMAL(15,2) NOT NULL,
    fecha_calculo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendedor_id) REFERENCES vendedores(vendedor_id)
);
CREATE TABLE productos (
    producto_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    referencia VARCHAR(50),
    precio_base DECIMAL(15,2)
);
