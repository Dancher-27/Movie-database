<?php
require_once 'connection.php'; // Zorg dat dit pad klopt

class User {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Registreren van een nieuwe gebruiker
    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO account (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        return $stmt->execute();
    }

    // Inloggen van een gebruiker
    public function login($email, $password) {
        $sql = "SELECT * FROM account WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user; // Retourneer gebruikersgegevens bij succesvolle login
            }
        }
        return false; // Login mislukt
    }

    // Ophalen van gebruikersgegevens op basis van ID
    public function getUserById($id) {
        $sql = "SELECT id, username, email FROM account WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>


