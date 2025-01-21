<?php

// Start the session to use $_SESSION variables
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started yet
}

// Include the database connection file
include('../connection.php');

// Set a maximum file size (in bytes) for the uploaded photo (example: 10MB)
define('MAX_FILE_SIZE', 10485760); // 10MB

// Handle adding new blotter record
if (isset($_POST['btn_add'])) {
    // Check if the POST variables are set before using them
    $txt_cname = $_POST['txt_cname'] ?? null;
    $txt_cage = $_POST['txt_cage'] ?? null;
    $txt_cadd = $_POST['txt_cadd'] ?? null;
    $txt_ccontact = $_POST['txt_ccontact'] ?? null;

    $txt_pname = $_POST['txt_pname'] ?? null;
    $txt_page = $_POST['txt_page'] ?? null;
    $txt_padd = $_POST['txt_padd'] ?? null;
    $txt_pcontact = $_POST['txt_pcontact'] ?? null;

    $txt_complaint = $_POST['txt_complaint'] ?? null;
    $ddl_acttaken = $_POST['ddl_acttaken'] ?? null;
    $ddl_stat = "Unsolved";
    $txt_location = $_POST['txt_location'] ?? null;
    $year = date('Y');
    $date = date('Y-m-d');
    $time = (new DateTime())->format('h:i:s A');
    
    // Handle the file upload
    if (isset($_FILES['complaint_photo']) && $_FILES['complaint_photo']['error'] == 0) {
        // Check if the file size is within the limit
        if ($_FILES['complaint_photo']['size'] > MAX_FILE_SIZE) {
            header("Location: your_page.php?status=error&message=File size exceeds the maximum limit.");
            exit();
        }

        // Get file extension and create a unique file name
        $file_extension = pathinfo($_FILES['complaint_photo']['name'], PATHINFO_EXTENSION);
        
        // Allow only image types (JPG, JPEG, PNG, GIF)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            header("Location: your_page.php?status=error&message=Invalid file type.");
            exit();
        }

        // Create a unique file name
        $photo = uniqid('blotter_') . '.' . $file_extension;
        
        // Move the uploaded file to the 'uploads' folder
        $upload_dir = 'uploads/';
        $upload_path = $upload_dir . $photo;

        // Ensure 'uploads' folder exists and is writable
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);  // Create 'uploads' folder if it doesn't exist
        }

        // Move the uploaded file to the 'uploads' directory
        if (move_uploaded_file($_FILES['complaint_photo']['tmp_name'], $upload_path)) {
            // Split the name by comma (LastName, FirstName MiddleName)
            $name_parts = explode(",", trim($txt_pname));
            $last_name = trim($name_parts[0] ?? '');
            $first_middle_names = trim($name_parts[1] ?? '');

            // Split the first and middle names by space
            $first_middle_parts = explode(" ", $first_middle_names);
            $first_name = trim($first_middle_parts[0] ?? '');
            $middle_name = trim($first_middle_parts[1] ?? '');

            // Fetch the ID of the person being complained about
            $query = mysqli_query($con, "
                SELECT id 
                FROM tblresident 
                WHERE LOWER(fname) = LOWER ('$first_name') 
                  AND LOWER(lname) = LOWER('$last_name')
            ");

            if (mysqli_num_rows($query) > 0) {
                $row = mysqli_fetch_assoc($query);
                $res_id = $row['id']; // Person to complain about ID
            } else {
                header("Location: your_page.php?status=error&message=Person to complain about not found.");
                exit();
            }

            // Log action
            if (isset($_SESSION['role'])) {
                $action = 'Added Blotter Request by ' . $txt_cname;
                mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('" . $_SESSION['role'] . "', NOW(), '" . $action . "')");
            }

            // Insert the new blotter record including the file path of the photo and the correct res_id
           $query = mysqli_query($con, "INSERT INTO tblblotter 
    (timeRecorded, yearRecorded, dateRecorded, complainant, cage, caddress, ccontact, personToComplain, page, paddress, pcontact, complaint, actionTaken, sStatus, locationOfIncidence, recordedby, res_id, photo)
    VALUES 
    ('$time', '$year', '$date', '$txt_cname', '$txt_cage', '$txt_cadd', '$txt_ccontact', '$txt_pname', '$txt_page', '$txt_padd', '$txt_pcontact', '$txt_complaint', '$ddl_acttaken', '$ddl_stat', '$txt_location', '" . $_SESSION['username'] . "', '$res_id', '$photo')") or die(mysqli_error($con));
   

            // Set session variable for success
            $_SESSION['added'] = true;

            // Redirect to a specific page without POST data
            header("Location: blotter.php");
            exit();
        } else {
            header("Location: blotter.php?status=error&message=Failed to move uploaded file.");
            exit();
        }
    } else {
        header("Location: blotter.php?status=error&message=No file uploaded.");
        exit();
    }
}

