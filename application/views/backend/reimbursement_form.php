<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Submit Reimbursement Request</h3>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-body">
                        <form class="row" id="reimbursementForm" method="post" enctype="multipart/form-data">
                            <!-- CSRF Token -->
                            <input type="hidden" 
                                name="<?php echo $this->security->get_csrf_token_name(); ?>" 
                                value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            
                            <div class="form-group col-md-6 m-t-5">
                                <label>Expense Date</label>
                                <input type="date" name="expense_date" class="form-control" required>
                            </div>
                            
                            <div class="form-group col-md-6 m-t-5">
                                <label>Amount</label>
                                <input type="number" name="amount" class="form-control" placeholder="Enter amount" step="0.01" required>
                            </div>
                            
                            <div class="form-group col-md-6 m-t-5">
                                <label>Category</label>
                                <select name="category" class="form-control custom-select" required>
                                    <option value="">Select Category</option>
                                    <option value="Travel">Travel</option>
                                    <option value="Meals">Meals</option>
                                    <option value="Office Supplies">Office Supplies</option>
                                    <option value="Training">Training</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-6 m-t-5">
                                <label>Receipt (PDF/Image)</label>
                                <input type="file" name="receipt" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            </div>
                            
                            <div class="form-group col-md-12 m-t-5">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Enter expense description" required></textarea>
                            </div>
                            
                            <div class="form-actions col-md-12">
                                <button type="submit" class="btn btn-info">Submit</button>
                                <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('backend/footer'); ?>

    <script>
    $(document).ready(function() {
        $('#reimbursementForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: '<?php echo base_url(); ?>reimbursement/submitRequest',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('.message').html('<div class="alert alert-success">' + response.message + '</div>');
                        $('#reimbursementForm')[0].reset();
                        setTimeout(function() {
                            window.location.href = '<?php echo base_url(); ?>reimbursement/viewMyRequests';
                        }, 2000);
                    } else {
                        $('.message').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    $('.message').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                    console.error(xhr.responseText);
                }
            });
        });
    });
    </script>
</div>