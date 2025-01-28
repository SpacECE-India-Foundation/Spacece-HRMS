<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <!-- Page Header -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-calendar" aria-hidden="true"></i> Meetings</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Meetings</li>
            </ol>
        </div>
    </div>

    <!-- Message Section -->
    <div class="message">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="container-fluid">

        <!-- Add New Meeting Button -->
        <div class="row m-b-10">
            <div class="col-12">
                <button type="button" class="btn btn-info">
                    <i class="fa fa-plus"></i>
                    <a href="<?php echo base_url(); ?>Meetings/create" class="text-white"> Add New Meeting</a>
                </button>
            </div>
        </div>

        <!-- Meetings Table -->
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white"><i class="fa fa-calendar" aria-hidden="true"></i> Meetings List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="meetingsTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php if (!empty($meetings)): ?>
        <?php foreach ($meetings as $meeting): ?>
            <?php 
                $meetingId = is_array($meeting) ? $meeting['id'] : $meeting->id;
                $meetingTitle = is_array($meeting) ? $meeting['meeting_title'] : $meeting->meeting_title;
                $meetingDate = is_array($meeting) ? $meeting['meeting_date'] : $meeting->meeting_date;
                $meetingTime = is_array($meeting) ? $meeting['meeting_time'] : $meeting->meeting_time;
                $department = is_array($meeting) ? $meeting['department'] : $meeting->dep_name;
                $designation = is_array($meeting) ? $meeting['designation'] : $meeting->des_name;
                $status = is_array($meeting) ? $meeting['status'] : $meeting->status;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($meetingTitle); ?></td>
                <td><?php echo htmlspecialchars($meetingDate); ?></td>
                <td><?php echo htmlspecialchars($meetingTime); ?></td>
                <td><?php echo htmlspecialchars($department); ?></td>
                <td><?php echo htmlspecialchars($designation); ?></td>
                <td><?php echo htmlspecialchars($status); ?></td>
                <td>
                    <a href="<?php echo base_url(); ?>Meetings/meeting_views?I=<?php echo base64_encode($meetingId); ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?php echo base_url(); ?>Meetings/delete/<?php echo $meetingId; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php if (!empty($meeting['uploaded_files'])): ?>
        <?php foreach ($meeting['uploaded_files'] as $file): ?>
            <a href="<?php echo base_url(); ?>assets/uploads/<?php echo $file['file_name']; ?>" target="_blank" class="btn btn-sm btn-info">View Uploaded File</a><br>
        <?php endforeach; ?>
    <?php else: ?>
        <span>No uploaded files</span>
    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" class="text-center">No meetings scheduled.</td>
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
    $(document).ready(function () {
        $('#meetingsTable').DataTable({
            "aaSorting": [[1, 'asc']],
            "dom": 'Bfrtip',
            "buttons": [
                {
                    extend: 'copy',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'print',
                    className: 'btn btn-primary'
                }
            ]
        });
    });
</script>
