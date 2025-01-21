<?php
echo '<div id="editModal'.$row['bid'].'" class="modal fade">
    <form class="form-horizontal" method="post">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit Blotter Information</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8"> <!-- Changed from col-sm-12 to col-sm-8 to reduce input width -->
                            <div class="form-group">
                                <input type="hidden" value="'.$row['bid'].'" name="hidden_id" id="hidden_id"/>

                                <!-- Complainant Field -->
                                <label class="control-label">Complainant:</label>
                                <select name="txt_edit_cname" class="form-control input-sm select2" style="width:100%">
                                    <option>'.$row['complainant'].'</option>';
                                    $qce = mysqli_query($con,"SELECT * from tblresident");
                                    while($rowce = mysqli_fetch_array($qce)){
                                        echo '<option>'.$rowce['lname'].', '.$rowce['fname'].' '.$rowce['mname'].'</option>';
                                    }
                                echo '   
                                </select>
                            </div>

                            <!-- Contact Field -->
                            <div class="form-group">
                                <label class="control-label">Contact #:</label>
                                <input name="txt_edit_ccontact" class="form-control input-sm" type="number" value="'.$row['ccontact'].'" />
                            </div>

                            <!-- Respondent Field -->
                            <div class="form-group">
                                <label class="control-label">Respondent:</label>
                                <select name="txt_edit_pname" class="form-control input-sm select2" style="width:100%">
                                    <option>'.$row['personToComplain'].'</option>';
                                    $qcp = mysqli_query($con,"SELECT * from tblresident");
                                    while($rowcp = mysqli_fetch_array($qcp)){
                                        echo '<option value="'.$rowcp['id'].'" '.($rowcp['id'] == $row['rid'] ? 'selected' : '').'>'.$rowcp['lname'].', '.$rowcp['fname'].' '.$rowcp['mname'].'</option>';
                                    }
                                    echo '   
                                </select>
                            </div>

                            <!-- Complaint Field -->
                            <div class="form-group">
                                <label class="control-label">Complaint:</label>
                                <input name="txt_edit_complaint" class="form-control input-sm" type="text" value="'.$row['complaint'].'" />
                            </div>

                            <!-- Status Field -->
                            <div class="form-group ```php
                            <label class="control-label">Status:</label>
                            <select name="ddl_edit_stat" id="ddl_edit_stat'.$row['bid'].'" class="form-control input-sm">
                                <option value="'.$row['sStatus'].'" selected>'.$row['sStatus'].'</option>
                                <option>Unsolved</option>
                                <option>Solved</option>
                            </select>
                        </div>

                        <!-- Location of Incident Field -->
                        <div class="form-group">
                            <label class="control-label">Location of Incident:</label>
                            <input name="txt_edit_location" class="form-control input-sm" type="text" value="'.$row['locationOfIncidence'].'" />
                        </div>

                        <!-- Remarks Field -->
                        <div class="form-group" id="remarks_field'.$row['bid'].'" style="display:'.($row['sStatus'] == 'Solved' ? 'block' : 'none').'">
                            <label class="control-label">Remarks:</label>
                            <select name="txt_edit_remarks" class="form-control input-sm">
                                <option value="">Select Remarks</option>
                                <option value="Case Closed/Resolved" '.($row['remarks'] == 'Case Closed/Resolved' ? 'selected' : '').'>Case Closed/Resolved</option>
                                <option value="Endorsed" '.($row['remarks'] == 'Endorsed' ? 'selected' : '').'>Endorsed</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel"/>
                    <input type="submit" class="btn btn-primary btn-sm" name="btn_save" value="Save"/>
                </div>
           
            </div>
        </div>
    </form>
</div>';
?>

<script>
    // JavaScript to handle status change and show remarks input
    function toggleRemarksField(modalId) {
        var status = document.getElementById('ddl_edit_stat' + modalId).value;
        var remarksField = document.getElementById('remarks_field' + modalId);
        
        if (status === 'Solved') {
            remarksField.style.display = 'block'; // Show the remarks input
        } else {
            remarksField.style.display = 'none'; // Hide the remarks input
        }
    }

    // Attach event listener on modal show
    $('#editModal<?php echo $row['bid']; ?>').on('shown.bs.modal', function () {
        var modalId = '<?php echo $row['bid']; ?>';
        toggleRemarksField(modalId); // Initialize visibility
        document.getElementById('ddl_edit_stat' + modalId).addEventListener('change', function() {
            toggleRemarksField(modalId);
        });
    });

    // Initialize remarks field visibility on page load
    (function() {
        toggleRemarksField('<?php echo $row['bid']; ?>');
    })();
</script>