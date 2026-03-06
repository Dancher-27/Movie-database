<?php 
// Verbind met de database en haal de media-items op
require_once 'connection.php';  // Databaseverbinding
require_once 'classes/Media.php';  // Media klasse

$media = new Media($conn);

// Haal media-items op per sectie
$watched = $media->getMediaBySection('watched');
$watchlist = $media->getMediaBySection('watchlist');

// Verwerk rating update formulier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['id'])) {
    $newRating = $_POST['rating'];
    $id = $_POST['id'];
    
    $media->updateRating($id, $newRating);
    
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; padding:0; background-color:#f4f4f4; }
        .navbar { background-color:#333; overflow:hidden; padding:10px 20px; }
        .navbar a { color:white; text-align:center; padding:12px 20px; text-decoration:none; display:inline-block; font-size:18px; }
        .navbar a:hover { background-color:#ddd; color:black; }
        .container { max-width:1000px; margin:40px auto; padding:20px; }
        h1 { color:#333; font-size:24px; text-align:center; margin-bottom:20px; }
        .media-list { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; }
        .media-item { background-color:white; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); overflow:hidden; text-align:center; width:200px; }
        .media-item img { width:100%; height:300px; object-fit:cover; }
        .media-item h3 { font-size:18px; color:#333; margin:10px 0; }
        .media-item p { font-size:16px; color:#777; margin:0 0 10px; }
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
    <!-- Watched Section -->
    <h1>Watched Movies/Series</h1>
    <div class="media-list">
        <?php if (!empty($watched)): ?>
            <?php foreach ($watched as $item): ?>
                <div class="media-item">
                    <?php 
                        // Veilig controleren of afbeelding bestaat
                        $imagePath = 'uploads/' . htmlspecialchars($item['image']);
                        if (file_exists($imagePath)) {
                            echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($item['title']) . '">';
                        } else {
                            echo '<img src="uploads/placeholder.png" alt="No image">'; // fallback afbeelding
                        }
                    ?>
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p>Rating: <?php echo htmlspecialchars($item['rating']); ?></p>

                    <!-- Update rating form -->
                    <form action="index.php" method="POST">
                        <label for="rating_<?php echo $item['id']; ?>">Update Rating:</label>
                        <input type="number" id="rating_<?php echo $item['id']; ?>" name="rating" min="1" max="10" value="<?php echo htmlspecialchars($item['rating']); ?>" required>
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <button type="submit">Update Rating</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No watched media items found.</p>
        <?php endif; ?>
    </div>

    <!-- Watchlist Section -->
    <h1>Watchlist</h1>
    <div class="media-list">
        <?php if (!empty($watchlist)): ?>
            <?php foreach ($watchlist as $item): ?>
                <div class="media-item">
                    <?php 
                        $imagePath = 'uploads/' . htmlspecialchars($item['image']);
                        if (file_exists($imagePath)) {
                            echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($item['title']) . '">';
                        } else {
                            echo '<img src="uploads/placeholder.png" alt="No image">';
                        }
                    ?>
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p>Rating: <?php echo htmlspecialchars($item['rating']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No items in your watchlist.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>