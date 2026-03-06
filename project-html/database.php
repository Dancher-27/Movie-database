<?php
class Database {
    private $host = "localhost";
    private $db_name = "wishlist";
    private $username = "root"; // Pas aan met je DB-gebruikersnaam
    private $password = ""; // Pas aan met je DB-wachtwoord
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
