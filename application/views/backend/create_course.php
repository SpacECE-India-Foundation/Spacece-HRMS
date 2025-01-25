<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>


<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Course Assignment</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Course Assignment</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row m-b-10"> 
            <div class="col-12">
                <button type="button" class="btn btn-info"><a href="<?php echo base_url(); ?>employee/create_assignment" class="text-white"><i class="" aria-hidden="true"></i>  Course Assignment</a></button>
                <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>course/reports" class="text-white"><i class="" aria-hidden="true"></i>  Course Reports</a></button>
            </div>
        </div>  
        <div class="row">
            <div class="col-6">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white"> Create Course </h4>
                    </div>
                    <div class="card-body">
                    <form method="post" action="<?php echo base_url('employee/add_course'); ?>" id="courseform" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
        <label>Department</label>
            <select name="department" class="form-control" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?php echo $department['dep_name']; ?>">
                        <?php echo $department['dep_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        </div>
        <div class="form-group">
    <label>Mandatory?</label>
    <div class="form-check">
        <input type="checkbox" name="mandatory" class="form-check-input" value="0">
        <label class="form-check-label">Check if this course is mandatory</label>
    </div>
</div>
        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Course URL</label>
            <input type="url" name="course_url" class="form-control" placeholder="Add link for accessing the course" required>
        </div>
        <div class="form-group">
            <label>Recurrence</label>
            <input type="text" name="recurrence" class="form-control" placeholder="Set a time for recurrence" required>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id" value="" class="form-control" id="recipient-name1">                                       
        <button type="button" class="btn btn-secondary" id="uploadFiles">Upload Relevant Files</button>
        <button type="submit" id="courseSubmit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    </div>
</form>

                    </div>
                </div>
            </div>
        </div>

           <!-- Courses List Section -->
    <div class="row">
        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white"> Courses List </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Department</label>
                                <select class="form-control" name="department" id="departmentFilter">
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo $department['dep_name']; ?>"><?php echo $department['dep_name']; ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" name="date" id="dateFilter">
                            </div>
                        </div>
                        <div class="col-md-4">
    <div class="form-group">
        <label for="natureFilter">Nature</label> <!-- Correctly placed label -->
        <select class="form-control" name="nature" id="natureFilter">
            <option value="">Select Nature</option>
            <option value="mandatory">Mandatory</option>
            <option value="not_mandatory">Not Mandatory</option>
        </select>
    </div>
</div>
                    </div>
                    <div class="table-responsive ">
                        <table id="coursesList" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                    <th>Nature</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($courses)){ ?>
                                    <?php foreach($courses as $course): ?>
                                        <tr>
                                            <td><?php echo $course->title; ?></td>
                                            <td><?php echo $course->department; ?></td>
                                            <td><?php echo $course->description; ?></td>
                                            <td><?php echo $course->due_date; ?></td>
                                            <td><?php echo $course->mandatory ? 'Mandatory' : 'Not Mandatory'; ?></td>
                                            <td>
                                                <a href="edit_course?id=<?php echo $course->course_id; ?>" class="btn btn-sm btn-info">Edit</a>
                                                <a href="view_course?id=<?php echo $course->course_id; ?>" class="btn btn-sm btn-warning">View</a>
                                                <a href="delete_course?id=<?php echo $course->course_id; ?>" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                                                  
<?php $this->load->view('backend/footer'); ?>

<script>
    $('#attendance123').DataTable({
        "aaSorting": [[0,'asc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    $('#coursesList').DataTable({
        "aaSorting": [[0,'asc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
</script>
<script>
    // Basic form validation
    $('#courseform').submit(function(event) {
        var title = $('input[name="title"]').val().trim();
        var description = $('textarea[name="description"]').val().trim();
        
        if (!title || !description) {
            alert('Please fill in all required fields.'); // Alert if fields are empty
            event.preventDefault(); // Prevent form submission
        }
    });

    // Handle the Upload Relevant Files button click
    $('#uploadFiles').click(function() {
        // Trigger file input dialog or handle file upload logic here
        alert('File upload dialog would be triggered here.');
        // You can implement file upload logic as needed
    });
    $('form').submit(function(event) {
    console.log('Mandatory:', $('input[name="mandatory"]').is(':checked')); // Log checkbox state
});
    
</script>