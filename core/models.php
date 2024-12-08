<?php  

require_once 'dbConfig.php';

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"result"=> false,
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_accounts";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getUserByID($pdo, $username) {
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$username]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function insertPhoto($pdo, $photo_name, $username, $description, $photo_id=null) {

	if (empty($photo_id)) {
		$sql = "INSERT INTO photos (photo_name, username, description) VALUES(?,?,?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$photo_name, $username, $description]);

		if ($executeQuery) {
			return true;
		}
	}
	else {
		$sql = "UPDATE photos SET photo_name = ?, description = ? WHERE photo_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$photo_name, $description, $photo_id]);

		if ($executeQuery) {
			return true;
		}
	}
}

function getAllPhotos($pdo, $username=null) {
	if (empty($username)) {
		$sql = "SELECT * FROM photos ORDER BY date_added DESC";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute();

		if ($executeQuery) {
			return $stmt->fetchAll();
		}
	}
	else {
		$sql = "SELECT * FROM photos WHERE username = ? ORDER BY date_added DESC";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$username]);

		if ($executeQuery) {
			return $stmt->fetchAll();
		}
	}
}


function getPhotoByID($pdo, $photo_id) {
	$sql = "SELECT * FROM photos WHERE photo_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$photo_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function deletePhoto($pdo, $photo_id) {
	$sql = "DELETE FROM photos WHERE photo_id  = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$photo_id]);

	if ($executeQuery) {
		return true;
	}
	
}

function insertComment($pdo, $photo_id, $username, $description) {
	$sql = "INSERT INTO photos (photo_id, username, description) VALUES(?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$photo_id, $username, $description]);

	if ($executeQuery) {
		return true;
	}
}

function getCommentByID($pdo, $comment_id) {
	$sql = "SELECT * FROM comments WHERE comment_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$comment_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function updateComment($pdo, $description, $comment_id) {
	$sql = "UPDATE comments SET description = ?, WHERE comment_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$description, $comment_id,]);

	if ($executeQuery) {
		return true;
	}
}

function deleteComment($pdo, $comment_id) {
	$sql = "DELETE FROM comments WHERE comment_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$comment_id]);

	if ($executeQuery) {
		return true;
	}
}

function getAllPhotosJson($pdo) {
	if (empty($username)) {
		$sql = "SELECT * FROM photos";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute();

		if ($executeQuery) {
			return $stmt->fetchAll();
		}
	}
}

function createAlbum($pdo, $album_name, $username, $description) {
    $sql = "INSERT INTO albums (album_name, username, description) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$album_name, $username, $description]);
}

function getUserAlbums($pdo, $username) {
	$sql = "SELECT * FROM albums WHERE username = ? ORDER BY date_added DESC";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username]);
	return $stmt->fetchAll();
}

function getAlbumsByUser($pdo, $username) {
    $sql = "SELECT * FROM albums WHERE username = ? ORDER BY date_added DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    return $stmt->fetchAll();
}

function getPhotosByAlbum($pdo, $album_id) {
    error_log("Fetching photos for album_id: $album_id");

    $stmt = $pdo->prepare("SELECT * FROM photos WHERE album_id = ?");
    $stmt->execute([$album_id]);
    
    $photos = $stmt->fetchAll();
    error_log("Fetched photos: " . print_r($photos, true));
    
    return $photos;
}


function updateAlbum($pdo, $album_id, $album_name, $description) {
    $sql = "UPDATE albums SET album_name = ?, description = ? WHERE album_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$album_name, $description, $album_id]);
}

function deleteAlbum($pdo, $album_id) {
    $sql = "DELETE FROM albums WHERE album_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$album_id]);
}

function getAlbumByID($pdo, $album_id) {
    $stmt = $pdo->prepare("SELECT * FROM albums WHERE album_id = ?");
    $stmt->execute([$album_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
