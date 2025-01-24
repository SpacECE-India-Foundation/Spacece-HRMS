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
                <button type="button" class="btn btn-info"><i class="fa fa-arrow-left"></i><a href="<?php echo base_url(); ?>document/DocumentView" class="text-white"> Document List</a></button>
            </div>
        </div>
        
        <div class="row">
            <div class="col-6">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Add HR</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="addHR" id="uploadform" enctype="multipart/form-data">
                            <div class="modal-body">
                            <div class="form-group">
                                    <label>Employee</label>
                                    <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="emid" required>
                                        <?php if(!empty($attval->em_code)): ?>
                                            <option value="<?php echo $attval->em_code ?>"><?php echo $attval->first_name.' '.$attval->last_name ?></option>
                                        <?php else: ?>
                                            <option value="#">Select Here</option>
                                        <?php endif; ?>
                                        <?php foreach($employee as $value): ?>
                                            <option value="<?php echo $value->em_code ?>" 
                                                <?php echo (!empty($attval->em_code) && $attval->em_code == $value->em_code) ? 'selected' : ''; ?>>
                                                <?php echo $value->first_name.' '.$value->last_name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Add</button>
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
