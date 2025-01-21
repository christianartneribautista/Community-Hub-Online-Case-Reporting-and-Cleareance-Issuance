<?php
session_start();
ob_start(); // Start output buffering

if (!isset($_SESSION['role'])) {
    header("Location: ../../login.php");
    exit();
}

include('../head_css.php');
include("../connection.php");
include('../header.php');
include('function.php');
include('approve_modal.php');

$user_id = $_SESSION['userid'];
$user_id = mysqli_real_escape_string($con, $user_id);

// Check if a mark-as-read request has been made
if (isset($_POST['mark_as_read'])) {
    $notification_id = mysqli_real_escape_string($con, $_POST['notification_id']);
    $update_sql = "UPDATE notifications SET status = 'read' WHERE id = '$notification_id' AND user_id = '$user_id'";
    mysqli_query($con, $update_sql) or die('Error updating notification status: ' . mysqli_error($con));
    // Redirect to avoid resubmission of the form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Separate queries for unread and read notifications
$unread_sql = "SELECT * FROM notifications WHERE user_id = '$user_id' AND status = 'unread' ORDER BY timestamp DESC";
$unread_result = mysqli_query($con, $unread_sql) or die('Error fetching unread notifications: ' . mysqli_error($con));

$read_sql = "SELECT * FROM notifications WHERE user_id = '$user_id' AND status = 'read' ORDER BY timestamp DESC";
$read_result = mysqli_query($con, $read_sql) or die('Error fetching read notifications: ' . mysqli_error($con));

ob_end_flush(); // Flush the output buffer and send it to the browser
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    
  
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px; /* Smaller font size for body text */
        }
        .notification-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .notification-table th, .notification-table td {
            border: 1px solid #ddd;
            padding: 5px; /* Reduced padding for smaller font size */
            text-align: left;
            font-size: 12px; /* Smaller font size for table cells */
        }
        .notification-table th {
            background-color: #f2f2f2;
            font-size: 12px; /* Smaller font size for table headers */
        }
        .notification {
            border-radius: 5px;
            padding: 8px; /* Reduced padding for smaller font size */
            margin: 8px 0; /* Reduced margin */
            background-color: #f9f9f9;
            font-size: 12px; /* Smaller font size for notification text */
        }
        .notification.read {
            background-color: #e9ecef;
        }
        .notification p {
            margin: 0;
            font-size: 12px; /* Smaller font size for paragraph text */
        }
        .notification .date {
            font-size: 10px; /* Smaller font size for date */
            color: #888;
        }
        .mark-read {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 4px 8px; /* Reduced padding for smaller font size */
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px; /* Smaller font size for button text */
        }
        .mark-read:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body class="skin-black">
    <?php include('../sidebar-left.php'); ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <aside class="right-side">
            <section class="content">
                <h3>Unread Notifications</h3>
                <?php if (mysqli_num_rows($unread_result) > 0): ?>
                    <table class="notification-table">
                        <thead>
                            <tr>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($notification = mysqli_fetch_assoc($unread_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($notification['message']); ?></td>
                                    <td><?php echo date('F j, Y, g:i a', strtotime($notification['timestamp'])); ?></td>
                                    <td>
                                        <form method="post" action="">
                                            <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                                            <button type="submit" name="mark_as_read" class="mark-read">Mark as Read</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No unread notifications found.</p>
                <?php endif; ?>

                <h3>Read Notifications</h3>
                <?php if (mysqli_num_rows($read_result) > 0): ?>
                    <table class="notification-table">
                        <thead>
                            <tr>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($notification = mysqli_fetch_assoc($read_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($notification['message']); ?></td>
                                    <td><?php echo date('F j, Y, g:i a', strtotime($notification['timestamp'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No read notifications found.</p>
                <?php endif; ?>
            </section>
        </aside>
    </div>

     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
