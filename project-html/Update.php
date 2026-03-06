<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px 20px;
        }
        .navbar a {
            color: white;
            text-align: center;
            padding: 12px 20px;
            text-decoration: none;
            display: inline-block;
            font-size: 18px;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="number"],
        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #333;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="add_movie.php">Create</a>
    <a href="index.php">Read</a>
    <a href="update.php">Update</a>
    <a href="delete.php">Delete</a>
    <a href="search.php">Search</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h1>Update Item</h1>

    <?php
    if (isset($message)) {
        echo "<div class='message $messageType'>$message</div>";
    }
    ?>

    <form action="update.php" method="POST" enctype="multipart/form-data">
        <label for="id">Item ID:</label>
        <input type="number" name="id" id="id" required><br><br>

        <label for="title">Title:</label>
        <input type="text" name="title" id="title"><br><br>

        <label for="category">Category:</label>
        <input type="text" name="category" id="category"><br><br>

        <label for="rating">Rating:</label>
        <input type="number" name="rating" id="rating"><br><br>

        <label for="sections">Sections:</label>
        <input type="text" name="sections" id="sections"><br><br>

        <label for="image">Image:</label>
        <input type="file" name="image" id="image"><br><br>

        <button type="submit">Update Item</button>
    </form>
</div>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wishlist";  // Zorg ervoor dat dit overeenkomt met je database naam

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Het ophalen van de formuliervelden
    $id = intval($_POST['id']);
    $title = $_POST['title'] ?? null;
    $category = $_POST['category'] ?? null;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
    $sections = $_POST['section'] ?? null;
    
    // Afbeelding verwerken (indien geüpload)
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Pad voor het uploaden van de afbeelding
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Controleren of het een geldige afbeelding is
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Bestand verplaatsen naar de servermap
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image = basename($_FILES['image']['name']);  // Bestandsnaam opslaan
            } else {
                $message = "Sorry, er was een fout bij het uploaden van de afbeelding.";
                $messageType = "error";
            }
        } else {
            $message = "Ongeldig bestandstype. Alleen JPG, JPEG, PNG en GIF worden ondersteund.";
            $messageType = "error";
        }
    }

    // Bijwerken van de gegevens in de database
    $fields = [];
    $params = [];
    $types = '';

    if ($title) { $fields[] = "title = ?"; $params[] = $title; $types .= 's'; }
    if ($category) { $fields[] = "category = ?"; $params[] = $category; $types .= 's'; }
    if ($rating !== null) { $fields[] = "rating = ?"; $params[] = $rating; $types .= 'i'; }
    if ($sections) { $fields[] = "sections = ?"; $params[] = $sections; $types .= 's'; }
    if ($image) { $fields[] = "image = ?"; $params[] = $image; $types .= 's'; }

    if (!empty($fields)) {
        // Hier is de aangepaste SQL query voor de 'media' tabel
        $sql = "UPDATE media SET " . implode(', ', $fields) . " WHERE id = ?";
        $types .= 'i';
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $message = "Item with ID $id was updated successfully.";
                $messageType = "success";
            } else {
                $message = "No changes were made or item with ID $id not found.";
                $messageType = "error";
            }
        } else {
            $message = "Error updating item: " . $conn->error;
            $messageType = "error";
        }

        $stmt->close();
    } else {
        $message = "No fields provided to update.";
        $messageType = "error";
    }
}

$conn->close();
?>

</body>
</html>
