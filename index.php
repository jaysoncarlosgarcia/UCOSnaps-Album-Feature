<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$album_id = isset($_GET['album_id']) ? intval($_GET['album_id']) : 0;
$photos = [];
if ($album_id) {
    $photos = getPhotosByAlbum($pdo, $album_id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
	<!-- Section to Add Albums -->
	<div class="addAlbumForm" style="margin: 20px; text-align: center;">
		<h3>Add New Album</h3>
		<form action="core/handleForms.php" method="POST">
			<label for="album_name">Album Name:</label>
			<input type="text" name="album_name" required>
			<br>
			<label for="album_description">Description:</label>
			<input type="text" name="album_description" required>
			<br>
			<input type="submit" name="createAlbumBtn" value="Create Album" style="margin-top: 10px;">
		</form>
	</div>
    <!-- Section to Display Albums -->
    <div class="albumsList" style="margin: 20px;">
    <h3>Your Albums</h3>
    <?php 
    $albums = getUserAlbums($pdo, $_SESSION['username']); 
    if (count($albums) > 0) {
        foreach ($albums as $album) {
            ?>
            <div class="albumContainer" style="border: 1px solid gray; padding: 10px; margin-bottom: 10px;">
                <h4>
                    <a href="index.php?album_id=<?php echo $album['album_id']; ?>">
                        <?php echo htmlspecialchars($album['album_name']); ?>
                    </a>
                </h4>
                <p>Album Description: <?php echo htmlspecialchars($album['description']); ?></p>
                <p><i>Created on: <?php echo $album['date_added']; ?></i></p>
                <a href="editalbum.php?album_id=<?php echo $album['album_id']; ?>" style="color: green; margin-right: 10px;">Edit</a>
                <a href="deleteAlbum.php?album_id=<?php echo $album['album_id']; ?>" style="color: red;">Delete</a>

                <!-- Section to Display Photos under the album if clicked -->
                <?php if (isset($_GET['album_id']) && $_GET['album_id'] == $album['album_id']): ?>
                    <div class="photo-gallery" style="margin: 20px; border: 1px dashed gray; padding: 10px;">
                        <h4>Upload Photo to Selected Album</h4>
                        <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="album_id" value="<?php echo $album['album_id']; ?>">
                            <label for="photoDescription">Photo Description:</label>
                            <input type="text" name="photoDescription" id="photoDescription" required>
                            <br><br>
                            <label for="image">Select Photo:</label>
                            <input type="file" name="image" id="image" required>
                            <br><br>
                            <button type="submit" name="insertPhotoBtn">Upload</button>
                        </form>
                        <hr>

                        <h4>Photos in this Album</h4>
                        <?php 
                        $photos = getPhotosByAlbum($pdo, $album['album_id']);
                        if ($photos && count($photos) > 0): 
                            foreach ($photos as $photo): 
                                ?>
                                <div class="images" style="display: flex; justify-content: center; margin-top: 10px;">
                                    <div class="photoContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray; width: 60%;">

                                        <img src="images/<?php echo htmlspecialchars($photo['photo_name']); ?>" alt="Uploaded Photo" style="width: 100%; height: auto;">

                                        <div class="photoDescription" style="padding: 15px;">
                                            <a href="profile.php?username=<?php echo htmlspecialchars($photo['username']); ?>">
                                                <h2 style="margin: 0;"><?php echo htmlspecialchars($photo['username']); ?></h2>
                                            </a>
                                            <p style="margin: 5px 0;"><i><?php echo htmlspecialchars($photo['date_added']); ?></i></p>
                                            <h4 style="margin: 5px 0;"><?php echo htmlspecialchars($photo['description']); ?></h4>
                                            
                                            <?php if ($_SESSION['username'] == $photo['username']): ?>
                                                <a href="editphoto.php?photo_id=<?php echo $photo['photo_id']; ?>" style="color: green; float: left; margin: 5px;">Edit</a>
                                                <a href="deletephoto.php?photo_id=<?php echo $photo['photo_id']; ?>" style="color: red; float: left; margin: 5px;">Delete</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align: center; margin: 10px;">No photos uploaded for this album yet.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php 
        }
    } else {
        echo "<p>No albums found. Create one to get started!</p>";
    }
    ?>
</div>
</body>
</html>
