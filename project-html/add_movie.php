<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Movie</title>
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
        input[type="text"],
        input[type="number"],
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
        .form-group {
            margin-bottom: 20px;
        }
        .alert {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 4px;
            display: none;
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
    <h1>Create Movie</h1>

    <div id="alertMessage" class="alert"></div>

    <form action="create_movie.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="id">ID:</label>
            <input type="number" name="id" id="id" required>
        </div>

        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
        </div>

        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" name="category" id="category" required>
        </div>

        <div class="form-group">
            <label for="rating">Rating:</label>
            <input type="number" name="rating" id="rating" required>
        </div>

        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" name="image" id="image">
        </div>

        <button type="submit">Create Movie</button>
    </form>
</div>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wishlist"; // Ensure this matches your actual database

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $category = $_POST['category'];
    $rating = intval($_POST['rating']);

    // Image handling
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image = basename($_FILES['image']['name']);
            } else {
                echo "<script>document.getElementById('alertMessage').innerHTML = 'Error uploading image.'; document.getElementById('alertMessage').classList.add('error'); document.getElementById('alertMessage').style.display = 'block';</script>";
            }
        } else {
            echo "<script>document.getElementById('alertMessage').innerHTML = 'Invalid image format. Only JPG, JPEG, PNG, GIF allowed.'; document.getElementById('alertMessage').classList.add('error'); document.getElementById('alertMessage').style.display = 'block';</script>";
        }
    }

    $stmt = $conn->prepare("INSERT INTO movies (id, title, category, rating, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issis", $id, $title, $category, $rating, $image);

    if ($stmt->execute()) {
        echo "<script>document.getElementById('alertMessage').innerHTML = 'New movie created successfully with ID " . $id . "!'; document.getElementById('alertMessage').classList.add('alert-success'); document.getElementById('alertMessage').style.display = 'block';</script>";
    } else {
        echo "<script>document.getElementById('alertMessage').innerHTML = 'Error creating movie: " . $conn->error . "'; document.getElementById('alertMessage').classList.add('error'); document.getElementById('alertMessage').style.display = 'block';</script>";
    }

    $stmt->close();
}

$conn->close();
?>

</body>
</html>


