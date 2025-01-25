<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Edit Course</h3>
        </div>
    </div>
    <form method="post" action="<?php echo base_url('employee/update_course'); ?>" id="courseform" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo isset($course->title) ? $course->title : ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" required><?php echo isset($course->description) ? $course->description : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Department</label>
                <select name="department" class="form-control" required>
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['dep_name']; ?>" <?php echo (isset($course->department) && $department['dep_name'] == $course->department) ? 'selected' : ''; ?>>
                            <?php echo $department['dep_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mandatory?</label>
                <div class="form-check">
                    <input type="checkbox" name="mandatory" class="form-check-input" value="1" <?php echo (isset($course->mandatory) && $course->mandatory) ? 'checked' : ''; ?>>
                    <label class="form-check-label">Check if this course is mandatory</label>
                </div>
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" value="<?php echo isset($course->due_date) ? $course->due_date : ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Course URL</label>
                <input type="url" name="course_url" class="form-control" value="<?php echo isset($course->course_url) ? $course->course_url : ''; ?>" placeholder="Add link for accessing the course" required>
            </div>
            <div class="form-group">
                <label>Recurrence</label>
                <input type="text" name="recurrence" class="form-control" value="<?php echo isset($course->recurrence) ? $course->recurrence : ''; ?>" placeholder="Set a time for recurrence" required>
            </div>
        </div>
        <div class="modal-footer">         
        <button type="button" class="btn btn-secondary" id="uploadFiles">Upload Relevant Files</button>            
            <button type="button" class="btn btn-secondary cancel-button" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
<?php $this->load->view('backend/footer'); ?>

<script>
   $(document).ready(function () {
    // Form validation
    $('#courseform').submit(function (event) {
        var title = $('input[name="title"]').val().trim();
        var description = $('textarea[name="description"]').val().trim();

        if (!title || !description) {
            alert('Please fill in all required fields.');
            event.preventDefault();
        } else {
            console.log('Form validated successfully.');
        }
    });

        // Cancel button functionality
        $('.cancel-button').click(function() {
            window.location.href = '<?php echo base_url("employee/createcourse"); ?>'; // Redirect to the create course page
        });

        // Log form submission
        $('#courseform').submit(function(event) {
            console.log('Form submitted'); 
        });
    });
</script>
