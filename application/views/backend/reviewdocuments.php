<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-file-text" style="color:#1976d2"></i> Document Reviews</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Documents</li>
            </ol>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        <div class="row m-b-10">
            <div class="col-12">
                <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a href="<?php echo base_url(); ?>document/hrUploadForm" class="text-white">Upload Document</a></button>
                <button type="button" class="btn btn-primary">
                    <i class="fa fa-file"></i>
                    <a href="<?php echo site_url('document/addDocumentTitle'); ?>" class="text-white">Add Document Title for Employee</a>
                    </button>
            </div>
        </div>

        <!-- Document List -->
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Document List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="documentTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Document Name</th>
                                        <th>Status</th>
                                        <th>Document</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Document Name</th>
                                        <th>Status</th>
                                        <th>Document</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php if ($documents): ?>
                                        <?php foreach ($documents as $document): ?>
                                            <tr>
                                                <td><?php echo $document['first_name']; ?></td>
                                                <td><?php echo $document['name']; ?></td>
                                                <td><?php echo ucfirst($document['status']); ?></td>
                                                <td><a href="<?php echo base_url(); ?>assets/images/document/<?php echo $document['file_path']; ?>" target="_blank">View Document</a></td>
                                                <td>
                                                    <?php if ($document['status'] == 'Pending'): ?>
                                                        <a href="<?php echo site_url('document/acceptDocument/'.$document['id']); ?>" class="btn btn-success">Accept</a>
                                                        <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal<?php echo $document['id']; ?>">Reject</a>
                                                    <?php else: ?>
                                                        <span class="badge badge-<?php echo $document['status'] == 'Accepted' ? 'success' : 'danger'; ?>">
                                                            <?php echo ucfirst($document['status']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <!-- Reject Modal -->
                                            <div id="rejectModal<?php echo $document['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post" action="<?php echo site_url('document/denyDocument/'.$document['id']); ?>">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel">Reject Document</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="reason">Rejection Reason</label>
                                                                    <textarea name="reason" id="reason" class="form-control" rows="4" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger">Reject</button>
                                                                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No documents available.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal for Adding Document Title -->
        <div id="AddTitleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddTitleLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="<?php echo base_url('document/addDocumentTitle'); ?>">
                        <div class="modal-header">
                            <h4 class="modal-title" id="AddTitleLabel">Add Document Title</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                            </div>
                            <div class="form-group">
                                <label for="document_title">Document Title</label>
                                <input type="text" name="document_title" id="document_title" class="form-control" placeholder="Enter Document Title" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">Save Title</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php $this->load->view('backend/footer'); ?>

<script>
    $('#documentTable').DataTable({
        "aaSorting": [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
</script>
