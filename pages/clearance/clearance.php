<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../login.php");
    exit();
}

ob_start();
include('../head_css.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearance Management</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style type="text/css">
        /* Container for QR Code */
        #qrcode {
            width: 100px;
            height: 100px;
            max-width: 100%;
            max-height: 100%;
            margin: 20px auto;
        }

        /* Print media query */
        @media print {
            body * {
                visibility: hidden;
            }
            .printable-area, .printable-area * {
                visibility: visible;
            }
            @page {
                margin: 0;
            }
        }

        .input-size {
            width: 418px;
        }
        .nav-tabs .nav-item .nav-link.active {
            background-color: #007bff; /* Change this color to your preference */
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body class="skin-black">
    <?php
    include "../connection.php";

    // Check database connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    include('../header.php');
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php include('../sidebar-left.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Clearance</h1>
            </section>

            <section class="content">
                <?php if ($_SESSION['role'] == "Administrator"): ?>
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <button id="addClearancebutton" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Clearance
                                </button>
                                <?php if (!isset($_SESSION['staff'])): ?>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button> 
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="nav-link active"><a href="#pending" data-toggle="tab">Pending</a></li>
                                <li><a class="nav-link" href="#approved" data-toggle="tab">Approved</a></li>
                                <li><a class="nav-link" href="#disapproved" data-toggle="tab">Disapproved</a></li>
                                <li><a class="nav-link" href="#issued" data-toggle="tab">Issued</a></li>
                            </ul>

                            <form method="post">
                                <div class="tab-content">
                                    <div id="pending" class="tab-pane active">
                                        <table id="table pendingTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20px !important;">
                                                        <input type="checkbox" name="chk_delete[]" class="cbxMain" onchange="checkMain(this)" />
                                                    </th>
                                                    <th>Time Recorded</th>
                                                    <th>Date Recorded</th>
                                                    <th>Resident Name</th>
                                                    <th>Purpose</th>
                                                    <th>Type of Clearance</th>
                                                    <th style="width: 25% !important;">Option</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Updated SQL query to include purpose and type_of_clearance
                                                $squery = mysqli_query($con, "SELECT p.timeRecorded, p.dateRecorded, p.purpose, p.type_of_clearance, CONCAT(r.lname, ', ', r.fname, ' ', r.mname) as residentname, p.id as pid FROM tblclearance p LEFT JOIN tblresident r ON r.id = p.residentid WHERE p.status = 'New' ORDER BY p.id DESC") or die('Error: ' . mysqli_error($con));
                                                
                                                if (mysqli_num_rows($squery) > 0) {
                                                    while ($row = mysqli_fetch_array($squery)) {
                                                        ?>
                                                        <tr>
                                                            <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="<?= $row['pid'] ?>" /></td>
                                                            <td><?= htmlspecialchars($row['timeRecorded'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['dateRecorded'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['residentname'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['purpose'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['type_of_clearance'] ?? 'N/A') ?></td>
                                                            <td>
                                                                <button class="btn btn-success btn-sm" data-target="#approveModal<?= $row['pid'] ?>" data-toggle="modal">
                                                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
                                                                </button>
                                                                <button class="btn btn-danger btn-sm" data-target="#disapproveModal<?= $row['pid'] ?>" data-toggle="modal">
                                                                    <i class="fa fa-thumbs-down" aria-hidden="true"></i> Disapprove
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        include "approve_modal.php";
                                                        include "disapprove_modal.php";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7' style='text-align: center;'>No records found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="approved" class="tab-pane">
                                        <table id="approvedTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <?php if (!isset($_SESSION['staff'])): ?>
                <th style="width: 20px !important;">
                    <input type="checkbox" name="chk_delete[]" class="cbxMain" onchange="checkMain(this)" />
                </th>
            <?php endif; ?>
            <th>Time Recorded</th>
            <th>Date Recorded</th>
            <th>Resident Name</th>
            <th>Type of Clearance</th>
            <th>Purpose</th>
            <th>OR Number</th>
            <th>Amount</th>
            <th style="width: 15% !important;">Option</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $statusCondition = isset($_SESSION['staff']) ? "" : " and status = 'Approved'";
        $squery = mysqli_query($con, "
            SELECT p.timeRecorded, p.dateRecorded, p.purpose, p.type_of_clearance, p.orNo, p.samount, 
                   CONCAT(r.lname, ', ', r.fname, ' ', r.mname) AS residentname, 
                   p.id AS pid 
            FROM tblclearance p 
            LEFT JOIN tblresident r ON r.id = p.residentid 
            WHERE 1 $statusCondition 
            ORDER BY p.id DESC
        ") or die('Error: ' . mysqli_error($con));

        if (mysqli_num_rows($squery) > 0) {
            while ($row = mysqli_fetch_array($squery)) {
                ?>
                <tr>
                    <?php if (!isset($_SESSION['staff'])): ?>
                        <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="<?= $row['pid'] ?>" /></td>
                    <?php endif; ?>
                    <td><?= htmlspecialchars($row['timeRecorded'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['dateRecorded'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['residentname'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['type_of_clearance'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['purpose'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['orNo'] ?? 'N/A') ?></td>
                    <td>₱ <?= number_format($row['samount'], 2) ?></td>
                    <td> 
                        <button class="btn btn-primary printButton" 
                            data-or-number="<?= htmlspecialchars($row['orNo']) ?>" 
                            data-requestee-name="<?= htmlspecialchars($row['residentname']) ?>" 
                            data-cert-fee="<?= htmlspecialchars($row['samount']) ?>" 
                            data-issued-day="<?= date('d') ?>" 
                            data-issued-month="<?= htmlspecialchars(date('F')) ?>" 
                            data-issued-year="<?= date('Y') ?>" 
                            data-document-date="<?= date('Y-m-d') ?>" 
                            data-clearance-type="<?= htmlspecialchars($row['type_of_clearance']) ?>">
                            Print
                        </button>

                        <!-- Button to trigger the modal -->
                        <button class="btn btn-success" name="btn-issued" data-toggle="modal" data-target="#issuedModal" data-clearance-id="<?= $row['pid'] ?>">
                            <i class="issued-button fa fa-check" aria-hidden="true"></i> Issued
                        </button>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='8' style='text-align: center;'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>


                                    </div>

                                    <div id="disapproved" class="tab-pane">
                                        <table id="disapprovedTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <?php if (!isset($_SESSION['staff'])): ?>
                                                        <th style="width: 20px !important;">
                                                            <input type="checkbox" name="chk_delete[]" class="cbxMain" onchange="checkMain(this)" />
                                                        </th>
                                                    <?php endif; ?>
                                                    <th>Time Recorded</th>
                                                    <th>Date Recorded</th>
                                                    <th>Resident Name</th>
                                                    <th>Type of Clearance</th>
                                                    <th>Purpose</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $statusCondition = isset($_SESSION['staff']) ? "" : " and status = 'Disapproved'";
                                                $squery = mysqli_query($con, "
                                                    SELECT p.timeRecorded, p.dateRecorded, p.purpose, p.type_of_clearance , 
                                                           CONCAT(r.lname, ', ', r.fname, ' ', r.mname) AS residentname, 
                                                           p.id AS pid 
                                                    FROM tblclearance p 
                                                    LEFT JOIN tblresident r ON r.id = p.residentid 
                                                    WHERE 1 $statusCondition 
                                                    ORDER BY p.id DESC
                                                ") or die('Error: ' . mysqli_error($con));

                                                if (mysqli_num_rows($squery) > 0) {
                                                    while ($row = mysqli_fetch_array($squery)) {
                                                        ?>
                                                        <tr>
                                                            <?php if (!isset($_SESSION['staff'])): ?>
                                                                <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="<?= $row['pid'] ?>" /></td>
                                                            <?php endif; ?>
                                                            <td><?= htmlspecialchars($row['timeRecorded'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['dateRecorded'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['residentname'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['type_of_clearance'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['purpose'] ?? 'N/A') ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else {
                                                    echo "<tr><td colspan='6' style='text-align: center;'>No records found</td></tr>";
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="issued" class="tab-pane">
                                        <table id="issuedTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <?php if (!isset($_SESSION['staff'])): ?>
                                                        <th style="width: 20px !important;">
                                                            <input type="checkbox" name="chk_delete[]" class="cbxMain" onchange="checkMain(this)" />
                                                        </th>
                                                    <?php endif; ?>
                                                    <th>Time Recorded</th>
                                                    <th>Date Recorded</th>
                                                    <th>Resident Name</th>
                                                    <th>Type of Clearance</th>
                                                    <th>Purpose</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $statusCondition = isset($_SESSION['staff']) ? "" : " and status = 'Issued'";
                                                $squery = mysqli_query($con, "
                                                    SELECT p.timeRecorded, p.dateRecorded, p.purpose, p.type_of_clearance, 
                                                           CONCAT(r.lname, ', ', r.fname, ' ', r.mname) AS residentname, 
                                                           p.id AS pid 
                                                    FROM tblclearance p 
                                                    LEFT JOIN tblresident r ON r.id = p.residentid 
                                                    WHERE 1 $statusCondition 
                                                    ORDER BY p.id DESC
                                                ") or die('Error: ' . mysqli_error($con));

                                                if (mysqli_num_rows($squery) > 0) {
                                                    while ($row = mysqli_fetch_array($squery)) {
                                                        ?>
                                                        <tr>
                                                            <?php if (!isset($_SESSION['staff'])): ?>
                                                                <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="<?= $row['pid'] ?>" /></td>
                                                            <?php endif; ?>
                                                            <td><?= htmlspecialchars($row['timeRecorded'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['dateRecorded'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['residentname'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['type_of_clearance'] ?? 'N/A') ?></td>
                                                            <td><?= htmlspecialchars($row['purpose'] ?? 'N/A') ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else {
                                                    echo "<tr><td colspan='6' style='text-align: center;'>No records found</td></tr>";
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php include "../deleteModal.php"; ?>
                            </form>
                        </div>
                        <?php include "../deleteModal.php"; ?>
                    <?php include "../edit_notif.php"; ?>
                    <?php include "../added_notif.php"; ?>
                    <?php include "../delete_notif.php"; ?>
                    <?php include "../duplicate_error.php"; ?>
                    <?php include "add_modal.php"; ?>
                    <?php include "issued_modal.php"; ?>
                    <?php include "function.php"; ?>
                    </div>
                <?php else: ?>
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#reqModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Request Clearance
                                </button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active"><a data-target="#new" data-toggle="tab">New</a></li>
                                <li><a data-target="#approved" data-toggle="tab">Approved</a></li>
                                <li><a data-target="#disapproved" data-toggle="tab">Disapproved</a></li>
                            </ul>
                            <form method="post">
                                <div class="tab-content">
                                    <div id="new" class="tab-pane active in">
                                        <table id="table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Purpose</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $squery = mysqli_query($con, "SELECT * FROM tblclearance p LEFT JOIN tblresident r ON r.id = p.residentid WHERE r.id = " . $_SESSION['userid'] . " AND status = 'New' ORDER BY orNo DESC") or die('Error: ' . mysqli_error($con));
                                                if (mysqli_num_rows($squery) > 0) {
                                                    while ($row = mysqli_fetch_array($squery)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['purpose']) ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="1" style="text-align: center;">No record found</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="approved" class="tab-pane">
                                        <table id="table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Clearance #</th>
                                                    <th>Type of Clearance</th>
                                                    <th>Purpose</th>
                                                    <th>OR Number</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $squery = mysqli_query($con, "SELECT * FROM tblclearance p LEFT JOIN tblresident r ON r.id = p.residentid WHERE r.id = " . $_SESSION['userid'] . " AND status = 'Approved'") or die('Error: ' . mysqli_error($con));
                                                if (mysqli_num_rows($squery) > 0) {
                                                    while ($row = mysqli_fetch_array($squery)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['clearanceNo']) ?></td>
                                                            <td><?= htmlspecialchars($row['type_of_clearance']) ?></td>
                                                            <td><?= htmlspecialchars($row['purpose']) ?></td>
                                                            <td><?= htmlspecialchars($row['orNo']) ?></td>
                                                            <td>₱ <?= number_format($row['samount'], 2) ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="5" style="text-align: center;">No record found</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="disapproved" class="tab-pane">
                                        <table id="table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Purpose</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $squery = mysqli_query($con, "SELECT * FROM tblclearance p LEFT JOIN tblresident r ON r.id = p.residentid WHERE r.id = " . $_SESSION['userid'] . " AND status = 'Disapproved'") or die('Error: ' . mysqli_error($con));
                                                if (mysqli_num_rows($squery) > 0) {
                                                    while ($row = mysqli_fetch_array($squery)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['purpose']) ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="1" style="text-align: center;">No record found</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                                include "../duplicate_error.php";
                                include "lengthstay_error.php";
                                include "req_modal.php";
                                include "function.php";
                                ?>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        </aside>
    </div>

    <?php include('../footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#pendingTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10,
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Disable sorting on the first column (checkbox)
            ]
        });

        $('#approvedTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10,
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Disable sorting on the first column (checkbox)
            ]
        });

        $('#disapprovedTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10,
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Disable sorting on the first column (checkbox)
            ]
        });

        $('#issuedTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10,
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Disable sorting on the first column (checkbox)
            ]
        });
    });

    $(document).on('click', '.printButton', function() {
        console.log('Button clicked'); // Log when the button is clicked

        var orNumber = $(this).data('or-number');
        var requesteeName = $(this).data('requestee-name');
        var certFee = $(this).data('cert-fee');
        var issuedDay = $(this).data('issued-day');
        var issuedMonth = $(this).data('issued-month');
        var issuedYear = $(this).data('issued-year');
        var documentDate = $(this).data('document-date');
        var clearanceType = $(this).data('clearance-type');
        var fileToOpen = '';

        // Log the data attributes to verify they are set correctly
        console.log('OR Number:', orNumber);
        console.log('Requestee Name:', requesteeName);
        console.log('Cert Fee:', certFee);
        console.log('Issued Day:', issuedDay);
        console.log('Issued Month:', issuedMonth);
        console.log('Issued Year:', issuedYear);
        console.log('Document Date:', documentDate);
        console.log('Clearance Type:', clearanceType);

        // Determine which file to open based on the clearance type
        switch (clearanceType) {
            case 'Barangay Clearance':
                fileToOpen = 'clearances/barangay_clearance.php?orNumber=' + encodeURIComponent(orNumber) + 
                             '&requesteeName=' + encodeURIComponent(requesteeName) + 
                             '&certFee=' + encodeURIComponent(certFee) + 
                             '&issuedDay=' + encodeURIComponent(issuedDay) + 
                             '&issuedMonth=' + encodeURIComponent(issuedMonth) + 
                             '&issuedYear=' + encodeURIComponent(issuedYear) + 
                             '&documentDate=' + encodeURIComponent(documentDate);
                break;

            case 'Certificate of Indigency':
                fileToOpen = 'clearances/certificate_of_indigency.php?orNumber=' + encodeURIComponent(orNumber) + 
                             '&requesteeName=' + encodeURIComponent(requesteeName) + 
                             '&certFee=' + encodeURIComponent(certFee) + 
                             '&issuedDay=' + encodeURIComponent(issuedDay) + 
                             '&issuedMonth=' + encodeURIComponent(issuedMonth) + 
                             '&issuedYear=' + encodeURIComponent(issuedYear) + 
                             '&documentDate=' + encodeURIComponent(documentDate);
                break;

            case 'Certificate of Disclosure':
                fileToOpen = 'clearances/certificate_of_disclosure.php';
                break;

            case 'Certificate of Residency':
                fileToOpen = 'clearances/certificate_of_residency.php';
                break;

            case 'Certificate of First Time Job Seeker':
                fileToOpen = 'clearances/certificate_of_first_time_job_seeker.php';
                break;

            case 'Fencing Clearance':
                fileToOpen = 'clearances/fencing_clearance.php';
                break;

            case 'Building Clearance':
                fileToOpen = 'clearances/building_clearance.php';
                break;

            case 'Tree Cutting Clearance':
                fileToOpen = 'clearances/tree_cutting_clearance.php';
                break;

            case 'Livestock Clearance':
                fileToOpen = 'clearances/livestock_clearance.php';
                break;

            default:
                alert('No print file available for this clearance type.');
                return; // Exit if no file is found
        }

        console.log('Opening URL:', fileToOpen); // Log the URL
        // Open the corresponding file
        window.open(fileToOpen, '_blank');
    });
</script>

</html>