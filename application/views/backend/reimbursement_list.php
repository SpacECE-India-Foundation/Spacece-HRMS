<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-list" style="color:#1976d2"></i> Reimbursement List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Reimbursement</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row m-b-10"> 
            <div class="col-12">
                <button type="button" class="btn btn-info">
                    <i class="fa fa-plus"></i>
                    <a href="<?php echo base_url(); ?>employee/Add_Reimbursement" class="text-white"> Add Reimbursement </a>
                </button>
            </div>
        </div>  
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white"> Reimbursement List </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="reimbursementTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Submission Date</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Admin Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Submission Date</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Admin Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach($reimbursement_list as $reimbursement): ?>
                                    <tr>
                                        <td><?php echo $reimbursement->id; ?></td>
                                        <td><?php echo $reimbursement->submission_date; ?></td>
                                        <td><?php echo $reimbursement->amount; ?></td>
                                        <td><?php echo $reimbursement->description; ?></td>
                                        <td><?php echo $reimbursement->status; ?></td>
                                        <td><?php echo $reimbursement->admin_remarks ?? 'N/A'; ?></td>
                                        <td>
                                            <a href="Edit_Reimbursement?ID=<?php echo $reimbursement->id; ?>" class="btn btn-sm btn-info">
                                                <i class="fa fa-pencil-square-o"></i> Edit
                                            </a>
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
</div>
<?php $this->load->view('backend/footer'); ?>
<script>
    $('#reimbursementTable').DataTable({
        "aaSorting": [[0,'asc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
</script>
