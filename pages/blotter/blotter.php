<!DOCTYPE html>
<html>

<?php
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    echo "<div class='alert alert-{$_SESSION['status']}'>{$_SESSION['message']}</div>";
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}
?>

<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../login.php"); 
} else {
    ob_start();
    include('../head_css.php'); 
?>
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <?php
        include "../connection.php";
        ?>
        <?php include('../header.php'); ?>

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <?php include('../sidebar-left.php'); ?>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Blotter
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <!-- left column -->
                        <div class="box">
                            <div class="box-header">
                                <div style="padding:10px;">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Blotter</button>  
                                    <?php 
                                    if (!isset($_SESSION['staff'])) {
                                    ?>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button> 
                                    <?php
                                    }
                                    ?>
                                </div>                                 
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <form method="post">
                                    <table id="table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <?php
                                                if (!isset($_SESSION['staff'])) {
                                                ?>
                                                    <th style="width: 20px !important;">
                                                        <input type="checkbox" name="chk_delete[]" class="cbxMain" onchange="checkMain(this)" />
                                                    </th>
                                                <?php
                                                }
                                                ?>
                                                <th>Time Recorded</th>
                                                <th>Date Recorded</th>
                                                <th>Complainant</th>
                                                <th>Respondent</th>
                                                <th>Complaint</th>
                                                <th>Status</th>
                                                <th>Location of Incidence</th>
                                                <th>Photo</th> <!-- New column for photo -->
                                                <th style="width: 40px !important;">Option</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php
                                            if (!isset($_SESSION['staff'])) {
                                                $squery = mysqli_query($con, "SELECT b.id as bid, b.timeRecorded, b.dateRecorded, b.complainant, b.personToComplain, b.complaint, b.sStatus, b.locationOfIncidence, b.photo, r.lname, r.fname, r.mname FROM tblblotter b LEFT JOIN tblresident r ON b.personToComplain = r.id  ORDER BY b.id DESC") or die('Error: ' . mysqli_error($con));
                                                while ($row = mysqli_fetch_array($squery)) {
                                                    echo '
                                                    <tr>
                                                        <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="' . $row['bid'] . '" /></td>
                                                        <td>' . date('h:i A', strtotime($row['timeRecorded'])) . '</td> <!-- Updated to 12-hour format -->
                                                        <td>' . $row['dateRecorded'] . '</td>
                                                        <td>' . $row['complainant'] . '</td>
                                                        <td>' . $row['personToComplain'] . '</td>
                                                        <td>' . $row['complaint'] . '</td>
                                                        <td>' . $row['sStatus'] . '</td>
                                                        <td>' . $row['locationOfIncidence'] . '</td>
                                                        <td>';
                                                    // Display the image if it exists
                                                    if (!empty($row['photo'])) {
                                                        echo '<img src="uploads/' . $row['photo'] . '" alt="Complaint Photo" class="photobox" style="width: 100px; height: auto; cursor:pointer;" />';
                                                    } else {
                                                        echo 'No Image';
                                                    }
                                                    echo '</td>
                                                        <td><button class="btn btn-primary btn-sm" data-target="#editModal' . $row['bid'] . '" data-toggle="modal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></td>
                                                    </tr>
                                                    ';
                                                    include "edit_modal.php"; // Include the edit modal for each row
                                                }
                                            }
                                            ?>  
                                        </tbody>
                                    </table>
                                    <?php include "../deleteModal.php"; ?>
                                </form>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->

                        <?php include "../edit_notif.php"; ?>
                        <?php include "../added_notif.php"; ?>
                        <?php include "../delete_notif.php"; ?>

                        <?php include "add_modal.php"; ?>
                        <?php include "function.php"; ?>
                    </div><!-- /.row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <!-- jQuery 2.0.2 -->
        <?php }
        include "../footer.php"; ?>
        <script type="text/javascript">
            <?php
            if (!isset($_SESSION['staff'])) {
            ?>
                $(function() {
                    $("#table").dataTable({
                       "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0, 8 ] } ],"aaSorting": []
                    });
                    $(".select2").select2();
                });
            <?php
            } else {
            ?>
                $(function() {
                    $("#table").dataTable({
                       "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 7 ] } ],"aaSorting": []
                    });
                    $(".select2").select2();
                });
            <?php
            }
            ?>
        </script>
    </body>
</html>
