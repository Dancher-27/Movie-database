<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Wishlist";

// Maak een databaseverbinding
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>


