<?php
class ComisionController {
    private $comisionModel;

    public function __construct($comisionModel) {
        $this->comisionModel = $comisionModel;
    }

    public function listarComisionMensual() {
        return $this->comisionModel->obtenerComisionesMensuales();
    }

    public function listarTopCincoComision() {
        return $this->comisionModel->topCincoComision();
    }

    public function listarTotalComisionMes(){
        return $this->comisionModel->totalComisionMes();
    }

    public function listarPorcentajeVendedoresConBono(){
        return $this->comisionModel->porcentajeVendedoresConBono();
    }
}
