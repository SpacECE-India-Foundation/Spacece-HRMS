<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-file-text" style="color:#1976d2"></i> View Document</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">View Document</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row m-b-10">
            <div class="col-12">
                <!-- Add buttons for document upload or other actions if needed -->
                <!-- Example: -->
                <!-- <button type="button" class="btn btn-info"><i class="fa fa-plus"></i> Upload Document</button> -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white"> Document Details </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="documentTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Document Name</th>
                                        <th>Document</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Document Name</th>
                                        <th>Document</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php if (!empty($document)): ?>
                                        <?php foreach ($document as $doc): ?> <!-- Loop through all documents -->
                                            <tr>
                                                <td>
                                                    <?php echo $doc->document_name; ?> <!-- Accessing the document name -->
                                                </td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" onclick="togglePreview(<?php echo $doc->id; ?>)">Show Preview</button>
                                                    <div id="previewSection_<?php echo $doc->id; ?>" style="display:none;">
                                                        <!-- Document preview will be shown here -->
                                                        <embed src="<?php echo base_url($doc->document_file); ?>" type="application/pdf" width="100%" height="400px">
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url($doc->document_file); ?>" class="btn btn-primary" download>Download Document</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No document uploaded for this employee.</td>
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
    function togglePreview(docId) {
        var previewSection = document.getElementById('previewSection_' + docId);
        if (previewSection.style.display === "none") {
            previewSection.style.display = "block"; // Show the preview section
        } else {
            previewSection.style.display = "none"; // Hide the preview section
        }
    }

    $('#documentTable').DataTable({
        "aaSorting": [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
</script>
