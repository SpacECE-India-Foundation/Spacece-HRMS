<!-- application/views/backend/my_reimbursements.php -->
<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">My Reimbursement Requests</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <a href="<?php echo base_url(); ?>reimbursement/showform" class="btn btn-info pull-right m-l-20">
                <i class="fa fa-plus"></i> New Request
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="reimbursementTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($requests as $request): ?>
                                    <tr>
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
                                        <td><?php echo $request['remarks'] ?? ''; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('reimbursement/download/'.$request['id']); ?>" 
                                               class="btn btn-sm btn-info" title="Download Receipt">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <?php if($request['status'] === 'Pending'): ?>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="deleteRequest(<?php echo $request['id']; ?>)" 
                                                    title="Delete Request">
                                                <i class="fa fa-trash"></i>
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

    <?php $this->load->view('backend/footer'); ?>
    <script>
        $(document).ready(function() {
            $('#reimbursementTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });
    </script>
</div>