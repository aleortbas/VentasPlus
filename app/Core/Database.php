<?php
class Database {
    private $host = "localhost";
    private $port = "3306";
    private $db_name = "infodec"; // Cambia por el nombre de tu base de datos
    private $username = "root";
    private $password = "4827"; // En XAMPP por defecto root no tiene contraseña
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            die("Error de conexión: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
