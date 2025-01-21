<!-- ========================= MODAL ======================= -->
<div id="reqModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" style="width:300px !important;" role="document">
    <div class="modal-content">
      <form method="post" action="function.php">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Request Clearance</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="txt_TOC">Type of Clearance:</label>
                <input list="ToCoptions" id="txt_TOC" name="txt_TOC" class="form-control input-sm" type="text" placeholder="Type of Clearance" required>
                <datalist id="ToCoptions">
                  <option value="Barangay Clearance">
                  <option value="Fencing Clearance">
                  <option value="Tree Cutting Clearance">
                  <option value="Building Clearance">
                  <option value="Certificate of Indigency">
                  <option value="Certificate of Residency">
                  <option value="Certificate of Disclosure">
                  <option value="Certificate of First Time Job Seeker">
                  <option value="Livestock Clearance">
                </datalist>
                <label for="txt_purpose">Purpose:</label>
                <input list="Poptions" id="txt_purpose" name="txt_purpose" class="form-control input-sm" type="text" placeholder="Purpose" required>
                <datalist id="Poptions">
                  <option value="Employment">
                  <option value="Business">
                  <option value="School Requirement">
                </datalist>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
          <input type="submit" class="btn btn-primary btn-sm" name="btn_req" value="Request Clearance"/>
        </div>
      </form>
    </div>
  </div>
</div>
