<?php  
require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../login.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);
		$userIDFromDB = $loginQuery['userInfoArray']['user_id'];
		$usernameFromDB = $loginQuery['userInfoArray']['username'];
		$passwordFromDB = $loginQuery['userInfoArray']['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			header("Location: ../index.php");
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['user_id']);
	unset($_SESSION['username']);
	header("Location: ../login.php");
}


if (isset($_POST['insertPhotoBtn'])) {
    $album_id = intval($_POST['album_id']);
    $photoDescription = $_POST['photoDescription'];
    $image = $_FILES['image'];

    error_log("Uploaded file details: " . print_r($image, true));

    if ($image['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../images/';
        $photoName = time() . '-' . basename($image['name']);
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($image['tmp_name'], $uploadDir . $photoName)) {
            error_log("File uploaded successfully.");

            $stmt = $pdo->prepare(
                "INSERT INTO photos (album_id, photo_name, description, username, date_added) 
                VALUES (?, ?, ?, ?, NOW())"
            );
            $stmt->execute([$album_id, $photoName, $photoDescription, $_SESSION['username']]);
            error_log("Database insert result: " . $stmt->rowCount());

            header("Location: ../index.php?album_id=$album_id");
            exit;
        } else {
            error_log("Failed to move uploaded file.");
        }
    } else {
        error_log("Error during file upload: " . $image['error']);
    }
}


if (isset($_POST['deletePhotoBtn'])) {
	$photo_name = $_POST['photo_name'];
	$photo_id = $_POST['photo_id'];
	$deletePhoto = deletePhoto($pdo, $photo_id);

	if ($deletePhoto) {
		unlink("../images/".$photo_name);
		header("Location: ../index.php");
	}

}

if (isset($_POST['createAlbumBtn'])) {
	$album_name = trim($_POST['album_name']);
	$album_description = trim($_POST['album_description']);
	$username = $_SESSION['username'];

	if (!empty($album_name) && !empty($album_description)) {
		$createAlbum = $pdo->prepare("INSERT INTO albums (album_name, username, description) VALUES (?, ?, ?)");
		$createAlbum->execute([$album_name, $username, $album_description]);
		$_SESSION['message'] = "Album created successfully!";
		header("Location: ../index.php");
	}
}

if (isset($_POST['deleteAlbumBtn']) && isset($_POST['album_id'])) {
    $album_id = intval($_POST['album_id']);
    $stmt = $pdo->prepare("DELETE FROM albums WHERE album_id = ?");
    $stmt->execute([$album_id]);

    header("Location: ../index.php");
    exit;
}

if (isset($_POST['updateAlbumBtn'])) {
    $album_id = $_POST['album_id'];
    $album_name = trim($_POST['album_name']);
    $description = trim($_POST['description']);
    updateAlbum($pdo, $album_id, $album_name, $description);
    header("Location: ../viewalbum.php");
}