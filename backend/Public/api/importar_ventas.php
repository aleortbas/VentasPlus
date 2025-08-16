<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
require_once '../../app/Core/cors.php';

require_once '../../app/Core/Database.php';
require_once '../../app/Models/Venta.php';
require_once '../../app/Models/Vendedor.php';
require_once '../../app/Models/Producto.php';
require_once '../../app/Models/Devolucion.php';
require_once '../../app/Controllers/DevolucionController.php';
require_once '../../app/Controllers/VentaController.php';

header('Content-Type: application/json');

$db = (new Database())->getConnection();
$ventaModel = new Venta($db);
$vendedorModel = new Vendedor($db);
$productoModel = new Producto($db);
$controller = new VentaController($ventaModel, $vendedorModel, $productoModel);

try {
    if (!isset($_FILES['excel']) || $_FILES['excel']['error'] !== 0) {
        echo json_encode(['success' => false, 'message' => 'No se subió archivo o hubo un error']);
        exit;
    }

    $tmpName = $_FILES['excel']['tmp_name'];
    $filename = $_FILES['excel']['name'];

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $destino = $uploadDir . $filename;

    if (!move_uploaded_file($tmpName, $destino)) {
        echo json_encode(['success' => false, 'message' => 'No se pudo mover el archivo']);
        exit;
    }

    // Procesar archivo
    $result = $controller->importarCSV($destino);

    echo json_encode(['success' => true, 'result' => $result]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;