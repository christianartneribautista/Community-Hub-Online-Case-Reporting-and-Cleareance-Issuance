<!-- Issued Modal -->
<div class="modal fade" id="issuedModal" tabindex="-1" role="dialog" aria-labelledby="issuedModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issuedModalLabel">Issued Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this item as issued?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmIssued">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // When the modal is shown, get the clearance ID from the button
    $('#issuedModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var clearanceId = button.data('clearance-id'); // Extract info from data-* attributes
        
        // Store the clearance ID in a data attribute of the modal
        $(this).data('clearance-id', clearanceId);
    });

    // Confirm button click event
    $('#confirmIssued').on('click', function() {
        // Get the clearance ID from the modal's data attribute
        var clearanceId = $('#issuedModal').data('clearance-id');

        // Perform the AJAX request to update the status
        $.ajax({
            type: "POST",
            url: "update_clearance.php", // Replace with the actual path to your PHP script
            data: { id: clearanceId },
            success: function(response) {
                alert(response); // Show the response from the server
                $('#issuedModal').modal('hide'); // Close the modal
                
                // Reload the page after a successful update
                location.reload();
            },
            error: function(xhr, status, error) {
                alert("An error occurred: " + error);
            }
        });
    });
});
</script>