<?php
// Check if the 'Add' button was pressed
if(isset($_POST['btn_add'])){
    // Retrieve form data
    $ddl_pos = $_POST['ddl_pos'];
    $txt_cname = $_POST['txt_cname'];
    $txt_contact = $_POST['txt_contact'];
    $txt_address = $_POST['txt_address'];
    $txt_sterm = $_POST['txt_sterm'];
    $txt_eterm = $_POST['txt_eterm'];
    $date = date('Y-m-d'); // Get current date
    $time = (new DateTime())->format('h:i A'); // Get current time

    // Log the action if the user role is set
    if(isset($_SESSION['role'])){
        $action = 'Added Official named '.$txt_cname;
        $iquery = mysqli_query($con,"INSERT INTO tbllogs (user,logdate,action) values ('".$_SESSION['role']."', NOW(), '".$action."')");
    }

    // Check if the position is already occupied by an active official
    $q = mysqli_query($con,"SELECT * from tblofficial where sPosition = '".$ddl_pos."' and status = 'Ongoing Term'");
    $ct = mysqli_num_rows($q); // Count the number of rows returned

    // If the position is not occupied, insert the new official
    if($ct == 0){ // Change this condition to check for zero records
        $query = mysqli_query($con,"INSERT INTO tblofficial (timeRecorded, dateRecorded, sPosition, completeName, pcontact, paddress, termStart, termEnd, status) 
        values ('$time', '$date', '$ddl_pos', '$txt_cname', '$txt_contact', '$txt_address', '$txt_sterm', '$txt_eterm', 'Ongoing Term')") or die('Error: ' . mysqli_error($con));
        
        // If the insert was successful, set a session variable and refresh the page
        if($query == true)
        {
            $_SESSION['added'] = 1;
            header ("location: ".$_SERVER['REQUEST_URI']);
        }   
    }
    // If the position is already occupied, set a duplicate session variable
    else{
        $_SESSION['duplicate'] = 1;
        header ("location: ".$_SERVER['REQUEST_URI']);
    }
}

// Check if the 'Save' button was pressed
if(isset($_POST['btn_save']))
{
    // Retrieve form data for editing
    $txt_id = $_POST['hidden_id'];
    $txt_edit_cname = $_POST['txt_edit_cname'];
    $txt_edit_contact = $_POST['txt_edit_contact'];
    $txt_edit_address = $_POST['txt_edit_address'];
    $txt_edit_sterm = $_POST['txt_edit_sterm'];
    $txt_edit_eterm = $_POST['txt_edit_eterm'];

    // Log the action if the user role is set
    if(isset($_SESSION['role'])){
        $action = 'Update Official named '.$txt_edit_cname;
        $iquery = mysqli_query($con,"INSERT INTO tbllogs (user,logdate,action) values ('".$_SESSION['role']."', NOW(), '".$action."')");
    }

    // Update the official's details in the database
    $update_query = mysqli_query($con,"UPDATE tblofficial set completeName = '".$txt_edit_cname."', pcontact = '".$txt_edit_contact."', paddress = '".$txt_edit_address."', termStart = '".$txt_edit_sterm."', termEnd = '".$txt_edit_eterm."' where id = '".$txt_id."' ") or die('Error: ' . mysqli_error($con));

    // If the update was successful, set a session variable and refresh the page
    if($update_query == true){
        $_SESSION['edited'] = 1;
        header("location: ".$_SERVER['REQUEST_URI']);
    }
}

// Check if the 'End Term' button was pressed
if(isset($_POST['btn_end']))
{
    $txt_id = $_POST['hidden_id']; // Retrieve the ID of the official to end the term

    // Update the official's status to 'End Term'
    $end_query = mysqli_query($con,"UPDATE tblofficial set status = 'End Term' where id = '$txt_id' ") or die('Error: ' . mysqli_error($con));

    // If the update was successful, set a session variable and refresh the page
    if($end_query == true){
        $_SESSION['end'] = 1;
        header("location: ".$_SERVER['REQUEST_URI']);
    }
}

// Check if the 'Start Term' button was pressed
if(isset($_POST['btn_start']))
{
    $txt_id = $_POST['hidden_id']; // Retrieve the ID of the official to start the term

    // Update the official's status to 'Ongoing Term'
    $start_query = mysqli_query($con,"UPDATE tblofficial set status = 'Ongoing Term' where id = '$txt_id' ") or die('Error: ' . mysqli_error($con));

    // If the update was successful, set a session variable and refresh the page
    if($start_query == true){
        $_SESSION['start'] = 1;
        header("location: ".$_SERVER['REQUEST_URI']);
    }
}

// Check if the 'Delete' button was pressed
if(isset($_POST['btn_delete']))
{
    // Check if any checkboxes were selected for deletion
    if(isset($_POST['chk_delete']))
    {
 // Loop through each selected checkbox value
        foreach($_POST['chk_delete'] as $value)
        {
            // Delete the official from the database based on the selected ID
            $delete_query = mysqli_query($con,"DELETE from tblofficial where id = '$value' ") or die('Error: ' . mysqli_error($con));
                    
            // If the delete was successful, set a session variable and refresh the page
            if($delete_query == true)
            {
                $_SESSION['delete'] = 1;
                header("location: ".$_SERVER['REQUEST_URI']);
            }
        }
    }
}
?>