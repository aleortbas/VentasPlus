<?php
class VendedorController {
    private $VendedorModel;

    public function __construct($VendedorModel) {
        $this->VendedorModel = $VendedorModel;
    }

    // Listar uno específico
    public function consolidadoPorVendedor($id = null) {
        return $this->VendedorModel->consolidadoPorVendedor($id);
    }

    public function listarTodos() {
        return $this->VendedorModel->obtenerTodos();
    }

    public function obtenerIdPorNombre($nombre = null) {
        return $this->VendedorModel->obtenerIdPorNombre($nombre);
    }
}
