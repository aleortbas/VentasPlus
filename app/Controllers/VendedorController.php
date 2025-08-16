<?php
class VendedorController {
    private $VendedorModel;

    public function __construct($VendedorModel) {
        $this->VendedorModel = $VendedorModel;
    }

    // Listar uno específico
    public function listar($id = null) {
        return $this->VendedorModel->consolidadoPorVendedor($id);
    }
}
