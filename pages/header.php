<?php
// Start output buffering at the beginning of the file
ob_start();

// Check if user is logged in
if (!isset($_SESSION['role'])) {
    header("Location: ../../login.php");
    exit();
}

include('../connection.php');

// Fetch unread notifications count
$user_id = mysqli_real_escape_string($con, $_SESSION['userid']);
$notification_count_query = "SELECT COUNT(*) AS count FROM notifications WHERE user_id = '$user_id' AND status = 'unread'";
$result = mysqli_query($con, $notification_count_query);
$notification_count = mysqli_fetch_assoc($result)['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Information System</title>
    <link rel="stylesheet" href="../path/to/bootstrap.min.css"> <!-- Update path -->
    <link rel="stylesheet" href="../path/to/font-awesome.min.css"> <!-- Update path -->
    <style>
   #notification-bell {
    position: relative;
    font-size: 18px; /* Adjust icon size */
    margin-top: -7px;
}

#notification-count {
    position: absolute;
    top: 1px; /* Adjust as needed to align with the bell icon */
    right: 0px; /* Adjust as needed to align with the bell icon */
    color: #fff; /* White text color */
    background-color: red; /* Red background color */
    border-radius: 50%; /* Circle shape */
    padding: 2px 6px; /* Adjusted padding for better alignment */
    font-size: 12px; /* Adjusted font size */
    font-weight: bold; /* Bold text */
    min-width: 18px; /* Adjusted width */
    text-align: center; /* Center text */
    line-height: 1; /* Align text vertically */
}

</style>


    <script src="../path/to/jquery.min.js"></script> <!-- Update path -->
    <script src="../path/to/bootstrap.min.js"></script> <!-- Update path -->
</head>
<body class="skin-black">
    <header class="header">
        <a href="dashboard/dashboard.php" class="logo">
            Community Hub  
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    <?php if ($_SESSION['role'] != 'Administrator' && $_SESSION['role'] != 'Staff'): ?>
                        <!-- Notification Bell -->
                        <li class="notifications-menu">
                            <a href="../clearance/notification.php" class="navbar-btn">
                                <i id="notification-bell" class="glyphicon glyphicon-bell"></i>
                                <span id="notification-count"><?php echo $notification_count; ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-user"></i><span><?php echo $_SESSION['role']; ?><i class="caret"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header bg-light-blue">
                                <p><?php echo $_SESSION['role']; ?></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat" data-toggle="modal" data-target="#editProfileModal">Change Account</a>
                                </div>
                                <div class="pull-right">
                                    <a href="../../logout.php" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal fade">
        <form method="post">
            <div class="modal-dialog modal-sm" style="width:300px !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Change Account</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if ($_SESSION['role'] == "Administrator") {
                                    $user = mysqli_query($con, "SELECT * FROM tbluser WHERE id = '" . $_SESSION['userid'] . "'");
                                    while ($row = mysqli_fetch_array($user)) {
                                        echo '
                                            <div class="form-group">
                                                <label>Username:</label>
                                                <input name="txt_username" id="txt_username" class="form-control input-sm" type="text" value="' . $row['username'] . '" />
                                            </div>
                                            <div class="form-group">
                                                <label>Password:</label>
                                                <input name="txt_password" id="txt_password" class="form-control input-sm" type="password"  value="' . $row['password'] . '"/>
                                            </div>';
                                    }
                                } elseif ($_SESSION['role'] == "Zone Leader") {
                                    $user = mysqli_query($con, "SELECT * FROM tblzone WHERE id = '" . $_SESSION['userid'] . "'");
                                    while ($row = mysqli_fetch_array($user)) {
                                        echo '
                                            <div class="form-group">
                                                <label>Username:</label>
                                                <input name="txt_username" id="txt_username" class="form-control input-sm" type="text" value="' . $row['username'] . '" />
                                            </div>
                                            <div class="form-group">
                                                <label>Password:</label>
                                                <input name="txt_password" id="txt_password" class="form-control input-sm" type="password"  value="' . $row['password'] . '"/>
                                            </div>';
                                    }
                                } elseif ($_SESSION['role'] == "Staff") {
                                    $user = mysqli_query($con, "SELECT * FROM tblstaff WHERE id = '" . $_SESSION['userid'] . "'");
                                    while ($row = mysqli_fetch_array($user)) {
                                        echo '
                                            <div class="form-group">
                                                <label>Username:</label>
                                                <input name="txt_username" id="txt_username" class="form-control input-sm" type="text" value="' . $row['username'] . '" />
                                            </div>
                                            <div class="form-group">
                                                <label>Password:</label>
                                                <input name="txt_password" id="txt_password" class="form-control input-sm" type="password"  value="' . $row['password'] . '"/>
                                            </div>';
                                    }
                                } else {
                                    $user = mysqli_query($con, "SELECT * FROM tblresident WHERE id = '" . $_SESSION['userid'] . "'");
                                    while ($row = mysqli_fetch_array($user)) {
                                        echo '
                                            <div class="form-group">
                                                <label>Username:</label>
                                                <input name="txt_username" id="txt_username" class="form-control input-sm" type="text" value="' . $row['username'] . '" />
                                            </div>
                                            <div class="form-group">
                                                <label>Password:</label>
                                                <!-- Password field removed for residents -->
                                            </div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel" />
                        <input type="submit" class="btn btn-primary btn-sm" id="btn_saveeditProfile" name="btn_saveeditProfile" value="Save" />
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['btn_saveeditProfile'])) {
        $username = $_POST['txt_username'];
        $password = $_POST['txt_password'];

        if ($_SESSION['role'] == "Administrator") {
            $updadmin = mysqli_query($con, "UPDATE tbluser SET username = '$username', password = '$password' WHERE id = '" . $_SESSION['userid'] . "'");
            if ($updadmin) {
                header("location: " . $_SERVER['REQUEST_URI']);
            }
        } elseif ($_SESSION['role'] == "Zone Leader") {
            $updzone = mysqli_query($con, "UPDATE tblzone SET username = '$username', password = '$password' WHERE id = '" . $_SESSION['userid'] . "'");
            if ($updzone) {
                header("location: " . $_SERVER['REQUEST_URI']);
            }
        } elseif ($_SESSION['role'] == "Staff") {
            $updstaff = mysqli_query($con, "UPDATE tblstaff SET username = '$username', password = '$password' WHERE id = '" . $_SESSION['userid'] . "'");
            if ($updstaff) {
                header("location: " . $_SERVER['REQUEST_URI']);
            }
        }
    }
    ?>

    <!-- JavaScript for notifications -->
    <script>
    function loadNotifications() {
        fetch('/path/to/notifications/api') // Replace with your actual API endpoint
            .then(response => response.json())
            .then(data => {
                const count = data.notifications.length;
                document.getElementById('notification-count').textContent = count;
            });
    }

    document.addEventListener('DOMContentLoaded', loadNotifications);

    
    </script>
</body>
</html>

<?php
// End output buffering and flush the output
ob_end_flush();
?>
