<?php
session_start(); // Start the session to use session variables
include "../connection.php"; // Include your database connection

if (isset($_POST['id'])) {
    $residentId = mysqli_real_escape_string($con, $_POST['id']);
    
    // Update the resident's status to approved
    $query = "UPDATE tblresident SET ustatus = 'approved' WHERE id = '$residentId'";
    if (mysqli_query($con, $query)) {
        $_SESSION['notification'] = "Resident approved successfully."; // Success message
        $_SESSION['notification_type'] = "success"; // Type of notification

        // Redirect to a specific page without POST data
        header("Location: resident.php");
        exit();
    } else {
        $_SESSION['notification'] = "Error: " . mysqli_error($con); // Error message
        $_SESSION['notification_type'] = "error"; // Type of notification
        header("Location: resident.php");
        exit();
    }
} else {
    $_SESSION['notification'] = "No ID provided."; // Error message
    $_SESSION['notification_type'] = "error"; // Type of notification
    header("Location: resident.php");
    exit();
}
?>