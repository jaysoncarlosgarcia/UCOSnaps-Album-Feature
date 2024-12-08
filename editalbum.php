<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// Redirect if user isn't logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch album ID from URL
if (!isset($_GET['album_id'])) {
    header("Location: index.php");
    exit;
}

$album_id = intval($_GET['album_id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $album_name = $_POST['album_name'];
    $album_description = $_POST['album_description'];

    // Update database
    $stmt = $pdo->prepare("UPDATE albums SET album_name = ?, description = ? WHERE album_id = ?");
    $stmt->execute([$album_name, $album_description, $album_id]);

    header("Location: index.php?album_id=$album_id");
    exit;
}

// Fetch current album details to pre-fill the form
$stmt = $pdo->prepare("SELECT * FROM albums WHERE album_id = ?");
$stmt->execute([$album_id]);
$album = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h2>Edit Album Details</h2>
    <form method="POST">
        <label for="album_name">Album Name:</label><br>
        <input type="text" id="album_name" name="album_name" value="<?php echo htmlspecialchars($album['album_name']); ?>" required><br><br>
        
        <label for="album_description">Album Description:</label><br>
        <textarea id="album_description" name="album_description" rows="4" cols="50" required><?php echo htmlspecialchars($album['description']); ?></textarea><br><br>
        
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
