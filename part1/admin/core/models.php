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

function insertNewUser($pdo, $username, $first_name, $last_name, $password, $is_admin=null) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password, is_admin) 
		VALUES (?,?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password, $is_admin])) {
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
	$sql = "SELECT * FROM user_accounts 
			WHERE is_admin = 0";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllAdmins($pdo) {
	$sql = "SELECT * FROM user_accounts 
			WHERE is_admin = 1";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getUserByID($pdo, $user_id) {
	$sql = "SELECT * FROM user_accounts WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function suspendAccount($pdo, $user_id) {
	$sql = "UPDATE user_accounts SET is_suspended = 1
			WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
		return true;
	}
}

function unsuspendAccount($pdo, $user_id) {
	$sql = "UPDATE user_accounts SET is_suspended = 0
			WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
		return true;
	}
}

function loginUser($pdo, $username, $password) {
	$sql = "SELECT * FROM user_accounts WHERE username=?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username]); 

	if ($stmt->rowCount() == 1) {
		$userInfoRow = $stmt->fetch();
		$userIDFromDB = $userInfoRow['user_id']; 
		$usernameFromDB = $userInfoRow['username']; 
		$passwordFromDB = $userInfoRow['password'];

		if ($password == $passwordFromDB) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			$_SESSION['message'] = "Login successful!";
			return true;
		}

		else {
			$_SESSION['message'] = "Password is invalid, but user exists";
		}
	}

	
	if ($stmt->rowCount() == 0) {
		$_SESSION['message'] = "Username doesn't exist from the database. You may consider registration first";
	}

}

function updateUserInfo($pdo, $first_name, $last_name, $user_id) {
	$sql = "UPDATE user_accounts 
			SET first_name = ?, 
				last_name = ? 
			WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt) {
		return $stmt->execute([$first_name, $last_name, $user_id]);
	}


}

function insertNewPost($pdo, $title, $body, $user_id) {

	$sql = "INSERT INTO user_posts (title, body, user_id) VALUES (?,?,?)";
	$stmt = $pdo->prepare($sql);

	if ($stmt) {
		return $stmt->execute([$title,$body,$user_id]);
	}
}

function editAPost($pdo, $title, $body, $user_post_id) {

	$sql = "UPDATE user_posts SET title = ?, body = ? WHERE user_post_id = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt) {
		return $stmt->execute([$title,$body,$user_post_id]);
	}

}

function deleteAPost($pdo, $user_post_id) {

	$sql = "DELETE FROM user_posts WHERE user_post_id = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt) {
		return $stmt->execute([$user_post_id]);
	}

}

function getAllPosts($pdo) {

	$sql = "SELECT
				user_posts.user_post_id AS user_post_id,
				user_posts.user_id AS user_id,
				CONCAT(user_accounts.first_name, ' ' , 
					user_accounts.last_name) AS userFullName,
				user_posts.title AS title,
				user_posts.body AS body,
				user_posts.date_added AS date_added
			FROM user_posts
			JOIN user_accounts ON
				user_posts.user_id = user_accounts.user_id
			";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute()) {
		return $stmt->fetchAll();
	}
}

function getPostByID($pdo, $user_post_id) {

	$sql = "SELECT
				user_posts.user_post_id AS user_post_id,
				user_posts.user_id AS user_id,
				CONCAT(user_accounts.first_name, ' ' , 
					user_accounts.last_name) AS userFullName,
				user_posts.title AS title,
				user_posts.body AS body,
				user_posts.date_added AS date_added
			FROM user_posts
			JOIN user_accounts ON
				user_posts.user_id = user_accounts.user_id
			WHERE user_post_id = ?
			";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$user_post_id])) {
		return $stmt->fetch();
	}
}

function getAllPostsByUser($pdo, $user_id) {

	$sql = "SELECT
				user_posts.user_post_id AS user_post_id,
				user_posts.user_id AS user_id,
				CONCAT(user_accounts.first_name, ' ' , 
					user_accounts.last_name) AS userFullName,
				user_posts.title AS title,
				user_posts.body AS body,
				user_posts.date_added AS date_added
			FROM user_posts
			JOIN user_accounts ON
				user_posts.user_id = user_accounts.user_id
			WHERE user_posts.user_id = ?";

	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$user_id])) {
		return $stmt->fetchAll();
	}
}

function getAllBranches($pdo) {
	$sql = "SELECT * FROM branches";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllBranchesBySearch($pdo, $searchQuery) {
	$sql = "SELECT * FROM branches WHERE 
			CONCAT(address,head_manager,
				contact_number,
				date_added,added_by,
				last_updated,
				last_updated_by) 
			LIKE ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute(["%".$searchQuery."%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}


function getBranchByID($pdo, $branch_id) {
	$sql = "SELECT * FROM branches WHERE branch_id = ?";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute([$branch_id])) {
		return $stmt->fetch();
	}
}

function insertAnActivityLog($pdo, $operation, $branch_id, $address, 
		$head_manager, $contact_number, $username) {

	$sql = "INSERT INTO activity_logs (operation, branch_id, address, 
		head_manager, contact_number, username) VALUES(?,?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$operation, $branch_id, $address, 
		$head_manager, $contact_number, $username]);

	if ($executeQuery) {
		return true;
	}

}

function getAllActivityLogs($pdo) {
	$sql = "SELECT * FROM activity_logs 
			ORDER BY date_added DESC";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute()) {
		return $stmt->fetchAll();
	}
}

