<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Notice Board</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Notice Board</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row m-b-10"> 
            <div class="col-12">
                <button type="button" class="btn btn-info">
                    <i class="fa fa-plus"></i>
                    <a data-toggle="modal" data-target="#noticemodel" data-whatever="@getbootstrap" class="text-white">Add Notice</a>
                </button>
            </div>
        </div> 
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white"> Notice</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Title</th>
                                        <th>File</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Title</th>
                                        <th>File</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach($notice as $value): ?>
                                    <tr>
                                        <td><?php echo $value->id; ?></td>
                                        <td><?php echo $value->title; ?></td>
                                        <td><a href="<?php echo base_url(); ?>assets/images/notice/<?php echo $value->file_url; ?>" target="_blank"><?php echo $value->file_url; ?></a></td>
                                        <td><?php echo $value->date; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('Notice/delete_notice/'.$value->id); ?>"  onclick="return confirm('Are you sure you want to delete this notice?');" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-trash-o"></i></a>
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

        <!-- Add Notice Modal -->
        <div class="modal fade" id="noticemodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Notice Board</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form role="form" method="post" action="Published_Notice" id="btnSubmit" enctype="multipart/form-data">
<<<<<<< HEAD
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="message-text" class="control-label">Notice Title</label>
                                <textarea class="form-control" name="title" id="noticeTitle" required minlength="25" maxlength="150"></textarea>
                                <span id="titleError" class="error" style="display: none;">Title must contain at least one alphabet.</span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Document</label>
                                <input type="file" name="file_url" class="form-control" id="fileInput" required>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="control-label">Published Date</label>
                                <input type="date" name="nodate" class="form-control" id="dateInput" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="noticeTitle" class="control-label">Notice Title <span style="color: red;">*</span></label>
                            <textarea class="form-control" name="title" id="noticeTitle" required minlength="25" maxlength="150"></textarea>
                            <span id="titleError" class="error" style="display: none;">Title must contain at least one alphabet.</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Document <span style="color: red;">*</span></label>
                            <input type="file" name="file_url" class="form-control" id="fileInput" required>
                        </div>
                        <div class="form-group">
                            <label for="dateInput" class="control-label">Published Date <span style="color: red;">*</span></label>
                            <input type="date" name="nodate" class="form-control" id="dateInput" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
>>>>>>> 0b32b76 (bugfix-pooja)
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('backend/footer'); ?>

<style>
.error {
    color: red;
    font-size: 14px;
    margin-top: 5px;
}
.input-error {
    border-color: red;
}
</style>

<script>
// Real-time validation for the title
$('#noticeTitle').on('input', function() {
    let title = $(this).val().trim();
    if (title.length > 0 && !/[a-zA-Z]/.test(title)) {
        $('#titleError').show();
        $(this).addClass('input-error');
    } else {
        $('#titleError').hide();
        $(this).removeClass('input-error');
    }
});

// Real-time validation for the file input
$('#fileInput').on('change', function() {
    let file = $(this).val();
    if (!file) {
        $('#fileError').show();
        $(this).addClass('input-error');
    } else {
        $('#fileError').hide();
        $(this).removeClass('input-error');
    }
});

// Real-time validation for the date input
$('#dateInput').on('change', function() {
    let date = $(this).val();
    if (!date) {
        $('#dateError').show();
        $(this).addClass('input-error');
    } else {
        $('#dateError').hide();
        $(this).removeClass('input-error');
    }
});

// Submit validation for the form
$('#btnSubmit').submit(function(event) {
    let isValid = true;

    // Validate title
    let title = $('#noticeTitle').val().trim();
    if (title.length > 0 && !/[a-zA-Z]/.test(title)) {
        isValid = false;
        $('#titleError').show();
        $('#noticeTitle').addClass('input-error').focus();
    } else if (!title) {
        isValid = false;
        $('#titleError').hide();
        $('#noticeTitle').addClass('input-error').focus();
    }

    // Validate file
    let file = $('#fileInput').val();
    if (!file) {
        isValid = false;
        $('#fileError').show();
        $('#fileInput').addClass('input-error').focus();
    }

    // Validate date
    let date = $('#dateInput').val();
    if (!date) {
        isValid = false;
        $('#dateError').show();
        $('#dateInput').addClass('input-error').focus();
    }

    // Prevent form submission if validation fails
    if (!isValid) {
        event.preventDefault();
    }
});


</script>
