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
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #0056b3;
            margin-top: 20px;
        }

        form {
            margin: 30px auto;
            width: 50%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form p {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-size: 1rem;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #0056b3;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #003d82;
        }

        .form-container {
            text-align: center;
        }

        .form-container a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #0056b3;
        }

        .form-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
	<?php include 'navbar.php'; ?>

	<?php $getBranchByID = getBranchByID($pdo, $_GET['branch_id']); ?>
	<form action="core/handleForms.php?branch_id=<?php echo $_GET['branch_id']; ?>" method="POST">
		<p>
			<label for="address">Job Position</label>
			<input type="text" name="address" value="<?php echo $getBranchByID['address']; ?>"></p>
		<p>
			<label for="address">Recruiter</label>
			<input type="text" name="head_manager" value="<?php echo $getBranchByID['head_manager']; ?>">
		</p>
		<p>
			<label for="address">Contact Number</label>
			<input type="text" name="contact_number" value="<?php echo $getBranchByID['contact_number']; ?>">
			<input type="submit" name="updateBranchBtn" value="Update">
		</p>
	</form>
</body>
</html>