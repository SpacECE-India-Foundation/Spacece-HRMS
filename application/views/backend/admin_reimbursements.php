<!-- application/views/backend/admin_reimbursements.php -->
<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Manage Reimbursements</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <div class="btn-group" role="group" aria-label="Status Filter">
               
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="adminReimbursementTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($requests as $request): ?>
                                    <tr>
                                        <td>
                                            <?php echo $request['employee_name']; ?><br>
                                            <small><?php echo $request['employee_email']; ?></small>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($request['expense_date'])); ?></td>
                                        <td><?php echo $request['category']; ?></td>
                                        <td><?php echo number_format($request['amount'], 2); ?></td>
                                        <td><?php echo $request['description']; ?></td>
                                        <td>
                                            <span class="badge <?php
                                                echo $request['status'] === 'Approved' ? 'badge-success' :
                                                    ($request['status'] === 'Rejected' ? 'badge-danger' : 'badge-warning');
                                            ?>">
                                                <?php echo $request['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('reimbursement/download/'.$request['id']); ?>" 
                                               class="btn btn-sm btn-info" title="Download Receipt">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <?php if($request['status'] === 'Pending'): ?>
                                            <button class="btn btn-sm btn-success" 
                                                    onclick="updateStatus(<?php echo $request['id']; ?>, 'Approved')" 
                                                    title="Approve Request">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="updateStatus(<?php echo $request['id']; ?>, 'Rejected')" 
                                                    title="Reject Request">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm">
                <div class="modal-header">
                    <h5 class="modal-title">Update Request Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="request_id" id="request_id">
                    <input type="hidden" name="status" id="status">
                    <div class="form-group">
                    <label for="status_reason">Reason for Status Change</label>
                            <textarea class="form-control" id="status_reason" name="status_reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('backend/footer'); ?>

<script>
    function updateStatus(requestId, status) {
        // Set the request ID and status in the modal
        $('#request_id').val(requestId);
        $('#status').val(status);
        
        // Show the modal
        $('#statusModal').modal('show');
    }

    // Handle form submission
    $('#statusForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = $(this).serialize(); // Serialize the form data

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('reimbursement/update_status'); ?>', // Update with your actual URL
            data: formData,
            success: function(response) {
                // Handle success response
                if (response.success) {
                    // Optionally, you can refresh the page or update the table
                    location.reload(); // Reload the page to see the updated status
                } else {
                    // Handle error response
                    alert(response.message);
                }
            },
            error: function() {
                // Handle AJAX error
                alert('An error occurred while updating the status. Please try again.');
            }
        });
    });
</script>