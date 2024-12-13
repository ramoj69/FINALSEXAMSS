<?php


// Check if the form has been submitted by checking if the 
// 'insertTextFileBtn' is set in the POST request
// if (isset($_POST['insertTextFileBtn'])) {

//     // Output the start of a preformatted text 
//     echo "<pre>";
    
//     // Print the details of the uploaded file from 
//     // the $_FILES superglobal array
//     print_r($_FILES['textFile']);
    
//     // Close the preformatted text block
//     echo "<pre>";

//     // Store the uploaded file information in a submittedFile variable
//     $submittedFile = $_FILES['textFile'];

//     // Output the name of the uploaded file
//     echo $_FILES['textFile']['name'] . "<br>";
    
//     // Output the size of the uploaded file in bytes
//     echo $_FILES['textFile']['size'] . "<br>";
// }

if (isset($_POST['insertTextFileBtn'])) {

	// Get file name
	$fileName = $_FILES['textFile']['name'];

	// Get temporary file name
	$tempFileName = $_FILES['textFile']['tmp_name'];

	// Get file extension
	$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

	// Generate random characters for image name
	$uniqueID = sha1(md5(rand(1,9999999)));

	// Combine image name and file extension
	$imageName = $uniqueID.".".$fileExtension;

	// Specify path
	$folder = "files/".$imageName;

	// Move file to the specified path 
	if (move_uploaded_file($tempFileName, $folder)) {
		$_SESSION['message'] = "File saved successfully!";
		header("Location: index.php");
	}
	
}