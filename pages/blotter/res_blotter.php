<!DOCTYPE html>
<html>

<head>
    <?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: ../../login.php");
        exit();
    }
    include('../head_css.php'); // Include CSS
    ?>
    <!-- Add jQuery and Bootstrap JS (if not already included) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body class="skin-black">
    <!-- header logo: style can be found in header.less -->
    <?php
    include "../connection.php";
    include('../header.php');
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <?php include('../sidebar-left.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Blotter</h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <!-- Button to trigger modal -->
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#resaddModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Blotter
                                </button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <form method="post">
                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date Recorded</th>
                                            <th>Complainant</th>
                                            <th>Respondent</th>
                                            <th>Complaint</th>
                                            <th>Status</th>
                                            <th>Location of Incidence</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_SESSION['userid'])) {
                                            $user_id = mysqli_real_escape_string($con, $_SESSION['userid']);
                                            $squery = mysqli_query($con, "SELECT * FROM tblblotter WHERE res_id = '$user_id' ORDER BY id DESC") or die('Error: ' . mysqli_error($con));
                                            while ($row = mysqli_fetch_array($squery)) {
                                                echo '
                                                <tr>
                                                    <td>' . $row['dateRecorded'] . '</td>
                                                    <td>' . $row['complainant'] . '</td>
                                                    <td>' . $row['personToComplain'] . '</td>
                                                    <td>' . $row['complaint'] . '</td>
                                                    <td>' . $row['sStatus'] . '</td>
                                                    <td>' . $row['locationOfIncidence'] . '</td>
                                                </tr>';
                                            }
                                        } else {
                                            // Modify the query to get the complainant's full name
                                                $squery = mysqli_query($con, "
                                                    SELECT b.*, CONCAT(r.fname, ' ', r.mname, ' ', r.lname) AS complainant
                                                    FROM tblblotter b
                                                    LEFT JOIN tblresident r ON b.complainant = r.id
                                                    WHERE b.res_id = '$user_id'
                                                    ORDER BY b.id DESC") or die('Error: ' . mysqli_error($con));

                                                while ($row = mysqli_fetch_array($squery)) {
                                                    echo '
                                                    <tr>
                                                        <td>' . $row['dateRecorded'] . '</td>
                                                        <td>' . $row['complainant'] . '</td> <!-- Display full name -->
                                                        <td>' . $row['personToComplain'] . '</td>
                                                        <td>' . $row['complaint'] . '</td>
                                                        <td>' . $row['actionTaken'] . '</td>
                                                        <td>' . $row['sStatus'] . '</td>
                                                        <td>' . $row['locationOfIncidence'] . '</td>
                                                    </tr>';
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
                    <?php include "res_add_modal.php"; ?>
                    <?php include "function.php"; ?>

                </div><!-- /.row -->
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->

    <!-- jQuery 2.0.2 -->
    <?php include "../footer.php"; ?>

    <script type="text/javascript">
        $(function() {
            $("#table").dataTable({
                "aoColumnDefs": [
                    { "bSortable": false, "aTargets": [ 0, 8 ] }
                ],
                "aaSorting": []
            });
            $(".select2").select2();
        });
    </script>

    <!-- Modal for Adding Blotter (included from res_add_modal.php) -->
    <div class="modal fade" id="resaddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Blotter</h4>
                </div>
                <div class="modal-body">
                    <!-- Add your form fields here -->
                    <form method="POST" action="your_add_blotter_script.php">
                        <div class="form-group">
                            <label for="complainant">Complainant</label>
                            <input type="text" class="form-control" name="complainant" placeholder="Complainant" required>
                        </div>
                        <div class="form-group">
                            <label for="complaint">Complaint</label>
                            <textarea class="form-control" name="complaint" placeholder="Complaint" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="actionTaken">Action Taken</label>
                            <textarea class="form-control" name="actionTaken" placeholder="Action Taken"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationOfIncidence">Location of Incidence</label>
                            <input type="text" class="form-control" name="locationOfIncidence" placeholder="Location of Incidence">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
