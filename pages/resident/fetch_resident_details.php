<?php
include "../connection.php"; // Include your database connection

if (isset($_POST['id'])) {
    $residentId = mysqli_real_escape_string($con, $_POST['id']);
    $query = "SELECT * FROM tblresident WHERE id = '$residentId'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $resident = mysqli_fetch_assoc($result);
        // Output the resident details with styles
        echo '
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }
            .resident-details {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
                max-width: 600px;
                margin: auto;
            }
            .resident-details h4 {
                color: #333;
                margin-bottom: 10px;
            }
            .resident-details p {
                color: #555;
                line-height: 1.6;
            }
            .resident-details img {
                border-radius: 8px;
                margin-top: 15px;
                max-width: 100%;
                height: auto;
            }
            .alert {
                background-color: #f8d7da;
                color: #721c24;
                padding: 10px;
                border-radius: 5px;
                margin: 20px 0;
            }
            .back-button {
                display: inline-block;
                margin-bottom: 20px;
                padding: 10px 15px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s;
            }
            .back-button:hover {
                background-color: #0056b3;
            }
        </style>
        <div class="resident-details">
            <a href="javascript:history.back()" class="back-button">Back</a>
            <h4>Name: ' . htmlspecialchars($resident['lname'] . ', ' . $resident['fname'] . ' ' . $resident['mname']) . '</h4>
            <p>Birthdate: ' . htmlspecialchars($resident['bdate']) . '</p>
            <p>Email: ' . htmlspecialchars($resident['emailAdd']) . '</p>
            <p>Length of Stay: ' . htmlspecialchars($resident['lengthofstay']) . ' months</p>
            <p>Username: ' . htmlspecialchars($resident['username']) . '</p>
            <p>Gender: ' . htmlspecialchars($resident['gender']) . '</p>
            <p>Marital Status: ' . htmlspecialchars($resident['maritalstatus']) . '</p>
            <p>Zone: ' . htmlspecialchars($resident['zone']) . '</p>
            <p>Religion: ' . htmlspecialchars($resident['religion']) . '</p>
            <p>Former Address: ' . htmlspecialchars($resident['formerAddress']) . '</p>
            <img src="image/' . htmlspecialchars(basename($resident['image'])) . '" alt="Resident Image"/>
        </div>';
    } else {
        echo '<div class="alert">No resident found with the provided ID.</div>';
    }
} else {
    echo '<div class="alert">Invalid request.</div>';
}
?>