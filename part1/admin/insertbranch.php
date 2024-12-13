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

	<form action="core/handleForms.php" method="POST">
		<p>
			<label for="address">Job Position</label>
			<input type="text" name="address"></p>
		<p>
			<label for="address">Recruiter</label>
			<input type="text" name="head_manager">
		</p>
		<p>
			<label for="address">Contact Number</label>
			<input type="text" name="contact_number">
			<input type="submit" name="insertNewBranchBtn" value="Create">
		</p>
	</form>

	<div class="tableClass">
		<table style="width: 100%;" cellpadding="20">
			<tr>
				<th>Job Position</th>
				<th>Recruiter</th>
				<th>Contact Number</th>
				<th>Date Added</th>
				<th>Added By</th>
				<th>Last Updated</th>
				<th>Last Updated By</th>
				<th>Action</th>
			</tr>
			<?php if (!isset($_GET['searchBtn'])) { ?>
				<?php $getAllBranches = getAllBranches($pdo); ?>
				<?php foreach ($getAllBranches as $row) { ?>
				<tr>
					<td><?php echo $row['address']; ?></td>
					<td><?php echo $row['head_manager']; ?></td>
					<td><?php echo $row['contact_number']; ?></td>
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_updated']; ?></td>
					<td><?php echo $row['last_updated_by']; ?></td>
					<td>
						<a href="updatebranch.php?branch_id=<?php echo $row['branch_id']; ?>">Update</a>
					</td>
				</tr>
				<?php } ?>
			<?php } else { ?>
				<?php $getAllBranchesBySearch = getAllBranchesBySearch($pdo, $_GET['searchQuery']); ?>
				<?php foreach ($getAllBranchesBySearch as $row) { ?>
				<tr>
					<td><?php echo $row['address']; ?></td>
					<td><?php echo $row['head_manager']; ?></td>
					<td><?php echo $row['contact_number']; ?></td>
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_updated']; ?></td>
					<td><?php echo $row['last_updated_by']; ?></td>
					<td>
						<a href="updatebranch.php?branch_id=<?php echo $row['branch_id']; ?>">Update</a>
					</td>
				</tr>
				<?php } ?>
			<?php } ?>
		</table>
	</div>

</body>
</html>