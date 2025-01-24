<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-briefcase" aria-hidden="true"></i> Job Listings</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Job Listings</li>
            </ol>
        </div>
    </div>
    <div class="message"></div>
    <div class="container-fluid">
        <div class="row m-b-10">
            <div class="col-12">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addJobModal"><i class="fa fa-plus"></i> Add Job Listing</button>
                <a href="<?php echo base_url(); ?>Job/bulkActions" class="btn btn-primary" style=""><i class="fa fa-tasks"></i> Bulk Actions</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white"><i class="fa fa-briefcase" aria-hidden="true"></i> Job Listings</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="jobListings" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Department</th>
                                        <th>Location</th>
                                        <th>Job Status</th>
                                        <th>Work Mode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Department</th>
                                        <th>Location</th>
                                        <th>Job Status</th>
                                        <th>Work Mode</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                <?php if (!empty($jobs)): ?>
                                    <?php foreach ($jobs as $job): ?>
                                        <tr>
                                            <td><?php echo $job['job_title']; ?></td>
                                            <td><?php echo $job['department']; ?></td>
                                            <td><?php echo $job['location']; ?></td>
                                            <td><?php echo $job['job_status']; ?></td>
                                            <td><?php echo $job['work_mode']; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-info edit-job-btn" data-id="<?php echo $job['id']; ?>" data-toggle="modal" data-target="#editJobModal"><i class="fa fa-pencil-square-o"></i></button>
                                                <button class="btn btn-sm btn-info delete-job-btn" data-id="<?php echo $job['id']; ?>"><i class="fa fa-trash-o"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No jobs found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal to Add Job -->
        <div id="addJobModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo base_url(); ?>Job/addJob" method="post">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Job Listing</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Job Title</label>
                                <input type="text" name="job_title" class="form-control" required>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3">
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
                    
                                <div class="col-md-3">
    <div class="form-group">
        <label>Location</label>
        <select name="location" class="form-control" required>
            <option value="">Select Location</option>
            <option value="Andhra Pradesh">Andhra Pradesh</option>
            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
            <option value="Assam">Assam</option>
            <option value="Bihar">Bihar</option>
            <option value="Chhattisgarh">Chhattisgarh</option>
            <option value="Goa">Goa</option>
            <option value="Gujarat">Gujarat</option>
            <option value="Haryana">Haryana</option>
            <option value="Himachal Pradesh">Himachal Pradesh</option>
            <option value="Jharkhand">Jharkhand</option>
            <option value="Karnataka">Karnataka</option>
            <option value="Kerala">Kerala</option>
            <option value="Madhya Pradesh">Madhya Pradesh</option>
            <option value="Maharashtra">Maharashtra</option>
            <option value="Manipur">Manipur</option>
            <option value="Meghalaya">Meghalaya</option>
            <option value="Mizoram">Mizoram</option>
            <option value="Nagaland">Nagaland</option>
            <option value="Odisha">Odisha</option>
            <option value="Punjab">Punjab</option>
            <option value="Rajasthan">Rajasthan</option>
            <option value="Sikkim">Sikkim</option>
            <option value="Tamil Nadu">Tamil Nadu</option>
            <option value="Telangana">Telangana</option>
            <option value="Tripura">Tripura</option>
            <option value="Uttar Pradesh">Uttar Pradesh</option>
            <option value="Uttarakhand">Uttarakhand</option>
            <option value="West Bengal">West Bengal</option>
        </select>
    </div>
