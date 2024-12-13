<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>

<?php  
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 0) {
	header("Location: ../index.php");
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
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1, h3 {
            color: #0056b3;
            text-align: center;
        }

        .searchForm {
            margin: 20px auto;
            text-align: center;
        }

        .searchForm input[type="text"] {
            width: 60%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 1rem;
        }

        .searchForm input[type="submit"] {
            padding: 10px 20px;
            background-color: #0056b3;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .searchForm input[type="submit"]:hover {
            background-color: #003d82;
        }

        .searchForm a {
            color: #0056b3;
            text-decoration: none;
            font-weight: bold;
        }

        form {
            margin: 20px auto;
            width: 60%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        form input[type="submit"] {
            background-color: #0056b3;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #003d82;
        }

        .postContainer {
            background-color: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 60%;
        }

        .postContainer h2, .postContainer h3 {
            margin: 0;
        }

        .postContainer p {
            color: #555;
        }

        .editAndDelete a {
            margin-right: 10px;
            text-decoration: none;
            color: #0056b3;
        }

        .editAndDelete a:hover {
            text-decoration: underline;
        }

        .tableClass {
            margin: 20px auto;
            width: 90%;
            overflow-x: auto;
        }

        .tableClass table {
            width: 100%;
            border-collapse: collapse;
        }

        .tableClass th, .tableClass td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .tableClass th {
            background-color: #0056b3;
            color: #fff;
        }

        .tableClass tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .tableClass tr:hover {
            background-color: #e8f4ff;
        }

        .tableClass a {
            color: #0056b3;
            text-decoration: none;
        }

        .tableClass a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
	<?php include 'navbar.php'; ?>

	<h1>Write a post</h1>

	<form action="core/handleForms.php" method="POST">
		<p><label for="username">Title</label></p>
		<p><input type="text" name="title"></p>
		<p><label for="username">Body</label></p>
		<p><textarea name="body" rows="10" cols="50"></textarea>
		<p><input type="submit" name="insertNewPostBtn"></p>
	</form>

	<h1>All Posts</h1>

	<?php $getAllPosts = getAllPosts($pdo); ?>
	<?php foreach ($getAllPosts as $row) { ?>
	<div class="postContainer">
		<a href="viewuser.php?user_id=<?php echo $row['user_id']; ?>">
			<h2><?php echo $row['userFullName']; ?></h2>
		</a>
		<i><?php echo $row['date_added']; ?></i>
		<h3><?php echo $row['title']; ?></h3>
		<p><?php echo $row['body']; ?></p>
		
		<?php if ($_SESSION['user_id'] == $row['user_id']) { ?>
		<div class="editAndDelete">
			<a href="editpost.php?user_post_id=<?php echo $row['user_post_id']; ?>">Edit</a>	
			<a href="deletepost.php?user_post_id=<?php echo $row['user_post_id']; ?>">Delete</a>	
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	
</body>
</html>