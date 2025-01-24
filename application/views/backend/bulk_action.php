<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-tasks"></i> Bulk Actions</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Bulk Actions</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <!-- Add a Back button here -->
        <div class="mb-3">
            <a href="javascript:history.back()" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <form id="bulkActionsForm" action="<?php echo base_url(); ?>Job/processBulkActions" method="post">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Select Jobs for Bulk Actions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="bulkJobListings" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll" onclick="toggleCheckboxes(this)"></th>
                                    <th>Job Title</th>
                                    <th>Department</th>
                                    <th>Location</th>
                                    <th>Job Status</th>
                                    <th>Work Mode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($jobs)): ?>
                                    <?php foreach ($jobs as $job): ?>
                                        <tr>
                                            <td><input type="checkbox" class="jobCheckbox" name="job_ids[]" value="<?php echo $job['id']; ?>"></td>
                                            <td><?php echo $job['job_title']; ?></td>
                                            <td><?php echo $job['department']; ?></td>
                                            <td><?php echo $job['location']; ?></td>
                                            <td><?php echo $job['job_status']; ?></td>
                                            <td><?php echo $job['work_mode']; ?></td>
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
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div class="form-group flex-grow-1">
                            <label for="workMode">Work Mode:</label>
                            <select name="work_mode" id="workMode" class="form-control">
                                <option value="">Select Work Mode</option>
                                <option value="remote">Remote</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="onsite">Onsite</option>
                            </select>
                        </div>

                        <div class="form-group flex-grow-1 mx-3">
                            <label for="jobStatus">Job Status:</label>
                            <select name="job_status" id="jobStatus" class="form-control">
                                <option value="">Select Job Status</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>

                        <div class="form-group ">
                            <button type="button" id="bulkDeleteButton" class="btn btn-danger">Bulk Delete</button>
                        </div>
                    </div>

                    <button type="submit" id="bulksubmit" class="btn btn-primary">Apply</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $this->load->view('backend/footer'); ?>

<script>
    function toggleCheckboxes(selectAllCheckbox) {
        var checkboxes = document.querySelectorAll('.jobCheckbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }
    document.getElementById('bulkDeleteButton').addEventListener('click', function() {
        if (confirm('Are you sure you want to delete the selected jobs?')) {
            var form = document.getElementById('bulkActionsForm');
            var actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'bulk_action';
            actionInput.value = 'delete';
            form.appendChild(actionInput);
            form.submit();
        }
    });
</script>

<style>
    [type=checkbox]:not(:checked) {
        position: initial;
        left: initial;
        opacity: 1;
    }
    [type=checkbox]:checked, [type=checkbox]:not(:checked) {
        position: initial;
        left: initial;
        opacity: 1;
    }

    .d-flex {
        display: flex;
        justify-content: space-between;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .mx-3 {
        margin-left: 1rem;
        margin-right: 1rem;
    }
</style>
