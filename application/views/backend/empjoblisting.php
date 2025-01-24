<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-briefcase" aria-hidden="true"></i> Available Jobs</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Available Jobs</li>
            </ol>
        </div>
    </div>
    <div class="message"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white"><i class="fa fa-briefcase" aria-hidden="true"></i> Available Jobs</h4>
                    </div>
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="filters">
                            <select id="filterDepartment" class="form-control">
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo $department['dep_name']; ?>"><?php echo $department['dep_name']; ?></option>
                                <?php endforeach; ?>
                            </select>

                            <select id="filterLocation" class="form-control">
                                <option value="">Select Location</option>
                                <?php foreach ($jobs as $job): ?>
                                    <option value="<?php echo $job['location']; ?>"><?php echo $job['location']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            
                            <select id="filterWorkMode" class="form-control">
                                <option value="">Select Work Mode</option>
                                <option value="Remote">Remote</option>
                                <option value="On-site">On-site</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>


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
                                                    <!-- Copy Link Button -->
                                                    <button class="btn btn-sm btn-info copy-link-btn" data-id="<?php echo $job['id']; ?>" onclick="copyLink()">
                                                        <i class="fa fa-link"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-info share-job-btn" data-id="<?php echo $job['id']; ?>" onclick="shareJob(<?php echo $job['id']; ?>)">
                                                        <i class="fa fa-share-alt"></i>
                                                    </button>
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
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="linkCopiedModal" tabindex="-1" role="dialog" aria-labelledby="linkCopiedModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkCopiedModalLabel">Link Copied</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                The link has been copied to your clipboard.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('backend/footer'); ?>

<script>
   $(document).ready(function () {
    // Function to filter table rows based on selected filters
    function filterJobs() {
        var department = $('#filterDepartment').val().toLowerCase();
        var location = $('#filterLocation').val().toLowerCase();
        var workMode = $('#filterWorkMode').val().toLowerCase();

        $('#jobListings tbody tr').each(function() {
            var rowDepartment = $(this).find('td:nth-child(2)').text().toLowerCase();
            var rowLocation = $(this).find('td:nth-child(3)').text().toLowerCase();
            var rowWorkMode = $(this).find('td:nth-child(5)').text().toLowerCase();
            rowWorkMode = rowWorkMode.replace('-', '');
            workMode = workMode.replace('-', '');
            // Log the values for debugging purposes
            console.log('Filtering:', department, location, workMode);
            console.log('Row values:', rowDepartment, rowLocation, rowWorkMode);

            // Check if the row matches the selected filters
            if ((department === "" || rowDepartment.includes(department)) &&
                (location === "" || rowLocation.includes(location)) &&
                (workMode === "" || rowWorkMode.includes(workMode))) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Attach the filter function to the dropdowns
    $('#filterDepartment, #filterLocation, #filterWorkMode').change(function() {
        filterJobs();
    });

    // Initial filtering when the page loads
    filterJobs();
});



    // Function to handle the Copy Link button
    // Function to handle the Copy Link button
function copyLink() {
    const specificLink = "https://docs.google.com/forms/d/1t584DVsdKfGP5CP5tSIUO4zOTrstzxoVY2PLtX89z-c/viewform?pli=1&pli=1&edit_requested=true";
    navigator.clipboard.writeText(specificLink)
        .then(() => {
            // Show the modal when the link is copied
            $('#linkCopiedModal').modal('show');
        })
        .catch(error => console.error('Error copying link:', error));
}


    // Function to share the job link
    function shareJob(jobId) {
        const jobLink = `https://docs.google.com/forms/d/1t584DVsdKfGP5CP5tSIUO4zOTrstzxoVY2PLtX89z-c/viewform?pli=1&pli=1&edit_requested=true`;
        if (navigator.share) {
            navigator.share({
                title: 'Check out this job!',
                url: jobLink
            }).catch(error => console.error('Error sharing:', error));
        } else {
            alert('Sharing is not supported in your browser. Use the Copy Link button instead.');
        }
    }
</script>

<style>
    .filters {
        margin-bottom: 20px;
    }
    .filters select {
        margin-right: 10px;
    }
    
    .filters {
    display: flex;
    gap: 15px; /* Adjust the spacing between the filters */
    margin-bottom: 20px;
}

.filters select {
    flex: 1; /* Ensure the select boxes take equal space */
    margin-right: 0; /* Remove right margin for a clean look */
}

.filters select:last-child {
    margin-right: 0; /* Ensures no extra margin on the last select element */
}

</style>
