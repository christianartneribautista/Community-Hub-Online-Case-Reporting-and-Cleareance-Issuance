<?php
include "../connection.php"; // Include your database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Loop through each row of data
    if (isset($_POST['clearance_certificate']) && isset($_POST['amount'])) {
        $clearanceCertificates = $_POST['clearance_certificate'];
        $amounts = $_POST['amount'];

        // Update each record
        foreach ($clearanceCertificates as $id => $clearanceCertificate) {
            $amount = $amounts[$id];

            // Sanitize data to prevent SQL injection
            $clearanceCertificate = mysqli_real_escape_string($con, $clearanceCertificate);
            $amount = mysqli_real_escape_string($con, $amount);

            // Update query
            $query = "UPDATE clearances_and_certificates 
                      SET clearance_certificate = '$clearanceCertificate', amount = '$amount' 
                      WHERE id = '$id'";

            // Execute the query
            if (!mysqli_query($con, $query)) {
                // Error occurred while updating
                echo "Error: " . mysqli_error($con);
                exit();
            }
        }

        // If successful, redirect with a success message
        header("Location: index.php?status=success");
        exit();
    } else {
        // If the form is not filled correctly
        echo "Error: Missing data.";
        exit();
    }
}
?>
