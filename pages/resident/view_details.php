<?php
include "../connection.php"; // Ensure this file is included correctly

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Example query to fetch residents
$query = "SELECT * FROM tblresident"; // Adjust this query as needed
$result = mysqli_query($con, $query);

if (!$result) {
    echo 'Query failed: ' . mysqli_error($con);
    exit; // Stop execution if the query fails
}

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        if (isset($row['id'])) {
            // Debugging: Ensure Resident ID is being printed correctly
            echo '<div id="viewDetailsModal' . $row['id'] . '" class="modal fade">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">View Resident Information</h4>
                    </div>
                    <div class="modal-body">';

                    // Debugging: Check if resident ID is printed
                    echo 'Resident ID: ' . htmlspecialchars($row['id']) . '<br>';

                    // Fetch resident details
                    $queryDetails = "SELECT * FROM tblresident WHERE id = '" . mysqli_real_escape_string($con, $row['id']) . "'";
                    $view_query = mysqli_query($con, $queryDetails);
                    
                    // Check if the query was successful and if any rows were returned
                    if ($view_query && mysqli_num_rows($view_query) > 0) {
                        $vrow = mysqli_fetch_array($view_query);

                        // Display resident details...
                        echo '
                            <div class="row">
                                <div class="container-fluid">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Name:</label><br>
                                            <span>' . htmlspecialchars($vrow['lname'] . ', ' . $vrow['fname'] . ' ' . $vrow['mname']) . '</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" style="margin-top:10px;">Birthdate:</label>
                                            <span>' . htmlspecialchars($vrow['bdate']) . '</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Email Address:</label>
                                            <span>' . htmlspecialchars($vrow['emailAdd']) . '</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Length of Stay: (in Months)</label>
                                            <span>' . htmlspecialchars($vrow['lengthofstay']) . '</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Username:</label>
                                            <span>' . htmlspecialchars($vrow['username']) . '</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Gender:</label>
                                            <span>' . htmlspecialchars($vrow['gender']) . '</span>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Marital Status:</label>
                                            <span>' . htmlspecialchars($vrow['maritalstatus']) . '</span>
                                        </div> 

                                        <div class="form-group">
                                            <label class="control-label">Zone #:</label>
                                            <span>' . htmlspecialchars($vrow['zone']) . '</span>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Religion:</label>
                                            <span>' . htmlspecialchars($vrow['religion']) . '</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Former Address:</label>
                                            <span>' . htmlspecialchars($vrow['formerAddress']) . '</span>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Image:</label><br>
                                            <img src="' . htmlspecialchars($vrow['image_path']) . '" alt="Resident Image" class="img-responsive" style="max-width: 100%; height: auto;"/>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    } else {
                        echo '<div class="alert alert-danger">No resident found with the provided ID.</div>';
                    }

                    echo '</div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Close"/>
                        
                    </div>
                </div>
              </div>
            </form>';
        } else {
            echo '<div class="alert alert-danger">Row is not set or does not contain an ID.</div>';
        }
    }
} else {
    echo 'Query failed: ' . mysqli_error($con);
}
?>
