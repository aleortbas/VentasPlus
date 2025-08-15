<?php
require_once '../../app/Core/Database.php';
require_once '../../app/Models/Comision.php';
require_once '../../app/Controllers/ComisionController.php';

$db = (new Database())->getConnection();
$model = new Comision($db);
$controller = new ComisionController($model);

header('Content-Type: application/json');
echo json_encode($controller->listar());
