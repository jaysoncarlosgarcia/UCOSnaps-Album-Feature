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
	<title>Delete Photo</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<?php include 'navbar.php'; ?>
	<?php 
	$getPhotoByID = getPhotoByID($pdo, $_GET['photo_id']); 
	?>
	<div class="deletePhotoForm" style="display: flex; justify-content: center; margin-top: 20px;">
		<div class="deleteForm" style="border: 2px solid red; background-color: #ffcbd1; padding: 15px; width: 60%;">
			<h2 style="text-align: center; color: red;">Confirm Deletion of Photo</h2>
			<p style="text-align: center; font-size: 16px;">You are about to delete the following photo:</p>
			<div style="border: 1px solid gray; padding: 10px; margin: 10px 0; background-color: #ffffff;">
				<strong>Photo Name:</strong> <?php echo htmlspecialchars($getPhotoByID['photo_name']); ?><br>
				<strong>Description:</strong> <?php echo htmlspecialchars($getPhotoByID['description']); ?>
			</div>
			<form action="core/handleForms.php" method="POST" style="text-align: center;">
				<input type="hidden" name="photo_name" value="<?php echo htmlspecialchars($getPhotoByID['photo_name']); ?>">
				<input type="hidden" name="photo_id" value="<?php echo htmlspecialchars($_GET['photo_id']); ?>">
				<input type="submit" name="deletePhotoBtn" style="background-color: red; color: white; padding: 10px 20px; border: none; cursor: pointer;" value="Delete Photo">
			</form>
			<p style="text-align: center; margin-top: 15px;">
				<a href="index.php" style="text-decoration: none; color: blue;">Cancel</a>
			</p>
		</div>
	</div>
	<div class="images" style="display: flex; justify-content: center; margin-top: 25px;">
		<div class="photoContainer" style="background-color: ghostwhite; border: 1px solid gray; width: 60%; padding: 15px;">
			<img src="images/<?php echo htmlspecialchars($getPhotoByID['photo_name']); ?>" alt="Photo" style="width: 100%; height: auto;">

			<div class="photoDescription" style="padding: 10px; font-size: 14px;">
				<strong>Description:</strong> <?php echo htmlspecialchars($getPhotoByID['description']); ?>
			</div>
		</div>
	</div>
</body>
</html>
