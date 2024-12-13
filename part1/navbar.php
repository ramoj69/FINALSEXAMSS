<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APPLICANT</title>
    <style>
        .navbar {
            background-color: #f4f4f4;
            padding: 20px 0;
            text-align: center;
            margin-bottom: 50px;
            border-bottom: 2px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            margin: 0 0 15px 0;
            font-family: Arial, sans-serif;
            font-size: 1.5rem;
            color: #333;
        }

        .navbar h1 span {
            color: blue;
            font-weight: bold;
        }

        .navbar a {
            text-decoration: none;
            color: #007BFF;
            margin: 0 10px;
            font-size: 1rem;
            font-family: Arial, sans-serif;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #007BFF;
            color: white;
        }

        .navbar a.active {
            background-color: #0056b3;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
	<div class="navbar" style="text-align: center; margin-bottom: 50px;">
		<h1>Welcome to the CUSTOMER PAGE, <span style="color: blue;"><?php echo $_SESSION['username']; ?></span></h1>
		<a href="index.php">Home</a>
		<a href="profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">Profile</a>
		<a href="inquiries.php">Inquiries</a>
		<!-- <a href="send-an-inquiry.php">Send An Inquiry</a> -->
		<a href="core/handleForms.php?logoutUserBtn=1">Logout</a>
	</div>
</body>
</html>



