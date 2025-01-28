<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">Schedule New Meeting</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Schedule Meeting</li>
                </ol>
            </div>
        </div>

        <!-- Meeting Form -->
        <div class="row">
            <div class="col-lg-8 col-md-10">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Schedule New Meeting</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo site_url('meetings/store'); ?>">
                            <!-- CSRF Token -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                            <div class="form-body">
                                <!-- Title -->
                                <div class="form-group">
                                    <label>Title of the Meeting</label>
                                    <input type="text" name="meeting_title" class="form-control" placeholder="Enter meeting title" required>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label>Description/Agenda</label>
                                    <textarea name="meeting_description" class="form-control" placeholder="Add meeting agenda or description" rows="3" required></textarea>
                                </div>

                                <!-- Date and Time -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" name="meeting_date" class="form-control" required id="meeting_date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Time</label>
                                            <input type="time" name="meeting_time" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                               <!-- Departments and Designations -->
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="departments">Departments Involved</label>
            <select id="departments" name="departments[]" class="form-control custom-select" multiple required>
                <?php if (!empty($departments)): ?>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department->id; ?>"><?php echo $department->dep_name; ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No departments available</option>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="designations">Designations Invited</label>
            <select id="designations" name="designations[]" class="form-control custom-select" multiple required>
                <?php if (!empty($designations)): ?>
                    <?php foreach ($designations as $designation): ?>
                        <option value="<?php echo $designation->id; ?>"><?php echo $designation->des_name; ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No designations available</option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>

                                <!-- Recurrence -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Recurrence</label>
                                            <select name="recurrence" class="form-control custom-select" required>
                                                <option value="" disabled selected>Please Select recurrence</option>
                                                <option value="None">None</option>
                                                <option value="Weekly">Weekly</option>
                                                <option value="Monthly">Monthly</option>
                                                <option value="Yearly">Yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="form-actions">
                                        <button type="submit" class="btn btn-success">Save</button>
                                        <button type="button" class="btn btn-danger" onclick="window.location.href='<?php echo site_url('meetings'); ?>'">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Select2 Initialization -->
<script>
    $(document).ready(function() {
        $('#departments').select2({
            placeholder: "Select departments",
            allowClear: true
        });

        $('#designations').select2({
            placeholder: "Select designations",
            allowClear: true
        });
    });
</script>
<script>
    // Get today's date
    const today = new Date();
    const dd = String(today.getDate()).padStart(2, '0');
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    const yyyy = today.getFullYear();

    // Format the date to YYYY-MM-DD
    const formattedDate = yyyy + '-' + mm + '-' + dd;

    // Set the min attribute of the input
    document.getElementById('meeting_date').setAttribute('min', formattedDate);
</script>

<?php $this->load->view('backend/footer'); ?>