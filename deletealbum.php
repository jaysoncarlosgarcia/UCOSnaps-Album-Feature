<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>

<?php  
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Delete Album</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<?php include 'navbar.php'; ?>
	
	<?php 
	// Fetch the album details by album_id
	if (isset($_GET['album_id'])) {
		$album_id = intval($_GET['album_id']);
		$getAlbumByID = getAlbumByID($pdo, $album_id);
	}
	?>

	<div class="deleteAlbumForm" style="display: flex; justify-content: center;">
		<div class="deleteForm" style="border-style: solid; border-color: red; background-color: #ffcbd1; padding: 10px; width: 50%;">
			<form action="core/handleForms.php" method="POST">
				<p>
					<label for=""><h2>Are you sure you want to delete this album?</h2></label>
					<input type="hidden" name="album_id" value="<?php echo $getAlbumByID['album_id']; ?>">
					<input type="submit" name="deleteAlbumBtn" style="margin-top: 10px;" value="Delete Album">
				</p>
			</form>
		</div>
	</div>
	
	<div class="albumDetails" style="display: flex; justify-content: center; margin-top: 25px;">
		<div class="albumContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray; width: 50%; padding: 10px;">
			<h2>Album Name: <?php echo htmlspecialchars($getAlbumByID['album_name']); ?></h2>
			<p>Album Description: <?php echo htmlspecialchars($getAlbumByID['description']); ?></p>
			<p><small>Created on: <?php echo htmlspecialchars($getAlbumByID['date_added']); ?></small></p>
		</div>
	</div>
</body>
</html>
