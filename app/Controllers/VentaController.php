<?php
class VentaController {
    private $ventaModel;
    private $vendedorModel;
    private $productoModel;

    public function __construct($ventaModel, $vendedorModel, $productoModel) {
        $this->ventaModel = $ventaModel;
        $this->vendedorModel = $vendedorModel;
        $this->productoModel = $productoModel;
    }

    public function importarCSV($rutaArchivo) {
        if (!file_exists($rutaArchivo)) {
            die("Archivo no encontrado");
        }

        if (($handle = fopen($rutaArchivo, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ","); // leer cabecera
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                list($fechaVenta, $vendedor, $producto, $referencia, $cantidad, $valorUnitario, $valorVendido, $impuesto) = $row;
                
                // 1. Insertar vendedor si no existe
                $vendedorId = $this->vendedorModel->insertarSiNoExiste($vendedor);
                
                // 2. Insertar producto si no existe
                $productoId = $this->productoModel->insertarSiNoExiste($producto, $referencia, $valorUnitario);

                // 3. Insertar venta
                $this->ventaModel->insertarVenta($fechaVenta, $vendedorId, $productoId, $referencia, $cantidad, $valorUnitario, $valorVendido, $impuesto);
            }
            fclose($handle);
        }
    }
}
