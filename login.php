<?php
ob_start();
session_start();

include "pages/connection.php";

$error_message = ""; // Initialize error message variable



if (isset($_POST['btn_login'])) {
    $username = mysqli_real_escape_string($con, $_POST['txt_username']);
    $password = mysqli_real_escape_string($con, $_POST['txt_password']);

    // Check for administrator
    $admin = mysqli_query($con, "SELECT * FROM tbluser WHERE username = '$username' AND password = '$password' AND type = 'administrator'");
    $numrow_admin = mysqli_num_rows($admin);

    // Check for resident with approved status
    $user = mysqli_query($con, "SELECT * FROM tblresident WHERE username = '$username' AND password = '$password' AND ustatus = 'approved'");
    $numrow_user = mysqli_num_rows($user);

    if ($numrow_admin > 0) {
        while ($row = mysqli_fetch_array($admin)) {
            $_SESSION['role'] = "Administrator";
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['type'] = $row['type'];
        }
        header('Location: pages/dashboard/dashboard.php');
        exit();

    } elseif ($numrow_user > 0) {
        while ($row = mysqli_fetch_array($user)) {
            $_SESSION['role'] = $row['fname'];
            $_SESSION['resident'] = 1;
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['type'] = $row['type'];
            // type - for security purpose (role)

        }
        header('Location: pages/clearance/clearance.php');
        exit();
    } else {
        $error_message = "Invalid Account or Account Not Approved"; // Set the error message
    }
}


// Redirect to dashboard//still need some adjustment
if (isset($_SESSION['userid'])) {
    // Check if HTTP_REFERER is set and not empty
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        // If no referer, you can fallback to a default page (e.g., the homepage)
        header("Location: index.php");
    }
    exit();
}



ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Information System</title>
    <link rel="icon" href="img/logo.jfif" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #6c5ce7, #2980b9); /* Elegant gradient */
            background-attachment: fixed;
            background-size: cover;
            color: #333;
            height: 100vh;
        }

        /* Subtle texture overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://www.toptal.com/designers/subtlepatterns/patterns/diagonal_stripes.png');
            opacity: 0.1;
            z-index: -1;
        }

        /* Navbar styling */
        .navbar {
            background-color: rgba(44, 62, 80, 0.9); /* Elegant semi-transparent dark background */
            border-radius: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar .navbar-brand {
            padding: 0;
        }
        .navbar .navbar-brand img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .navbar-nav {
            display: flex;
            align-items: center;
        }
        .navbar-nav li {
            padding-left: 15px;
        }
        .navbar-nav li a {
            color: #fff !important;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .navbar-nav li a:hover,
        .navbar-nav li.active a {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        /* Login Panel Styling */
        .content {
            display: flex;
            justify-content: center;
 align-items: center;
            height: calc(100vh - 56px); /* Adjust for navbar */
        }

        .login-panel {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .login-panel h3 {
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            width: 100%;
            font-size: 16px;
            color: #333;
        }

        .form-group input {
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            width: 100%;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            border: 1px solid #ccc;
        }

        .btn-primary {
            background-color: #2980b9;
            border: none;
            color: white;
            font-size: 16px;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1d6cb7;
        }

        .alert-danger {
            margin-top: 20px;
            color: #fff;
            background-color: #e74c3c;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Log In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registration/register.php">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Login Content -->
<div class="content">
    <div class="login-panel">
        <h3>Login</h3>
        <form method="post">
            <div class="form-group">
                <label for="txt_username">Username</label>
                <input type="text" class="form-control" name="txt_username" placeholder="Enter Username" required>
            </div>
            <div class="form-group">
                <label for="txt_password">Password</label>
                <input type="password" class="form-control" name="txt_password" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="btn_login">Log in</button>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" style="margin-top: 15px;">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>