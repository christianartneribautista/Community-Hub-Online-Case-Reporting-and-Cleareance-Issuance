<?php
// Include database connection
include '../connection.php'; // Adjust path if necessary

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($status === 'Issued') {
        $query = "UPDATE tblclearance SET status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('si', $status, $id);
        
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'invalid status';
    }
    exit; // Ensure no additional output is sent
}
?>

<script>
$(document).ready(function() {
    $('.btn-mark-issued').click(function() {
        var clearanceId = $(this).data('id');
        
        $.ajax({
            url: '', // This will post to the same file
            type: 'POST',
            data: { id: clearanceId, status: 'Issued' },
            success: function(response) {
                if(response.trim() === 'success') {
                    location.reload(); // Reload page to reflect changes
                } else {
                    alert('Failed to update status: ' + response);
                }
            },
            error: function() {
                alert('Error processing request');
            }
        });
    });
});
</script>
</body>
</html>
