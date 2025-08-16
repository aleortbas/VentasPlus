<?php
class DevolucionController
{
    private $devolucionModel;
    private $vendedorModel;
    private $productoModel;

    public function __construct($devolucionModel, $vendedorModel, $productoModel)
    {
        $this->devolucionModel = $devolucionModel;
        $this->vendedorModel = $vendedorModel;
        $this->productoModel = $productoModel;
    }

    /**
     * Importar devoluciones desde CSV
     */
    public function importarCSV($rutaArchivo)
    {
        if (!file_exists($rutaArchivo)) {
            die("Archivo CSV no encontrado: " . $rutaArchivo);
        }

        if (($handle = fopen($rutaArchivo, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ","); // Leer cabecera
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                list(
                    $fechaVenta,
                    $vendedorNombre,
                    $productoNombre,
                    $referencia,
                    $cantidad,
                    $valorUnitario,
                    $valorVendido,
                    $impuesto,
                    $tipoOperacion,
                    $motivo
                ) = $row;

                /* echo "[$tipoOperacion]\n"; */

                // Filtrar solo motivo "devolucion" (ignora mayúsculas/minúsculas y espacios)
                if (strcasecmp(trim($tipoOperacion), "Devolucion") !== 0) {
                    continue;
                }

                // Buscar IDs existentes
                $vendedorId = $this->vendedorModel->obtenerIdPorNombre($vendedorNombre);
                if (!$vendedorId) {
                    echo "⚠ Vendedor no encontrado: $vendedorNombre\n";
                    continue;
                }

                $productoId = $this->productoModel->obtenerIdPorNombreYReferencia($productoNombre, $referencia);
                if (!$productoId) {
                    echo "⚠ Producto no encontrado: $productoNombre ($referencia)\n";
                    continue;
                }

                // Insertar devolución
                $this->devolucionModel->insertarDevolucion(
                    $fechaVenta,
                    $vendedorId,
                    $productoId,
                    $referencia,
                    $cantidad,
                    $valorUnitario,
                    $valorVendido,
                    $impuesto,
                    $tipoOperacion,
                    $motivo
                );
            }
            fclose($handle);
        }
    }
}
