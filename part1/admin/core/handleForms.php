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

if (isset($_POST['insertNewAdminBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);
	$is_admin = true;

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT), $is_admin);
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../alladmins.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register-an-admin.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register-an-admin.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register-an-admin.php");
	}
}


if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['user_id']);
	unset($_SESSION['username']);
	header("Location: ../login.php");
}

if (isset($_POST['suspendAccountBtn'])) {
	$user_id = $_POST['user_id'];
	$suspendAccount = suspendAccount($pdo, $user_id);

	if ($suspendAccount) {
		header("Location: ../index.php");
	}
}

if (isset($_POST['unsuspendAccountBtn'])) {
	$user_id = $_POST['user_id'];
	$unsuspendAccount = unsuspendAccount($pdo, $user_id);

	if ($unsuspendAccount) {
		header("Location: ../index.php");
	}
}

if (isset($_POST['registerUserBtn'])) {

	$username = sanitizeInput($_POST['username']);
	$first_name = sanitizeInput($_POST['first_name']);
	$last_name = sanitizeInput($_POST['last_name']);
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];

	if (!empty($username) && !empty($first_name) && !empty($last_name) 
		&& !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (validatePassword($password)) {

				$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, sha1($password));

				if ($insertQuery) {
					header("Location: ../login.php");
				}
				else {
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = "Password should be more than 8 characters and should contain both uppercase, lowercase, and numbers";
				header("Location: ../register.php");
			}
		}

		else {
			$_SESSION['message'] = "Please check if both passwords are equal!";
			header("Location: ../register.php");
		}
	
	}

	else {
		$_SESSION['message'] = "Please make sure the input fields 
		are not empty for registration!";

		header("Location: ../register.php");
	}

}

if (isset($_POST['insertNewPostBtn'])) {

	$title = ($_POST['title']);
	$body = $_POST['body'];
	$userID = $_SESSION['user_id'];

	if (!empty($title) && !empty($body) && !empty($userID)) {

		$insertQuery = insertNewPost($pdo, $title, $body, $userID);

		if ($insertQuery) {
			header("Location: ../index.php");
		}
		
	}

	else {
		header("Location: ../index.php");
		$_SESSION['message'] = "Make sure input fields are not empty!";
	}

}

if (isset($_POST['editPostBtn'])) {

	$title = sanitizeInput($_POST['title']);
	$body = sanitizeInput($_POST['body']);
	$user_post_id = $_GET['user_post_id'];

	if (!empty($title) && !empty($body)) {

		$editQuery = editAPost($pdo, $title, $body, $user_post_id);

		if ($editQuery) {
			header("Location: ../index.php");
		}
	
	}

	else {
		header("Location: ../index.php");
		$_SESSION['message'] = "Make sure input fields are not empty!";
	}
}

if (isset($_POST['deletePostBtn'])) {

	$user_post_id = $_GET['user_post_id'];
	$deleteQuery = deleteAPost($pdo, $user_post_id);

	if ($deleteQuery) {
		header("Location: ../index.php");
	}

}

if (isset($_POST['insertNewBranchBtn'])) {
	$address = trim($_POST['address']);
	$head_manager = trim($_POST['head_manager']);
	$contact_number = trim($_POST['contact_number']);

	if (!empty($address) && !empty($head_manager) && !empty($contact_number)) {
		$insertABranch = insertABranch($pdo, $address, $head_manager, 
			$contact_number, $_SESSION['username']);
		$_SESSION['status'] =  $insertABranch['status']; 
		$_SESSION['message'] =  $insertABranch['message']; 
		header("Location: ../index.php");
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../insertbranch.php");
	}

}

if (isset($_POST['updateBranchBtn'])) {

	$address = $_POST['address'];
	$head_manager = $_POST['head_manager'];
	$contact_number = $_POST['contact_number'];
	$date = date('Y-m-d H:i:s');

	if (!empty($address) && !empty($head_manager) && !empty($contact_number)) {

		$updateBranch = updateBranch($pdo, $address, $head_manager, $contact_number, 
			$date, $_SESSION['username'], $_GET['branch_id']);

		$_SESSION['message'] = $updateBranch['message'];
		$_SESSION['status'] = $updateBranch['status'];
		header("Location: ../index.php");
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_POST['deleteBranchBtn'])) {
	$branch_id = $_GET['branch_id'];

	if (!empty($branch_id)) {
		$deleteBranch = deleteABranch($pdo, $branch_id);
		$_SESSION['message'] = $deleteBranch['message'];
		$_SESSION['status'] = $deleteBranch['status'];
		header("Location: ../make-a-post.php");
	}
}

if (isset($_GET['searchBtn'])) {
	$getAllBranchesBySearch = getAllBranchesBySearch($pdo, $_GET['searchQuery']);
	foreach ($getAllBranchesBySearch as $row) {
		echo "<tr> 
				<td>{$row['id']}</td>
				<td>{$row['address']}</td>
				<td>{$row['head_manager']}</td>
				<td>{$row['contact_number']}</td>
				<td>{$row['date_added']}</td>
				<td>{$row['added_by']}</td>
				<td>{$row['last_updated']}</td>
				<td>{$row['last_updated_by']}</td>
			  </tr>";
	}
}

if (isset($_POST['insertPhotoBtn'])) {

	// Get Description
	$description = $_POST['photoDescription'];

	// Get file name
	$fileName = $_FILES['image']['name'];

	// Get temporary file name
	$tempFileName = $_FILES['image']['tmp_name'];

	// Get file extension
	$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

	// Generate random characters for image name
	$uniqueID = sha1(md5(rand(1,9999999)));

	// Combine image name and file extension
	$imageName = $uniqueID.".".$fileExtension;

	// If we want edit a photo
	if (isset($_POST['photo_id'])) {
		$photo_id = $_POST['photo_id'];
	}
	else {
		$photo_id = "";
	}

	// Save image 'record' to database
	$saveImgToDb = insertPhoto($pdo, $imageName, $_SESSION['user_id'], $description, $photo_id);

	// Store actual 'image file' to images folder
	if ($saveImgToDb) {

		// Specify path
		$folder = "../images/".$imageName;

		// Move file to the specified path 
		if (move_uploaded_file($tempFileName, $folder)) {
			header("Location: ../index.php");
		}
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

if (isset($_POST['insertReplyBtn'])) {
	$reply_description = $_POST['reply_description'];
	$inquiry_id = $_POST['inquiry_id'];
	$insertQuery = insertReply($pdo, $reply_description, $inquiry_id, $_SESSION['user_id']);
	if ($insertQuery) {
		header("Location: ../admin/reply-to-inquiry.php?inquiry_id=" . $inquiry_id);
	}
}

if (isset($_POST['updateReplyBtn'])) {
	$reply_description = $_POST['reply_description'];
	$reply_id = $_POST['reply_id'];
	$inquiry_id = $_POST['inquiry_id'];
	$editQuery = editReply($pdo, $reply_description, $reply_id);
	if ($editQuery) {
		header("Location: ../admin/reply-to-inquiry.php?inquiry_id=" . $inquiry_id);
	}
}

if (isset($_POST['deleteReplyBtn'])) {
	$reply_id = $_POST['reply_id'];
	$inquiry_id = $_POST['inquiry_id'];
	$deleteQuery = deleteReply($pdo, $reply_id);

	if ($deleteQuery) {
		header("Location: ../admin/reply-to-inquiry.php?inquiry_id=" . $inquiry_id);
	}

}



