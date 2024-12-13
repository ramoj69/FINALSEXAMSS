<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['branch_id']) && isset($_GET['status'])) {
    $branch_id = $_GET['branch_id'];
    $status = $_GET['status'];

    // Validate the status
    if (!in_array($status, ['accepted', 'declined'])) {
        $_SESSION['message'] = "Invalid status!";
        $_SESSION['status'] = 400;
        header("Location: index.php");
        exit();
    }

    // Update the status in the database
    $stmt = $pdo->prepare("UPDATE branches SET status = :status WHERE branch_id = :branch_id");
    $stmt->execute([':status' => $status, ':branch_id' => $branch_id]);

    if ($stmt->rowCount()) {
        $_SESSION['message'] = "Branch status updated successfully.";
        $_SESSION['status'] = 200;
    } else {
        $_SESSION['message'] = "Failed to update branch status.";
        $_SESSION['status'] = 400;
    }

    header("Location: index.php");
    exit();
}
?>
