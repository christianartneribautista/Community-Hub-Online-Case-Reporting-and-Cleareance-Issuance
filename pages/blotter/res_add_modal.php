<!-- ========================= MODAL ======================= -->
<div id="resaddModal" class="modal fade">
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="function.php">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Blotter</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Complainant -->
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Complainant:</label>
                                </div>  
                                <div class="col-sm-4">
                                    <select name="txt_cname" class="form-control input-sm select2" style="width:100%" required>
                                        <option disabled selected>-- Select Complainant --</option>
                                       <?php
                                            $qc = mysqli_query($con, "SELECT * from tblresident");
                                            while ($rowc = mysqli_fetch_array($qc)) {
                                                echo '
                                                <option>' . $rowc['lname'] . ', ' . $rowc['fname'] . ' ' . $rowc['mname'] . '</option>
                                                ';
                                            }
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <!-- Contact Number -->
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label class="control-label">Contact #:</label>
                                </div>  
                                <div class="col-sm-4">
                                    <input name="txt_ccontact" class="form-control input-sm" type="tel" placeholder="Contact #" required />
                                </div> 
                            </div> 

                            <!-- Complainee -->
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label class="control-label">Respondent :</label>
                                </div>  
                                <div class="col-sm-4">
                                    <select name="txt_pname" class="form-control input-sm select2" style="width:100%" required>
                                        <option disabled selected>-- Select Respondent --</option>
                                       <?php
                                            $qc = mysqli_query($con, "SELECT * from tblresident");
                                            while ($rowc = mysqli_fetch_array($qc)) {
                                                echo '
                                                <option>' . $rowc['lname'] . ', ' . $rowc['fname'] . ' ' . $rowc['mname'] . '</option>
                                                ';
                                            }
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <!-- Complaint -->
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label class="control-label">Complaint:</label>
                                </div>
                                <div class="col-sm-4">
                                    <textarea id="txt_complaint" name="txt_complaint" class="form-control input-sm" placeholder="Enter Complaint" rows="4" required></textarea>
                                    
                                    <textarea id="txt_complaint_details" name="txt_complaint_details" class="form-control input-sm mt-2" placeholder="Enter additional details or description" rows="4" style="display:none;"></textarea>
                                </div>  
                            </div>

                            <!-- Upload Photo -->
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label class="control-label">Upload Photo:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="file" name="complaint_photo" class="form-control input-sm" accept="image/*" />
                                    <small class="form-text text-muted">Optional: Upload a photo related to the complaint.</small>
                                </div>
                            </div>


                            <!-- Incident Location -->
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label class="control-label">Location of Incident:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input name="txt_location" class="form-control input-sm" type="text" placeholder="Location of Incident" required />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel" />
                    <input type="submit" class="btn btn-primary btn-sm" name="btn_add" value="Add Blotter" />
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Expand input on typing (makes the input expand dynamically as the user types)
    function expandInput(input) {
        input.style.height = 'auto'; // Reset height to auto to allow expansion
        input.style.height = input.scrollHeight + 'px'; // Set height to scroll height

 // Switch to textarea if the user presses Enter
        if (input.value.includes('\n')) {
            document.getElementById('txt_complaint_details').style.display = 'block'; // Show textarea
            document.getElementById('txt_complaint_details').value = input.value; // Copy input value to textarea
            input.style.display = 'none'; // Hide the input box
        }
    }
</script>