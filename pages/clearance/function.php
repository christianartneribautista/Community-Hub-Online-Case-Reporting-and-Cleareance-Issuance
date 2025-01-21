<?php

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the 'Add' button was pressed
if (isset($_POST['btn_add'])) {
    // Retrieve form inputs
    $ddl_resident = $_POST['ddl_resident'];
    $txt_typeOfClearance = $_POST['txt_TOC'];
    $txt_purpose = $_POST['txt_purpose'];
    $txt_ornum = $_POST['txt_ornum'];
    $date = date('Y-m-d');  // Get the current date
    $time = (new DateTime())->format('h:i A');  // 12-hour format without seconds and with AM/PM

    // Debugging: check if time is correct
    //echo "Time: $time";  // Debugging step to check time format

    // Log the action if a user role is set
    if (isset($_SESSION['role'])) {
        $action = 'Added Clearance with clearance number of ' . $txt_ornum;
        $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('" . $_SESSION['role'] . "', NOW(), '" . $action . "')");
    }

    // Retrieve the amount from the database based on the type of clearance
    $amountQuery = mysqli_query($con, "SELECT amount FROM clearances_and_certificates WHERE clearance_certificate = '$txt_typeOfClearance'");
    
    if ($amountQuery && mysqli_num_rows($amountQuery) > 0) {
        $row = mysqli_fetch_assoc($amountQuery);
        $txt_amount = $row['amount'];  // Get the amount from the database
    } else {
        echo "Error: Clearance type not found or amount not set.";  // Handle error if clearance type is not found
        exit();
    }

    // Check if the user is an Administrator
    if ($_SESSION['role'] == "Administrator") {
        // Administrator query
        $query = mysqli_query($con, "INSERT INTO tblclearance 
            (residentid, type_of_clearance, purpose, orNo, samount, timeRecorded, dateRecorded, recordedBy, status) 
            VALUES ('$ddl_resident', '$txt_typeOfClearance', '$txt_purpose', '$txt_ornum', '$txt_amount', '$time', '$date', '" . $_SESSION['username'] . "', 'Approved')");

        if (!$query) {
            echo "Error: " . mysqli_error($con);  // Capture MySQL error
            exit();  // Stop script execution for debugging
        }

    } else {
        // Non-administrator query
        $query = mysqli_query($con, "INSERT INTO tblclearance
            (residentid, type_of_clearance, purpose, orNo, samount, dateRecorded, timeRecorded, recordedBy, status) 
            VALUES ('$ddl_resident', '$txt_typeOfClearance', '$txt_purpose', '$txt_ornum', '$txt_amount', '$date', '$time', '" . $_SESSION['username'] . "', 'New')");

        if (!$query) {
            echo "Error: " . mysqli_error($con);  // Capture MySQL error
            exit();  // Stop script execution for debugging
        }
    }

    // Check if the query was successful
    if ($query) {
        $_SESSION['added'] = 1;
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        // Set session variable for failure and redirect
        $_SESSION['duplicate'] = 1;
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}
// Check if the 'Request' button was pressed
 $time = (new DateTime())->format('h:i A');  // 12-hour format with AM/PM
if (isset($_POST['btn_req'])) {
    $txt_typeOfClearance = $_POST['txt_TOC'];  // Retrieve this from POST data

    // Check if the user has any pending complaints in tblblotter
    $chkblot = mysqli_query($con, "SELECT * FROM tblblotter WHERE res_id = '" . $_SESSION['userid'] . "' AND sStatus = 'unsolved'");
    
    // If there are pending complaints (assuming the status is 'pending')
    if (mysqli_num_rows($chkblot) > 0) {
        // Set session variable for blotter check failure and redirect
        $_SESSION['blotter'] = 1;
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Get resident details
    $chk = mysqli_query($con, "SELECT * FROM tblresident WHERE id = '" . $_SESSION['userid'] . "'");
    while ($row = mysqli_fetch_array($chk)) {
        // Check if the length of stay is less than 6 months
        if ($row['lengthofstay'] < 6) {
            $_SESSION['lengthofstay'] = 1;
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $txt_purpose = $_POST['txt_purpose'];
            $date = date('Y-m-d');

            // Retrieve the highest clearanceNo currently in use
            $maxQuery = mysqli_query($con, "SELECT MAX(clearanceNo) as max_clearanceNo FROM tblclearance");
            $maxRow = mysqli_fetch_array($maxQuery);
            $new_clearanceNo = $maxRow['max_clearanceNo'] + 1;

            // Optionally handle orNo similarly if it needs to be unique and incremented
            $maxOrNoQuery = mysqli_query($con, "SELECT MAX(orNo) as max_orNo FROM tblclearance");
            $maxOrNoRow = mysqli_fetch_array($maxOrNoQuery);
            $new_orNo = $maxOrNoRow['max_orNo'] + 1;

            // Insert new clearance request with incremented clearanceNo and orNo
            $reqquery = mysqli_query($con, "INSERT INTO tblclearance (clearanceNo, residentid, type_of_clearance, purpose, orNo, timeRecorded, dateRecorded, recordedBy, status) 
                VALUES ('$new_clearanceNo', '" . $_SESSION['userid'] . "', '$txt_typeOfClearance', '$txt_purpose', '$new_orNo', '$time', '$date', '" . $_SESSION['role'] . "', 'New')") or die('Error: ' . mysqli_error($con));

            if ($reqquery) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            } 
        }
    }
}


// Check if the 'Approve' button was pressed

if (isset($_POST['btn_approve'])) {
    // Retrieve form inputs
    $txt_id = $_POST['hidden_id'];
    $txt_ornum  = $_POST['txt_ornum'];
    // Update clearance status to Approved
    $approve_query = mysqli_query($con, "UPDATE tblclearance 
                                         SET   
                                             orNo = '$txt_ornum', 
                                             samount = 80,
                                             status = 'Approved' 
                                         WHERE id = '$txt_id'") 
                     or die('Error: ' . mysqli_error($con));

    if ($approve_query) {
        // Retrieve the requester's ID
        $get_requester_query = mysqli_query($con, "SELECT residentid FROM tblclearance WHERE id = '$txt_id'") 
                             or die('Error fetching requester ID: ' . mysqli_error($con));
        
        if ($row = mysqli_fetch_assoc($get_requester_query)) {
            $user_id = $row['residentid'];
            
            // Get the requester’s email address
            $get_email_query = mysqli_query($con, "SELECT emailAdd FROM tblresident WHERE id = '$user_id'") 
                               or die('Error fetching email: ' . mysqli_error($con));
            $email_row = mysqli_fetch_assoc($get_email_query);
            $requester_email = $email_row['emailAdd'];

            // Create notification message
            $notification_message = mysqli_real_escape_string($con, "Dear Resident,<br>Your clearance request has been approved, you may visit at the barangay hall from monday to friday to obtain your clearance. Thank you");

            
            // Insert system notification
            $notification_query = mysqli_query($con, "INSERT INTO notifications (user_id, message) 
                                                      VALUES ('$user_id', '$notification_message')") 
                                 or die('Notification Error: ' . mysqli_error($con));

            if ($notification_query) { // Fixed 'iif' to 'if'
                // Include PHPMailer classes
                
                $mail = new PHPMailer(true); // Create a new PHPMailer instance

                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com'; // Corrected SMTP host
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'emialnotifacc@gmail.com'; // Your email
                    $mail->Password   = 'vaph tyjc kykw fdzo'; // Your email password
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port       = 465;

                    $mail->setFrom('emialnotifacc@gmail.com', 'Barangay Taboc');
                    $mail->addAddress($requester_email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Clearance Approved';
                    $mail->Body    = 'We are pleased to inform you that your clearance request has been approved. You may visit the Barangay Hall from Monday to Saturday during office hours to claim your clearance. Thank you for your attention, and should you require further assistance, please do not hesitate to reach out. Sincerely,
                        <br> <br> <br>
                        Mark Anthony Cabiladas <br>
                        Barangay Secretary <br>
                        Taboc, San Juan <br>
                        09123456789';
                    $mail->AltBody = 'Dear Resident, Your clearance request has been approved.';

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                // Redirect to the same page to refresh
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            } else {
                // Handle notification error
                die('Notification Error: ' . mysqli_error($con));
            }
        } else {
            // Handle case where requester's ID was not found
            die('Error: Requester ID not found.');
        }
    } else {
        // Handle update error
        die('Update Error: ' . mysqli_error($con));
    }
}


// Check if the 'Disapprove' button was pressed
if (isset($_POST['btn_disapprove'])) {
    // Retrieve form inputs
    $txt_id = $_POST['hidden_id'];
    // Update clearance status to Disapproved
    $disapprove_query = mysqli_query($con, "UPDATE tblclearance SET status = 'Disapproved' WHERE id = '$txt_id'") or die('Error: ' . mysqli_error($con));

    if ($disapprove_query == true) {
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
}

// Check if the 'Save' button was pressed
if (isset($_POST['btn_save'])) {
    // Retrieve form inputs
    $txt_id = $_POST['hidden_id'];
    $txt_edit_cnum = $_POST['txt_edit_cnum'];
    $txt_edit_purpose = $_POST['txt_edit_purpose'];
    $txt_edit_ornum = $_POST['txt_edit_ornum'];
    $txt_edit_amount = $_POST['txt_edit_amount'];

    // Update clearance details
    $update_query = mysqli_query($con, "UPDATE tblclearance SET clearanceNo = '$txt_edit_cnum', purpose = '$txt_edit_purpose', orNo = '$txt_edit_ornum', samount = '$txt_edit_amount' WHERE id = '$txt_id'") or die('Error: ' . mysqli_error($con));

    // Log the update action if a user role is set
    if (isset($_SESSION['role'])) {
        $action = 'Update Clearance with clearance number of ' . $txt_edit_cnum;
        $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('" . $_SESSION['role'] . "', NOW(), '" . $action . "')");
    }

    if ($update_query == true) {
        $_SESSION['edited'] = 1;
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
}

// Check if the 'Delete' button was pressed
if (isset($_POST['btn_delete'])) {
    if (isset($_POST['chk_delete'])) {
        // Initialize a variable to track if any deletion was successful
        $deleteSuccess = false;

        // Delete selected clearances
        foreach ($_POST['chk_delete'] as $value) {
            $delete_query = mysqli_query($con, "DELETE FROM tblclearance WHERE id = '$value'") or die('Error: ' . mysqli_error($con));

            if ($delete_query) {
                $deleteSuccess = true; // Mark as successful if at least one deletion was successful
            }
        }

        // Set session message based on deletion success
        if ($deleteSuccess) {
            $_SESSION['delete'] = 1; // Set a session variable to indicate success
        } else {
            $_SESSION['delete'] = 0; // Set a session variable to indicate failure
        }

        // Redirect to the same page to refresh the data
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        // Optionally, set a session message if no checkboxes were selected
        $_SESSION['delete'] = 0; // No records selected for deletion
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

//check if the issued button was clicked

    
?>
