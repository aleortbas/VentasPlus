<?php
require_once '../../app/Core/Database.php';
require_once '../../app/Models/Comision.php';
require_once '../../app/Controllers/ComisionController.php';

$db = (new Database())->getConnection();
$model = new Comision($db);
$controller = new ComisionController($model);

header('Content-Type: application/json');
echo "listarComisionMensual: \n";
echo json_encode($controller->listarComisionMensual());
echo"</pre>";

echo "<pre> listarTopCincoComision: \n";
echo json_encode($controller->listarTopCincoComision());
echo"</pre>";

echo "<pre> listarTotalComisionMes: \n";
echo json_encode($controller->listarTotalComisionMes());
echo"</pre>";

echo "<pre> listarPorcentajeVendedoresConBono: \n";
echo json_encode($controller->listarPorcentajeVendedoresConBono());
echo"</pre>";
