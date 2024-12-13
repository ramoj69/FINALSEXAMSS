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
	<?php  
	if (isset($_SESSION['message']) && isset($_SESSION['status'])) {

		if ($_SESSION['status'] == "200") {
			echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
		}

		else {
			echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>";	
		}

	}
	unset($_SESSION['message']);
	unset($_SESSION['status']);
	?>
	<h1 style="text-align: center;">Hello there!! <span style="color: blue"><?php echo $_SESSION['username']; ?></span>. Here are all available Jobs for yah!!</h1>

	<?php $getAllBranches = getAllBranches($pdo); ?>
	<?php foreach ($getAllBranches as $row) { ?>
	<div class="branches" style="display: flex; justify-content: center; margin-top: 25px;">
		<div class="branchContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%; padding: 25px;">
			<h3>Job Position: <?php echo $row['address']; ?></h3>
			<h3>Recruiter: <?php echo $row['head_manager']; ?></h3>
			<h3>Contact Number: <?php echo $row['contact_number']; ?></h3>
			<form action="core/handleForms.php" method="POST" style="margin-top: 20px;">
                <input type="hidden" name="branch_id" value="">
                <button type="submit" name="insertInquiryBtn" style="padding: 10px 20px; background-color: blue; color: white; border: none; cursor: pointer;">
                    Apply
                </button>
            </form>
		</div>
	</div>
	<?php } ?>

</body>
</html>