<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['movieTitle'])) {
    $movieTitle = urlencode($_GET['movieTitle']);
    $apiKey = '186be766';
    $url = "http://www.omdbapi.com/?t=$movieTitle&apikey=$apiKey";

    $response = file_get_contents($url);
    $movieData = json_decode($response, true);

    if ($movieData['Response'] == 'True') {
        $title = $movieData['Title'];
        $year = $movieData['Year'];
        $genre = $movieData['Genre'];
        $plot = $movieData['Plot'];
        $poster = $movieData['Poster'];
    } else {
        $errorMessage = "Film niet gevonden.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Zoeken</title>
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
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        form label {
            margin-right: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        form input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            flex: 1;
        }
        form button {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }
        form button:hover {
            background-color: #555;
        }
        .movie-result {
            text-align: center;
            margin-top: 20px;
        }
        .movie-result img {
            width: 200px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .movie-result h2 {
            font-size: 24px;
            color: #333;
        }
        .movie-result p {
            font-size: 16px;
            color: #777;
            margin: 5px 0;
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
        <h1>Film Zoeken</h1>
        <!-- Formulier -->
        <form action="search.php" method="GET">
            <label for="movieTitle">Zoek een film:</label>
            <input type="text" name="movieTitle" id="movieTitle" placeholder="Vul een filmnaam in..." required>
            <button type="submit">Zoeken</button>
        </form>

        <!-- Filmgegevens -->
        <?php if (isset($movieData['Response']) && $movieData['Response'] == 'True'): ?>
            <div class="movie-result">
                <img src="<?= $poster ?>" alt="Film poster">
                <h2><?= $title ?> (<?= $year ?>)</h2>
                <p><strong>Genre:</strong> <?= $genre ?></p>
                <p><strong>Plot:</strong> <?= $plot ?></p>
            </div>
        <?php elseif (isset($errorMessage)): ?>
            <p style="text-align: center; color: red;"><?= $errorMessage ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