// Handle saving/editing an existing blotter record
if (isset($_POST['btn_save'])) {
    // Use prepared statements to prevent SQL injection
    $txt_id = $_POST['hidden_id'] ?? null;
    $txt_edit_cname = $_POST['txt_edit_cname'] ?? null;
    $txt_edit_cage = $_POST['txt_edit_cage'] ?? null;
    $txt_edit_cadd = $_POST['txt_edit_cadd'] ?? null;
    $txt_edit_ccontact = $_POST['txt_edit_ccontact'] ?? null;

    $txt_edit_pname = $_POST['txt_edit_pname'] ?? null;
    $txt_edit_page = $_POST['txt_edit_page'] ?? null;
    $txt_edit_padd = $_POST['txt_edit_padd'] ?? null;
    $txt_edit_pcontact = $_POST['txt_edit_pcontact'] ?? null;

    $txt_edit_complaint = $_POST['txt_edit_complaint'] ?? null;
    $ddl_edit_acttaken = $_POST['ddl_edit_acttaken'] ?? null; // Ensure this field is in your form
    $ddl_edit_stat = $_POST['ddl_edit_stat'] ?? null;
    $txt_edit_location = $_POST['txt_edit_location'] ?? null;

    // Prepare the update query
    $update_query = mysqli_prepare($con, "UPDATE tblblotter SET 
        complainant = ?, 
        cage = ?, 
        caddress = ?, 
        personToComplain = ?, 
        page = ?, 
        paddress = ?, 
        pcontact = ?, 
        complaint = ?, 
        actionTaken = ?, 
        sStatus = ?, 
        locationOfIncidence = ? 
        WHERE id = ?");

    if ($update_query) {
        // Bind parameters
        mysqli_stmt_bind_param($update_query, 'sssssssssssi', 
            $txt_edit_cname, 
            $txt_edit_cage, 
            $txt_edit_cadd, 
            $txt_edit_pname, 
            $txt_edit_page, 
            $txt_edit_padd, 
            $txt_edit_pcontact, 
            $txt_edit_complaint, 
            $ddl_edit_acttaken, // Ensure this is included
            $ddl_edit_stat, 
            $txt_edit_location, 
            $txt_id
        );

        // Execute the update query
        if (mysqli_stmt_execute($update_query)) {
            // Log the action if the user role is set
            if (isset($_SESSION['role'])) {
                $action = 'Update Blotter Request by ' . $txt_edit_cname;
                mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('" . $_SESSION['role'] . "', NOW(), '" . $action . "')");
            }

            // Set session variable and redirect
            $_SESSION['edited'] = 1;
            header("Location: {$_SERVER['REQUEST_URI']}?status=success&message=Blotter record updated successfully!");
            exit();
        } else {
            // Handle error
            error_log('Update Error: ' . mysqli_error($con)); // Log the error
            echo "An error occurred while updating the record. Please try again.";
        }

        // Close the prepared statement
        mysqli_stmt_close($update_query);
    } else {
        // Handle error in preparing the statement
        error_log('Prepare Error: ' . mysqli_error($con)); // Log the error
        echo "An error occurred while preparing the update statement. Please try again.";
    }
}
// Handle deleting a blotter record
if (isset($_POST['btn_delete'])) {
    if (isset($_POST['chk_delete'])) {
        foreach ($_POST['chk_delete'] as $value) {
            $delete_query = mysqli_query($con, "DELETE FROM tblblotter WHERE id = '$value'") or die('Error: ' . mysqli_error($con));

            if (isset($_SESSION['role'])) {
                $action = 'Deleted Blotter Request ID: ' . $value;
                mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('" . $_SESSION['role'] . "', NOW(), '" . $action . "')");
            }
        }

        header("Location: {$_SERVER['REQUEST_URI']}?status=success&message=Selected blotter records deleted.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blotter Management</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your stylesheet here -->
</head>
<body>
    <div class="container">
        <?php
        // Check for the status query parameter in the URL
        if (isset($_GET['status'])) {
            $status = $_GET['status'];  // Either 'success' or 'error'
            $message = $_GET['message'] ?? '';

            if ($status == 'success') {
                echo "<div class='alert alert-success'>$message</div>";
            } elseif ($status == 'error') {
                echo "<div class='alert alert-danger'>$message</div>";
            }
        }
        ?>
        
        <!-- Your form for adding/editing blotter records -->
        <!-- Additional code for editing and deleting records can be added here -->
    </div>
</body>
</html>