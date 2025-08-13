<?php
require_once '../../app/Core/Database.php';
require_once '../../app/Models/Venta.php';
require_once '../../app/Models/Vendedor.php';
require_once '../../app/Models/Producto.php';
require_once '../../app/Models/Devolucion.php';
require_once '../../app/Controllers/DevolucionController.php';
require_once '../../app/Controllers/VentaController.php';

$db = (new Database())->getConnection();

$ventaModel = new Venta($db);
$vendedorModel = new Vendedor($db);
$productoModel = new Producto($db);
$devolucionModel = new Devolucion($db);

$controller = new VentaController($ventaModel, $vendedorModel, $productoModel);
$controller->importarCSV('C:/Users/aleor/Downloads/ventas_ejemplo_junio_julio.csv');

$controller = new DevolucionController($devolucionModel, $vendedorModel, $productoModel);
$controller->importarCSV('C:/Users/aleor/Downloads/ventas_con_devoluciones.csv');