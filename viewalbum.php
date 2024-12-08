<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 
if (!isset($_SESSION['username'])) { header("Location: login.php"); }
$username = $_SESSION['username'];
$albums = getAlbumsByUser($pdo, $username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Albums</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <h1>My Albums</h1>
    <form action="core/handleForms.php" method="POST">
        <label>Album Name:</label>
        <input type="text" name="album_name" required>
        <label>Description:</label>
        <input type="text" name="description" required>
        <button type="submit" name="createAlbumBtn">Create Album</button>
    </form>

    <?php foreach ($albums as $album): ?>
        <div>
            <h2><?php echo $album['album_name']; ?></h2>
            <p><?php echo $album['description']; ?></p>
            <form action="core/handleForms.php" method="POST" style="display:inline;">
                <input type="hidden" name="album_id" value="<?php echo $album['album_id']; ?>">
                <input type="text" name="album_name" value="<?php echo $album['album_name']; ?>" required>
                <input type="text" name="description" value="<?php echo $album['description']; ?>" required>
                <button type="submit" name="updateAlbumBtn">Edit</button>
            </form>
            <form action="core/handleForms.php" method="POST" style="display:inline;">
                <input type="hidden" name="album_id" value="<?php echo $album['album_id']; ?>">
                <button type="submit" name="deleteAlbumBtn">Delete</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
