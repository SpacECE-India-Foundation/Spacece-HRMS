<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">Expense Submission Form</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Expense Submission</li>
                </ol>
            </div>
        </div>

        <!-- Expense Form -->
        <div class="row">
            <div class="col-lg-8 col-md-10">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Submit Your Expense</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo site_url('expenses/store'); ?>">
                            <!-- CSRF Token -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                            <div class="form-body">
                                <!-- Expense Title -->
                                <div class="form-group">
                                    <label>Expense Title</label>
                                    <input type="text" name="expense_title" class="form-control" placeholder="Enter expense title" required>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="expense_description" class="form-control" placeholder="Describe the expense" rows="3" required></textarea>
                                </div>

                                <!-- Amount and Date -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="number" name="expense_amount" class="form-control" placeholder="Enter amount" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" name="expense_date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Expense Category -->
                                <div class="form-group">
                                    <label>Expense Category</label>
                                    <select name="expense_category" class="form-control custom-select" required>
                                        <option value="" disabled selected>Select a category</option>
                                        <option value="Travel">Travel</option>
                                        <option value="Food">Food</option>
                                        <option value="Office Supplies">Office Supplies</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>

                                <!-- Upload Receipt -->
                                <div class="form-group">
                                    <label>Upload Receipt</label>
                                    <input type="file" name="expense_receipt" class="form-control">
                                </div>

                                <!-- Actions -->
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <button type="reset" class="btn btn-danger">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('backend/footer'); ?>
