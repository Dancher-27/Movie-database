<?php

class Media {
    private $conn;

    // Constructor: Initialiseer de databaseverbinding
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getMediaBySection($section) {
        $sql = "SELECT id, title, image, rating, category, section FROM media WHERE section = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $section);
        $stmt->execute();
        $result = $stmt->get_result();
        $mediaItems = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return $mediaItems;
    }
    

    // Functie om een nieuw item toe te voegen
    public function addMedia($title, $year, $genre, $plot, $image, $section, $rating = null) {
        $sql = "INSERT INTO media (title, year, genre, plot, image, section, rating) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", $title, $year, $genre, $plot, $image, $section, $rating);
        $stmt->execute();
        $stmt->close();
    }

    // Functie om een item te updaten
    public function updateMedia($id, $title, $year, $genre, $plot, $image, $section, $rating = null) {
        $sql = "UPDATE media SET title = ?, year = ?, genre = ?, plot = ?, image = ?, section = ?, rating = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssii", $title, $year, $genre, $plot, $image, $section, $rating, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Functie om een item te verwijderen
    public function deleteMedia($id) {
        $sql = "DELETE FROM media WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    // Functie om de rating van een item te updaten
    public function updateRating($id, $newRating) {
        $sql = "UPDATE media SET rating = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("di", $newRating, $id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
