<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_approve'])) {
    $pid = htmlspecialchars($_POST['hidden_id'], ENT_QUOTES, 'UTF-8');
    $cnum = htmlspecialchars($_POST['txt_cnum'], ENT_QUOTES, 'UTF-8');
    $findings = htmlspecialchars($_POST['txt_findings'], ENT_QUOTES, 'UTF-8');
    $ornum = htmlspecialchars($_POST['txt_ornum'], ENT_QUOTES, 'UTF-8');
    $amount = htmlspecialchars($_POST['txt_amount'], ENT_QUOTES, 'UTF-8');

    // Database connection
    $conn = new mysqli('hostname', 'username', 'password', 'database');

    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE clearances SET clearanceNo=?, findings=?, orNo=?, samount=? WHERE pid=?");
    $stmt->bind_param('sssss', $cnum, $findings, $ornum, $amount, $pid);

    if ($stmt->execute()) {
        // Fetch resident email from the database
        $stmt = $conn->prepare("SELECT email FROM residents WHERE pid=?");
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();

        // Prepare email notification
        $to = $email;
        $subject = "Clearance Approved";
        $message = "Dear Resident,\n\nYour clearance with Clearance #$cnum has been approved.\n\nFindings: $findings\nOR Number: $ornum\nAmount: $amount\n\nThank you.";
        $headers = "From: no-reply@yourdomain.com";

        // Send email
        mail($to, $subject, $message, $headers);

        echo json_encode(['status' => 'success', 'message' => 'Clearance approved and notification sent.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating record.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
