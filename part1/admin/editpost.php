<?php 
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMIN</title>
	<style>
		body {
			font-family: "Arial";
		}
		input {
			font-size: 1.5em;
			height: 50px;
			width: 200px;
		}
		textarea { 
			font-size: 1.5em;
		}
		table, th, td {
			border:1px solid black;
		}
	</style>
</head>
<body>
	<?php if (isset($_SESSION['message'])) { ?>
		<h1 style="color: red;"><?php echo $_SESSION['message']; ?></h1>
	<?php } unset($_SESSION['message']); ?>



	<?php if (isset($_SESSION['username'])) { ?>
		<h1>Hello there!! <?php echo $_SESSION['username']; ?></h1>
		<?php include 'navbar.php'; ?>
	<?php } else { echo "<h1>No user logged in</h1>";}?>

	<?php $getPostByID = getPostByID($pdo, $_GET['user_post_id']); ?>

	<form action="core/handleForms.php?user_post_id=<?php echo $_GET['user_post_id']; ?>" method="POST">
		<p>
			<label for="username">Title</label>
			<input type="text" name="title" value="<?php echo $getPostByID['title']; ?>">
		</p>
		<p><label for="username">Body</label></p>
		<p><textarea name="body" rows="10" cols="50"><?php echo $getPostByID['body']; ?></textarea></p>
		<p><input type="submit" name="editPostBtn"></p>
	</form>
</body>
</html>
