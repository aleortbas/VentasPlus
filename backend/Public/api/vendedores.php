<?php
require_once '../../app/Core/cors.php';

require_once '../../app/Core/Database.php';
require_once '../../app/Models/Vendedor.php';
require_once '../../app/Controllers/VendedorController.php';

$db = (new Database())->getConnection();
$model = new Vendedor($db);
$controller = new VendedorController($model);

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$vendedorId = isset($data['vendedorId']) && $data['vendedorId'] !== '' ? intval($data['vendedorId']) : null;
$nombre = isset($data['nombre']) ? trim($data['nombre']) : null; // acepta cadena vacía


header('Content-Type: application/json');

$response = [
    'listarTodos' => $controller->listarTodos()
];

// Si llega vendedorId, usar consolidadoPorVendedor por ID
if (!empty($vendedorId)) {
    $response['consolidadoPorVendedor'] = $controller->consolidadoPorVendedor($vendedorId);
} 
// Si llega nombre, usar obtenerIdPorNombre por nombre
elseif (!empty($nombre)) {
    $response['obtenerIdPorNombre'] = $controller->obtenerIdPorNombre($nombre);
} 
// Si no llega ninguno, devolver null o mensaje
else {
    $response['obtenerIdPorNombreNull'] = null;
}

echo json_encode($response);

