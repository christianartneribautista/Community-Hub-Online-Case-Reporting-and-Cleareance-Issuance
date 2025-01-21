<?php
// Start output buffering at the beginning of the file
ob_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Barangay Information System</title>
    <style>
    .notifications-menu {
        position: relative;
    }
    .notifications-menu .dropdown-menu {
        width: 250px;
    }
    .notifications-menu .menu {
        padding: 0;
        list-style: none;
    }
    .notifications-menu .menu li {
        border-bottom: 1px solid #ddd;
    }
    .notifications-menu .menu li a {
        display: block;
        padding: 10px;
    }
    </style>
</head>
<body>
<header class="header">
    <a href="#" class="logo">
        Barangay Information System
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
                <!-- Notification Bell -->
                    <?php if ($_SESSION['role'] == "Resident") : ?>
                    
                    <?php endif; ?>

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i><span><?php echo $_SESSION['role']; ?><i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header bg-light-blue">
                            <p>
                                <?php echo $_SESSION['role']; ?>
                            </p>
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
                                $user = mysqli_query($con, "SELECT * from tbluser where id = '" . $_SESSION['userid'] . "' ");
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
                                $user = mysqli_query($con, "SELECT * from tblzone where id = '" . $_SESSION['userid'] . "' ");
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
                                $user = mysqli_query($con, "SELECT * from tblstaff where id = '" . $_SESSION['userid'] . "' ");
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
                                $user = mysqli_query($con, "SELECT * from tblresident where id = '" . $_SESSION['userid'] . "' ");
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
        $updadmin = mysqli_query($con, "UPDATE tbluser set username = '$username', password = '$password' where id = '" . $_SESSION['userid'] . "' ");
        if ($updadmin == true) {
            header("location: " . $_SERVER['REQUEST_URI']);
        }
    } elseif ($_SESSION['role'] == "Zone Leader") {
        $updzone = mysqli_query($con, "UPDATE tblzone set username = '$username', password = '$password' where id = '" . $_SESSION['userid'] . "' ");
        if ($updzone == true) {
            header("location: " . $_SERVER['REQUEST_URI']);
        }
    } elseif ($_SESSION['role'] == "Staff") {
        $updstaff = mysqli_query($con, "UPDATE tblstaff set username = '$username', password = '$password' where id = '" . $_SESSION['userid'] . "' ");
        if ($updstaff == true) {
            header("location: " . $_SERVER['REQUEST_URI']);
        }
    }
}
?>

<!-- Add JavaScript for notifications -->
<script>
function loadNotifications() {
    fetch('/path/to/notifications/api') // Replace with your actual API endpoint
        .then(response => response.json())
        .then(data => {
            const count = data.notifications.length;
            document.getElementById('notification-count').textContent = count;
            
            const list = document.getElementById('notification-list');
            list.innerHTML = ''; // Clear existing notifications
            
            data.notifications.forEach(notification => {
                const li = document.createElement('li');
                li.innerHTML = `<a href="#"><i class="fa fa-info-circle text-blue"></i> ${notification.message}</a>`;
                list.appendChild(li);
            });
        });
}

document.addEventListener('DOMContentLoaded', loadNotifications);
</script>

<?php
// End output buffering and flush the output
ob_end_flush();
?>
</body>
</html>
