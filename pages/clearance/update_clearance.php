<?php
// Database connection parameters
$servername = "localhost"; // Your database server
$username = "root";     // Your database username
$password = "";     // Your database password
$dbname = "db_barangay";       // Your database name

// Create a connection to the database
$connection = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get the clearance ID from the AJAX request
$clearanceId = isset($_POST['id']) ? intval($_POST['id']) : 0;
error_log("Received clearance ID: " . $clearanceId); // Log the ID for debugging    

if ($clearanceId > 0) {
    // Function to update the clearance status to "issued"
    function updateClearanceStatusToIssued($connection, $clearanceId) {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $connection->prepare("UPDATE tblclearance SET status = ? WHERE id = ?");
        
        // Check if the statement was prepared successfully
        if (!$stmt) {
            error_log("Prepare failed: (" . $connection->errno . ") " . $connection->error);
            return false;
        }

        // Set the status to 'Issued'
        $status = 'Issued';
        
        // Bind parameters
        $stmt->bind_param("si", $status, $clearanceId);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0) {
                return true; // Update successful
            } else {
                error_log("No rows updated for ID: " . $clearanceId); // Log for debugging
                return false; // No rows updated (ID may not exist)
            }
        } else {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error); // Log the SQL error
            return false; // Update failed
        }
        
        // Close the statement
        $stmt->close();
    }

    // Call the function to update the status
    if (updateClearanceStatusToIssued($connection, $clearanceId)) {
        echo "Clearance status updated to 'issued' successfully.";
    } else {
        echo "Failed to update clearance status.";
    }
} else {
    echo "Invalid clearance ID.";
}

// Close the database connection
$connection->close();
?>