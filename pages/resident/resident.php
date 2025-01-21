<!DOCTYPE html>
<html>

<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../login.php");
    exit();
} else {
    ob_start();
    include('../head_css.php'); 
}
?>

<style>
    /* Tab Navigation Styles */
    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
    }
    .nav-tabs .nav-item .nav-link {
        border: 1px solid transparent;
        border-radius: 0;
        margin-bottom: -1px;
    }
    .nav-tabs .nav-item .nav-link.active {
        background-color: transparent;
        color: #007bff;
        font-weight: bold;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    .nav-tabs .nav-item .nav-link:hover {
        border-color: #dee2e6;
    }
</style>

<!-- Include jQuery and DataTables CSS/JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<body class="skin-black">
    <?php include "../connection.php"; ?>
    <?php include('../header.php'); ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php include('../sidebar-left.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Resident</h1>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#pending" class="nav-link active">Pending</a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#approved" class="nav-link">Approved</a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content table-responsive">
                                <div id="pending" class="tab-pane active">
                                   <table id="pendingTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th> <!-- Include ID column -->
                                                <th>Time Recorded</th>
                                                <th>Date Recorded</th>
                                                <th>Zone</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Former Address</th>
                                                <th>Option</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $squery = mysqli_query($con, "SELECT id, timeRecorded, dateRecorded, zone, CONCAT(lname, ', ', fname, ' ', mname) as cname, age, gender, formerAddress, image FROM tblresident WHERE ustatus = 'pending' ORDER BY id DESC");
                                            while ($row = mysqli_fetch_array($squery)) {
                                                echo '
                                                <tr>
                                                    <td>' . htmlspecialchars($row['id']) . '</td> <!-- ID column -->
                                                    <td>' . htmlspecialchars($row['timeRecorded']) . '</td>
                                                    <td>' . htmlspecialchars((new DateTime($row['dateRecorded']))->format('Y-m-d') ?? 'N/A') . '</td>
                                                    <td>' . htmlspecialchars($row['zone']) . '</td>
                                                    <td><img src="image/' . htmlspecialchars(basename($row['image'])) . '" style="width:60px;height:60px;" /></td>
                                                    <td>' . htmlspecialchars($row['cname']) . '</td>
                                                    <td>' . htmlspecialchars($row['age']) . '</td>
                                                    <td>' . htmlspecialchars($row['gender']) . '</td>
                                                    <td>' . htmlspecialchars($row['formerAddress']) . '</td>
                                                    <td>
                                                        <form action="fetch_resident_details.php" method="POST" style="display:inline;">
                                                            <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="fa fa-eye"></i> View Details
                                                            </button>
                                                        </form>
                                                        <form action="approve_resident.php" method="POST" style="display:inline;">
                                                            <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fa fa-check"></i> Approve
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="approved" class="tab-pane">
                                    <table id="approvedTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Zone</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Former Address</th>
                                                <th>Option</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $squery = mysqli_query($con, "SELECT zone, id, CONCAT(lname, ', ', fname, ' ', mname) as cname, age, gender, formerAddress, image FROM tblresident WHERE ustatus = 'approved' ORDER BY zone");
                                            if (!$squery) {
                                                die("Query failed: " . mysqli_error($con));
                                            }
                                            while ($row = mysqli_fetch_array($squery)) {
                                                echo '
                                                <tr>
                                                    <td>' . htmlspecialchars($row['zone']) . '</td>
                                                    <td><img src="image/' . htmlspecialchars(basename($row['image'])) . '" style="width:60px;height:60px;" /></td>
                                                    <td>' . htmlspecialchars($row['cname']) . '</td>
                                                    <td>' . htmlspecialchars($row['age']) . '</td>
                                                    <td>' . htmlspecialchars($row['gender']) . '</td>
                                                    <td>' . htmlspecialchars($row['formerAddress']) . '</td>
                                                    <td>
                                                        <form action="fetch_resident_details.php" method="POST" style="display:inline;">
                                                            <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="fa fa-eye"></i> View Details
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Include modals -->
                    <?php include "../deleteModal.php"; ?>
                    <?php include "../duplicate_error.php"; ?>
                    <?php include "function.php"; ?>
                    <?php include "../approve_notif.php"; ?>
                </div>
            </section>
        </aside>
    </div>
    <?php include "../footer.php"; ?>
<script>
    $(document).ready(function() {
    // Initialize the pendingTable
    $('#pendingTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "lengthChange": true,
        "pageLength": 10,
        "columnDefs": [
            { "orderable": false, "targets": 8 } // Disable sorting on the last column (Options)
        ],
        "order": [[0, 'desc']] // Change 0 to the index of the id column if it's not the first column
    });

    // Initialize the approvedTable
    $('#approvedTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "lengthChange": true,
        "pageLength": 10,
        "columnDefs": [
            { "orderable": false, "targets": 6 } // Disable sorting on the last column (Options)
        ],
        "order": [[0, 'asc']] // Change 0 to the index of the id column if it's not the first column
    });
});
</script>
</body>
</html>