<?php
require_once '../../app/Core/Database.php';
require_once '../../app/Models/Vendedor.php';
require_once '../../app/Controllers/VendedorController.php';

$db = (new Database())->getConnection();
$model = new Vendedor($db);
$controller = new VendedorController($model);

// Obtener ID por query string ?id=3
//$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$id = 41;

header('Content-Type: application/json');
echo json_encode($controller->listar($id));
