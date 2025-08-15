<?php
class ComisionController {
    private $comisionModel;

    public function __construct($comisionModel) {
        $this->comisionModel = $comisionModel;
    }

    public function listar() {
        return $this->comisionModel->obtenerComisionesMensuales();
    }
}
