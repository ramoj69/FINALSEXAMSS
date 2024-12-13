<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php  
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 1) {
	header("Location: admin/index.php");
}

if ($getUserByID['is_suspended'] == 1) {
	header("Location: suspended-account-error.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>APPLICANT</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<?php include 'navbar.php'; ?>
	<h1 style="text-align:center;">Send an Inquiry</h1>
	<div class="formContainer" style="display: flex; justify-content: center;">
		<form action="core/handleForms.php" method="POST">
			<p>
				<label for="username">Inquiry</label>
				<input type="text" name="inquiry_description">
				<input type="submit" name="insertInquiryBtn" style="margin-top: 25px; ">
			</p>
		</form>	
	</div>
	
</body>
</html>