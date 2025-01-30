<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-file-text" style="color:#FFA500"></i> Document Titles</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Document Titles</li>
            </ol>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        <div class="row m-b-10">
            <div class="col-12">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#AddTitleModal">
                    <i class="fa fa-plus"></i> Add Document Title
                </button>
            </div>
        </div>

        <!-- Document Title List -->
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Document Titles</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="documentTitleTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Document Title</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($document_titles): ?>
                                        <?php foreach ($document_titles as $title): ?>
                                            <tr>
                                                <td><?php echo $title['title']; ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#editModal<?php echo $title['id']; ?>">Edit</a>
                                                    <a href="<?php echo site_url('document/deleteDocumentTitle/'.$title['id']); ?>" class="btn btn-danger">Delete</a>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div id="editModal<?php echo $title['id']; ?>" class="modal fade" tabindex="-1" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post" action="<?php echo site_url('document/editDocumentTitle/'.$title['id']); ?>">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Edit Document Title</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="document_title">Document Title</label>
                                                                    <input type="text" name="document_title" id="document_title" class="form-control" value="<?php echo $title['title']; ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-info">Save Changes</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center">No document titles available.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Add Document Title Modal (Placed outside the loop) -->
<div id="AddTitleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddTitleLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?php echo base_url('document/addDocumentTitle1'); ?>">
                <div class="modal-header">
                    <h4 class="modal-title" id="AddTitleLabel">Add Document Title</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
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

<?php $this->load->view('backend/footer'); ?>

<script>
    $('#documentTitleTable').DataTable({
        "aaSorting": [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
</script>