</div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Job Status</label>
                                        <select name="job_status" class="form-control" required>
                                            <option value="Open">Open</option>
                                            <option value="Closed">Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Work Mode</label>
                                        <select name="work_mode" class="form-control" required>
                                            <option value="Remote">Remote</option>
                                            <option value="Hybrid">Hybrid</option>
                                            <option value="Onsite">Onsite</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <div class="">
                                    <div class="form-group">
                                        <label>Job Description</label>
                                        <textarea name="description" class="form-control" rows="5" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            
                            <button type="reset" class="btn btn-secondary">Reset Form</button>
                            <button type="submit" class="btn btn-info">Save & Publish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal to Edit Job -->
        <div id="editJobModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editJobForm" action="<?php echo base_url(); ?>Job/updateJob" method="post">
                        <input type="hidden" name="id" id="editJobId">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Job Listing</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Job Title</label>
                                <input type="text" name="job_title" id="editJobTitle" class="form-control" required>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3">
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
                            
                                <div class="col-md-3">
    <div class="form-group">
        <label>Location</label>
        <select name="location" class="form-control" id="editlocation" required>
            <option value="">Select Location</option>
            <option value="Andhra Pradesh">Andhra Pradesh</option>
            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
            <option value="Assam">Assam</option>
            <option value="Bihar">Bihar</option>
            <option value="Chhattisgarh">Chhattisgarh</option>
            <option value="Goa">Goa</option>
            <option value="Gujarat">Gujarat</option>
            <option value="Haryana">Haryana</option>
            <option value="Himachal Pradesh">Himachal Pradesh</option>
            <option value="Jharkhand">Jharkhand</option>
            <option value="Karnataka">Karnataka</option>
            <option value="Kerala">Kerala</option>
            <option value="Madhya Pradesh">Madhya Pradesh</option>
            <option value="Maharashtra">Maharashtra</option>
            <option value="Manipur">Manipur</option>
            <option value="Meghalaya">Meghalaya</option>
            <option value="Mizoram">Mizoram</option>
            <option value="Nagaland">Nagaland</option>
            <option value="Odisha">Odisha</option>
            <option value="Punjab">Punjab</option>
            <option value="Rajasthan">Rajasthan</option>
            <option value="Sikkim">Sikkim</option>
            <option value="Tamil Nadu">Tamil Nadu</option>
            <option value="Telangana">Telangana</option>
            <option value="Tripura">Tripura</option>
            <option value="Uttar Pradesh">Uttar Pradesh</option>
            <option value="Uttarakhand">Uttarakhand</option>
            <option value="West Bengal">West Bengal</option>
        </select>
    </div>
</div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Job Status</label>
                                        <select name="job_status" id="editJobStatus" class="form-control" required>
                                            <option value="Open">Open</option>
                                            <option value="Closed">Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Work Mode</label>
                                        <select name="work_mode" id="editWorkMode" class="form-control" required>
                                            <option value="Remote">Remote</option>
                                            <option value="Hybrid">Hybrid</option>
                                            <option value="Onsite">Onsite</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="">
                                    <div class="form-group">
                                        <label>Job Description</label>
                                        <textarea name="description" id="editDescription" class="form-control" rows="5" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="reset" class="btn btn-secondary">Reset Form</button>
                            <button type="submit" class="btn btn-info">Save & Publish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $this->load->view('backend/footer'); ?>

<script>
    $(document).ready(function () {
        $(".edit-job-btn").click(function (e) {
            e.preventDefault();
            var jobId = $(this).data('id'); // Get the job ID
            $('#editJobForm').trigger("reset"); // Reset the form to clear any previous data
            $('#editJobModal').modal('show'); // Show the edit modal

            // Fetch the job details via AJAX
            $.ajax({
                url: '<?php echo base_url(); ?>Job/getJob/' + jobId,
                method: 'GET',
                dataType: 'json',
            }).done(function (response) {
                console.log(response);
                $('#editJobForm').find('[name="id"]').val(response.id);
                $('#editJobForm').find('[name="job_title"]').val(response.job_title);
                $('#editJobForm').find('[name="department"]').val(response.department).trigger('change');
                $('#editJobForm').find('[name="location"]').val(response.location);
                $('#editJobForm').find('[name="job_status"]').val(response.job_status);
                $('#editJobForm').find('[name="work_mode"]').val(response.work_mode);
                $('#editJobForm').find('[name="description"]').val(response.description);
            }).fail(function () {
                alert("There was an error fetching the job details.");
            });
        });

        $(document).on('click', '.delete-job-btn', function () {
            const jobId = $(this).data('id');
            if (confirm('Are you sure you want to delete this job?')) {
                window.location.href = '<?php echo base_url(); ?>Job/deleteJob/' + jobId;
            }
        });
    });
</script>
<style>
    .modal-dialog{
        max-width: 80%;
    }
</style>