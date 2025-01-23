<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Document Status</h3>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">My Document</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="documentTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Document Name</th>
                                        <th>Document Status</th>
                                        <th>Reason</th>  <!-- Add a new column for reason -->
                                        <th>Document</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Document Name</th>
                                        <th>Document Status</th>
                                        <th>Reason</th>  <!-- Add a new column for reason -->
                                        <th>Document</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php if (!empty($documents)): ?>
                                        <?php foreach ($documents as $document): ?>
                                            <tr>
                                                <td><?php echo $document['name']; ?></td>
                                                <td>
                                                    <?php 
                                                    if ($document['status'] == 'Pending') {
                                                        echo '<span class="badge badge-warning">Pending</span>';
                                                    } elseif ($document['status'] == 'Accepted') {
                                                        echo '<span class="badge badge-success">Accepted</span>';
                                                    } else {
                                                        echo '<span class="badge badge-danger">Rejected</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    // Display the reason if the document is rejected
                                                    if ($document['status'] == 'Rejected') {
                                                        echo '<span class="text-danger">' . $document['reason'] . '</span>';
                                                    } else {
                                                        echo 'N/A';  // No reason needed for Pending or Accepted status
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>assets/images/document/<?php echo $document['file_path']; ?>" target="_blank">View Document</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No document found or uploaded for review.</td>  <!-- Update colspan -->
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

<?php $this->load->view('backend/footer'); ?>
<script>
    $('#documentTable').DataTable({
        "aaSorting": [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
</script>
