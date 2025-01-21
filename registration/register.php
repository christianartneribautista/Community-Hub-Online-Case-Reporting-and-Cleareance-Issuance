<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../pages/connection.php"; // Include your database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['btn_add'])) {
    // Sanitize and validate input
    
    $txt_lname = mysqli_real_escape_string($con, $_POST['txt_lname']);
    $txt_fname = mysqli_real_escape_string($con, $_POST['txt_fname']);
    $txt_mname = mysqli_real_escape_string($con, $_POST['txt_mname']);
    $ddl_gender = mysqli_real_escape_string($con, $_POST['ddl_gender']);
    $txt_bdate = mysqli_real_escape_string($con, $_POST['txt_bdate']);
    $txt_age = mysqli_real_escape_string($con, $_POST['txt_age']); // Get age from POST
    $txt_bplace = mysqli_real_escape_string($con, $_POST['txt_bplace']);
    $txt_email = mysqli_real_escape_string($con, $_POST['txt_email']);
    $txt_length = mysqli_real_escape_string($con, $_POST['txt_length']);
    $txt_religion = mysqli_real_escape_string($con, $_POST['txt_religion']);
    $txt_mstatus = mysqli_real_escape_string($con, $_POST['txt_mstatus']);
    $txt_zone = mysqli_real_escape_string($con, $_POST['txt_zone']);
    $txt_faddress = mysqli_real_escape_string($con, $_POST['txt_faddress']);
    $txt_uname = mysqli_real_escape_string($con, $_POST['txt_uname']);
    $txt_upass = mysqli_real_escape_string($con, $_POST['txt_upass']);
    $date = date('Y-m-d');  // Get the current date
    $time = (new DateTime())->format('h:i A');  // 12-hour format without seconds and with AM/PM
    $status = "pending";
    // Validate image upload
    $name = basename($_FILES['txt_image']['name']);
    $temp = $_FILES['txt_image']['tmp_name'];
    $imagetype = $_FILES['txt_image']['type'];
    $size = $_FILES['txt_image']['size'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/bmp'];

    // Check if the image type and size are valid
    if (!in_array($imagetype, $allowed_types) || $size > 2048000) {
        $_SESSION['error'] = "Invalid image type or size.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    // Create a unique filename for the image
    $milliseconds = round(microtime(true) * 1000);
    $image = $milliseconds . '_' . $name;

    // Check for duplicate username
    $stmt = $con->prepare("SELECT * FROM tblresident WHERE username = ?");
    $stmt->bind_param("s", $txt_uname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['duplicateuser'] = 1;
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    // Ensure the image directory exists
    if (!is_dir('image/')) {
        mkdir('image/', 0755, true);
    }

    // Move uploaded file
    if (move_uploaded_file($temp, 'image/' . $image)) {
        // Insert data into the database
        $stmt = $con->prepare("INSERT INTO tblresident (timeRecorded, dateRecorded, lname, fname, mname, bdate, age, bplace, emailAdd, lengthofstay, religion, gender, maritalstatus, zone, formerAddress, image, username, password, ustatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssssssss", $time, $date, $txt_lname, $txt_fname, $txt_mname, $txt_bdate, $txt_age, $txt_bplace, $txt_email,$txt_length, $txt_religion, $ddl_gender, $txt_mstatus, $txt_zone, $txt_faddress, $image, $txt_uname, $txt_upass, $status);

        // Execute the statement and check for errors
        if ($stmt->execute()) {
            $_SESSION['added'] = 1;
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            $_SESSION['error'] = "Error in registration: " . $stmt->error; // Capture the error
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    } else {
        $_SESSION['error'] = "Failed to upload image: " . $_FILES['txt_image']['error']; // Capture the error
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}
?>

<?php
include "../pages/connection.php"; // Include your database connection

// Check for session messages
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$added_message = isset($_SESSION['added']) ? "Registration successful! Please wait for admin approval." : '';
$duplicate_user_message = isset($_SESSION['duplicateuser']) ? "Username already exists." : '';

// Clear session messages
unset($_SESSION['error']);
unset($_SESSION['added']);
unset($_SESSION['duplicateuser']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="icon" href="img/logo.jfif" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General body styling */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #6c5ce7, #2980b9);
            background-attachment: fixed;
            background-size: cover;
            color: #333;
            overflow-x: hidden;
            height: 100vh;
        }

        /* Title container styling */
        .title-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            text-align: center;
            color: #fff;
        }

        /* Styling for the form */
        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .input-size {
            width: 100%;
        }

        /* Custom footer styling */
        footer {
            text-align: center;
            padding: 8px 15px;
            background-color: #34495e;
            color: #fff;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- Main Title Container -->
<div class="title-container">
    <h2 class="text-white">Registration</h2>
</div>

<div class="container">
    <div class="form-container">
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($added_message): ?>
            <div class="alert alert-success"><?php echo $added_message; ?></div>
        <?php endif; ?>
        <?php if ($duplicate_user_message): ?>
            <div class="alert alert-warning"><?php echo $duplicate_user_message; ?></div>
        <?php endif; ?>

        <form class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Name:</label><br>
                        <div class="col-sm-4">
                            <input name="txt_lname" class="form-control input-sm" type="text" placeholder="Lastname" required/>
                        </div>
                        <div class="col-sm-4">
                            <input name="txt_fname" class="form-control input-sm" type="text" placeholder="Firstname" required/>
                        </div>
                        <div class="col-sm-4">
                            <input name="txt_mname" class="form-control input-sm" type="text" placeholder="Middlename" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Birthdate:</label>
                        <input name="txt_bdate" class="form-control input-sm input-size" type="date" placeholder="Birthdate" required/>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Age:</label>
                        <input name="txt_age" class="form-control input-sm input-size" type="number" placeholder="Age" required/>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Email Address:</label>
                        <input name="txt_email" class="form-control input-sm input-size" type="email" placeholder="Email Address" required/>
                    </div>

                    <div class="
                    <div class="form-group">
                        <label class="control-label">Length of Stay: (in Months)</label><br>
                        <input name="txt_length" class="form-control input-sm input-size" type="number" min="0" placeholder="Length of Stay" required/>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Religion:</label>
                        <input name="txt_religion" class="form-control input-sm input-size" type="text" placeholder="Religion" required/>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Username:</label>
                        <input name="txt_uname" id="username" class="form-control input-sm input-size" type="text" placeholder="Username" required/>
                        <label id="user_msg" style="color:#CC0000;"></label>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Password:</label>
                        <input name="txt_upass" class="form-control input-sm" type="password" placeholder="Password" required/>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">     
                        <label class="control-label">Sex</label>
                        <select name="ddl_gender" class="form-control input-sm" required>
                            <option selected="" disabled="">-Select Gender-</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="LGBTQ+">LGBTQ+</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Birthplace:</label>
                        <input name="txt_bplace" class="form-control input-sm" type="text" placeholder="Birthplace" required/>
                    </div> 

                    <div class="form-group">
                        <label class="control-label">Marital Status:</label>
                        <select name="txt_mstatus" class="form-control input-sm" required>
                            <option value="" selected disabled>- Select Marital Status -</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Separated">Separated</option>
                            <option value="Annulled">Annulled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Zone #:</label>
                        <select name="txt_zone" class="form-control input-sm" required>
                            <option value="" disabled selected>Select Zone</option>
                            <option value="1">Zone/Purok 1</option>
                            <option value="2">Zone/Purok 2</option>
                            <option value="3">Zone/Purok 3</option>
                            <option value="4">Zone/Purok 4</option>
                            <option value="5">Zone/Purok 5</option>
                            <option value="6">Zone/Purok 6</option>
                            <option value="7">Zone/Purok 7</option>
                            <option value="8">Zone/Purok 8</option>
                            <option value="9">Zone/Purok 9</option>
                            <option value="10">Zone/Purok 10</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Former Address:</label>
                        <input name="txt_faddress" class="form-control input-sm" type="text" placeholder="Former Address" required/>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Image:</label>
                        <input name="txt_image" class="form-control input-sm" type="file" accept="image/*" required/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default btn-sm" value="Cancel" onclick="window.location.href='../main/index.php';"/>
                <input type="submit" class="btn btn-primary btn-sm" name="btn_add" id="btn_add" value="Submit"/>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Function to compute the age based on the birthdate
        function computeAge(birthDate) {
            var today = new Date();
            var birthDate = new Date(birthDate);
            var age = today.getFullYear() - birthDate.getFullYear();
            var month = today.getMonth() - birthDate.getMonth();
            
            if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            return age;
        }

        // Event listener for when the birthdate field is updated
        $("input[name='txt_bdate']").on('change', function() {
            var birthdate = $(this).val();  // Get the value of the birthdate input
            if (birthdate) {
                var age = computeAge(birthdate);  // Compute the age
                $("input[name='txt_age']").val(age);  // Set the computed age in the age input field
            }
        });

        // Existing code for username validation
        var timeOut = null; // this used for hold few seconds to make ajax request
        var loading_html = '<img src="../../img/ajax-loader.gif" style="height: 20px; width: 20px;"/>'; // just a loading image or we can put any texts here

        $('#username').keyup(function(e) {
            switch(e.keyCode) {
                case 9:   // tab
                case 13:  // enter
                case 16:  // shift
                case 17:  // ctrl
                case 18:  // alt
                case 19:  // pause/break
                case 20:  // caps lock
                case 27:  // escape
                case 33:  // page up
                case 34:  // page down
                case 35:  // end
                case 36:  // home
                case 37:  // left arrow
                case 38:  // up arrow
                case 39:  // right arrow
                case 40:  // down arrow
                case 45:  // insert
                    return;
            }
            if (timeOut != null) clearTimeout(timeOut);
            timeOut = setTimeout(is_available, 500);  // delay ajax request for 500 milliseconds
            $('#user_msg').html(loading_html); // adding the loading text or image
        });

        function is_available() {
            var username = $('#username').val();
            if (username.length === 0) {
                $('#user_msg').html(''); // Clear message if username is empty
                return;
            }

            $.post("check_username.php", { username: username }, function(result) {
                if(result != 0) {
                    $('#user_msg').html('Not Available');
                    document.getElementById("btn_add").disabled = true;
                } else {
                    $('#user_msg').html('<span style="color:#006600;">Available</span>');
                    document.getElementById("btn_add").disabled = false;
                }
            });
        }
    });
</script>
</body>
</html>