<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Upload Document Here</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Upload Document</li>
            </ol>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row m-b-10">
            <div class="col-12">
                <button type="button" class="btn btn-info"><i class="fa fa-arrow-left"></i><a href="<?php echo base_url(); ?>document/DocumentList" class="text-white"> Document List</a></button>
            </div>
        </div>
        
        <div class="row">
            <div class="col-6">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Upload Document</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="uploadDocument" id="uploadform" enctype="multipart/form-data">
                            <div class="modal-body">
                            <div class="form-group">
    <label for="name">Document Name:</label>
    <select name="name" class="form-control" required>
        <option value="" selected disabled>Select a Document Name</option>
        <?php foreach ($document_titles as $doc): ?>
            <option value="<?= htmlspecialchars($doc['title']) ?>"><?= htmlspecialchars($doc['title']) ?></option>
        <?php endforeach; ?>
    </select>
</div>


                                <div class="form-group">
                                    <label for="document">Upload Document (PDF only):</label>
                                    <input type="file" name="document" id="document" class="form-control" required onchange="previewDocument()">
                                </div>
                            </div>
                            <div id="previewSection" style="display:none;">
                                <h5>Document Preview:</h5>
                                <embed id="documentPreview" src="" type="application/pdf" width="100%" height="400px">
                                <div class="mt-3">
                                    <button type="button" class="btn btn-danger" id="deleteButton" onclick="deleteDocument()">Delete</button>
                                    <button type="button" class="btn btn-info" id="replaceButton" onclick="replaceDocument()">Replace</button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('backend/footer'); ?>

<script>
    function previewDocument() {
        const fileInput = document.getElementById('document');
        const previewSection = document.getElementById('previewSection');
        const documentPreview = document.getElementById('documentPreview');

        // Ensure the file is selected and it's a PDF
        if (fileInput.files && fileInput.files[0] && fileInput.files[0].type === 'application/pdf') {
            const fileURL = URL.createObjectURL(fileInput.files[0]);
            documentPreview.src = fileURL;
            previewSection.style.display = 'block';
        } else {
            alert("Please upload a PDF document.");
        }
    }

    function deleteDocument() {
        const fileInput = document.getElementById('document');
        fileInput.value = ''; // Clear the file input
        document.getElementById('previewSection').style.display = 'none'; // Hide preview
    }

    function replaceDocument() {
        const fileInput = document.getElementById('document');
        fileInput.click(); // Trigger the file input to replace document
    }
</script>