function insertABranch($pdo, $address, $head_manager, $contact_number, $added_by) {
	$response = array();
	$sql = "INSERT INTO branches (address, head_manager, contact_number, added_by) VALUES(?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$insertBranch = $stmt->execute([$address, $head_manager, $contact_number, $added_by]);

	if ($insertBranch) {
		$findInsertedItemSQL = "SELECT * FROM branches ORDER BY date_added DESC LIMIT 1";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute();
		$getBranchID = $stmtfindInsertedItemSQL->fetch();

		$insertAnActivityLog = insertAnActivityLog($pdo, "INSERT", $getBranchID['branch_id'], 
			$getBranchID['address'], $getBranchID['head_manager'], 
			$getBranchID['contact_number'], $_SESSION['username']);

		if ($insertAnActivityLog) {
			$response = array(
				"status" =>"200",
				"message"=>"Branch addedd successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
		
	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"Insertion of data failed!"
		);

	}

	return $response;
}

function updateBranch($pdo, $address, $head_manager, $contact_number, 
	$last_updated, $last_updated_by, $branch_id) {

	$response = array();
	$sql = "UPDATE branches
			SET address = ?,
				head_manager = ?,
				contact_number = ?, 
				last_updated = ?, 
				last_updated_by = ? 
			WHERE branch_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$updateBranch = $stmt->execute([$address, $head_manager, $contact_number, 
	$last_updated, $last_updated_by, $branch_id]);

	if ($updateBranch) {

		$findInsertedItemSQL = "SELECT * FROM branches WHERE branch_id = ?";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute([$branch_id]);
		$getBranchID = $stmtfindInsertedItemSQL->fetch(); 

		$insertAnActivityLog = insertAnActivityLog($pdo, "UPDATE", $getBranchID['branch_id'], 
			$getBranchID['address'], $getBranchID['head_manager'], 
			$getBranchID['contact_number'], $_SESSION['username']);

		if ($insertAnActivityLog) {

			$response = array(
				"status" =>"200",
				"message"=>"Updated the branch successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}

	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;

}


function deleteABranch($pdo, $branch_id) {
	$response = array();
	$sql = "SELECT * FROM branches WHERE branch_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$branch_id]);
	$getBranchByID = $stmt->fetch();

	$insertAnActivityLog = insertAnActivityLog($pdo, "DELETE", $getBranchByID['branch_id'], 
		$getBranchByID['address'], $getBranchByID['head_manager'], 
		$getBranchByID['contact_number'], $_SESSION['username']);

	if ($insertAnActivityLog) {
		$deleteSql = "DELETE FROM branches WHERE branch_id = ?";
		$deleteStmt = $pdo->prepare($deleteSql);
		$deleteQuery = $deleteStmt->execute([$branch_id]);

		if ($deleteQuery) {
			$response = array(
				"status" =>"200",
				"message"=>"Deleted the branch successfully!"
			);
		}
		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
	}
	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;
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
function getAllInquiries($pdo, $inquiry_id=NULL) {

	if (!empty($inquiry_id)) {
		$sql = "SELECT 
					user_accounts.username AS username,
					inquiries.inquiry_id AS inquiry_id,
					inquiries.description AS description,
					inquiries.date_added AS date_added
				FROM inquiries
				JOIN user_accounts 
				ON inquiries.user_id = user_accounts.user_id
				WHERE inquiries.inquiry_id = ?
				";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$inquiry_id]);

		if ($executeQuery) {
			return $stmt->fetch();
		}

	}
	else {
		$sql = "SELECT 
					user_accounts.username AS username,
					inquiries.inquiry_id AS inquiry_id,
					inquiries.description AS description,
					inquiries.date_added AS date_added
				FROM inquiries
				JOIN user_accounts 
				ON inquiries.user_id = user_accounts.user_id
				ORDER BY inquiries.date_added DESC
				";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute();

		if ($executeQuery) {
			return $stmt->fetchAll();
		}

	}
}


function getAllRepliesByInquiry($pdo, $inquiry_id) {
	$sql = "SELECT 
				user_accounts.username AS username,
				replies.reply_id AS reply_id,
				replies.description AS description,
				replies.date_added AS date_added
			FROM replies
			JOIN user_accounts 
			ON replies.user_id = user_accounts.user_id
			JOIN inquiries 
			ON replies.inquiry_id = inquiries.inquiry_id
			WHERE inquiries.inquiry_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$inquiry_id]);

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function insertReply($pdo, $description, $inquiry_id, $user_id) {
	$sql = "INSERT INTO replies (description, inquiry_id, user_id) VALUES(?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$description, $inquiry_id, $user_id]);
	if ($executeQuery) {
		return true;
	}
}


function getReplyByID($pdo, $reply_id) {
	$sql = "SELECT * FROM replies WHERE reply_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$reply_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function editReply($pdo, $description, $reply_id) {
	$sql = "UPDATE replies SET description = ? WHERE reply_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$description, $reply_id]);
	if ($executeQuery) {
		return true;
	}
}

function deleteReply($pdo, $reply_id) {
	$sql = "DELETE FROM replies WHERE reply_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$reply_id]);
	if ($executeQuery) {
		return true;
	}
}


