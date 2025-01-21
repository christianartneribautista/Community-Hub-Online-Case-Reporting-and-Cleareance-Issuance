<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Hub</title>
    <link rel="icon" href="../img/logo.jfif" type="image/x-icon">
    <!-- Google Fonts for elegance -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
    /* General body styling */
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to bottom right, #6c5ce7, #2980b9); /* Elegant gradient */
        background-attachment: fixed; /* Keeps the background fixed while scrolling */
        background-size: cover; /* Ensures the background covers the whole screen */
        color: #333; /* Default text color */
        overflow-x: hidden; /* Prevent horizontal scrolling */
        height: 100vh; /* Ensures the body takes full height */
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
        opacity: 0.1; /* Very subtle texture */
        z-index: -1; /* Placing it behind the content */
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
        height: 40px; /* Decreased size of logo */
        width: 40px; /* Decreased size of logo */
        border-radius: 50%; /* Circular logo */
        object-fit: cover;
    }
    .navbar-nav {
        display: flex;
        align-items: center;
    }
    .navbar-nav li {
        padding-left: 20px;
    }
    .navbar-nav li a {
        color: #fff !important;
        font-weight: 600;
        font-size: 16px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .navbar-nav li a:hover,
    .navbar-nav li.active a {
        background-color: #2980b9; /* Elegant blue */
        transform: scale(1.05); /* Slight scale effect */
    }

    /* Title container styling */
    .title-container {
        display: flex;
        flex-direction: column; /* Stack logo and title vertically */
        align-items: center;
        justify-content: center;
        height: calc(100vh - 56px); /* Adjust height for navbar */
        padding: 40px 20px;
        text-align: center;
        color: #fff; /* Title color to stand out against the background */
    }

    /* Styling for the logo and title */
    .logo {
        margin-bottom: 20px; /* Space between the logo and the title */
    }

    .title {
        font-size: 1.8rem; /* Decreased title font size */
        font-weight: 600;
        max-width: 700px;
        line-height: 1.6;
        margin-top: 20px; /* Moves title lower */
        text-align: center;
        transition: color 0.3s ease;
    }

    /* Media Queries for responsiveness */
    @media (max-width: 768px) {
        .title {
            font-size: 1.6rem; /* Even smaller font for mobile */
        }
        .logo img {
            height: 30px; /* Even smaller logo size for mobile */
            width: 30px; /* Even smaller logo size for mobile */
        }
    }

    /* Custom footer styling */
    footer {
        text-align: center;
        padding: 8px 15px; /* Reduced padding for a smaller footer */
        background-color: #34495e;
        color: #fff;
        position: relative;
        bottom: 0;
        width: 100%;
    }

    /* Adding smooth scroll behavior */
    html {
        scroll-behavior: smooth;
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
                    <a class="nav-link" href="../login.php">Log In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../registration/register.php">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Title Container -->
<div class="title-container">
    <div class="logo">
        <img style="border-radius: 190px;" src="../img/logo.jfif" alt="Community Hub">
    </div>
    <div class="title">
        Community Hub: Online Case Reporting and Clearance Issuance of Barangay Taboc San Juan La Union
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Community Hub. All rights reserved.</p>
</footer>

<!-- Bootstrap and jQuery JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
