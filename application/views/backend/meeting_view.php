<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">Meeting Details</h3>
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
                        <h4 class="m-b-0 text-white">Meeting Details</h4>
                    </div>
                    <div class="card-body">
                    <form method="post" action="<?php echo site_url('meetings/update/' . $meeting_details['id']); ?>">
                    <!-- CSRF Token -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                            <div class="form-body">
                                <!-- Title (1 column) -->
                                <div class="form-group">
                                    <label>Title of the Meeting</label>
                                    <input type="text" name="meeting_title" class="form-control" placeholder="Enter meeting title" value="<?php echo isset($meeting_details['meeting_title']) ? htmlspecialchars($meeting_details['meeting_title']) : ''; ?>" required>
                                </div>

                                <!-- Description (1 column) -->
                                <div class="form-group">
                                    <label>Description/Agenda</label>
                                    <textarea name="meeting_description" class="form-control" placeholder="Add meeting agenda or description" rows="3" required><?php echo isset($meeting_details['meeting_description']) ? htmlspecialchars($meeting_details['meeting_description']) : ''; ?></textarea>
                                </div>

                                <!-- Date and Time (2 columns) -->
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" name="meeting_date" value="<?php echo isset($meeting_details['meeting_date']) ? htmlspecialchars($meeting_details['meeting_date']) : ''; ?>" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Time</label>
                                            <input type="time" name="meeting_time" class="form-control" value="<?php echo isset($meeting_details['meeting_time']) ? htmlspecialchars($meeting_details['meeting_time']) : ''; ?>"  required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Departments and Designations (2 columns) -->
                                <div class="row">
                                    <!-- Departments Involved Dropdown -->
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="departments">Departments Involved</label>
                                        <select id="departments" name="departments[]" class="form-control custom-select" multiple required>
                                            <?php foreach ($departments as $department): ?>
                                                <option value="<?php echo $department->id; ?>" 
                                                    <?php echo (in_array($department->id, $selected_departments)) ? 'selected' : ''; ?>>
                                                    <?php echo $department->dep_name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>


                                    <!-- Designations Invited Dropdown -->
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="designations">Designations Invited</label>
                                        <select id="designations" name="designations[]" class="form-control custom-select" multiple required>
                                            <?php foreach ($designations as $designation): ?>
                                                <option value="<?php echo $designation->id; ?>" 
                                                    <?php echo (in_array($designation->id, $selected_designations)) ? 'selected' : ''; ?>>
                                                    <?php echo $designation->des_name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    </div>

                                </div>

                                <!-- Recurrence (if needed) -->
                                <div class="form-group">
                                    <label>Recurrence</label>
                                    <input type="text" name="recurrence" class="form-control" value="<?php echo isset($meeting_details['recurrence']) ? htmlspecialchars($meeting_details['recurrence']) : ''; ?>" placeholder="Enter recurrence details (if any)">
                                </div>

                                <!-- Meeting Notes -->
                                <div class="form-group">
                                    <label>Meeting Notes</label>
                                    <textarea name="meeting_notes" class="form-control" placeholder="Add any additional notes or comments" rows="3"></textarea>
                                </div>

                                <!-- Include jQuery --

                                <!-- Actions -->
                                <div class="form-actions">
                                    <div class="row">
                        <h4 class="m-b-0 text-white">Upload Document</h4>
                    </div> <div class="form-group">
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
                          
                                        <div class="col-md-6">
    <button type="button" class="btn btn-primary btn-block" onclick="showNotificationModal()">Create and Send Notifications</button>
</div>

<!-- Notification Modal -->


<script type="text/javascript">
    function showNotificationModal() {
        $('#notificationModal').modal('show');
    }

    $(document).ready(function () {
        $('#notificationForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Get form data
            var notificationType = $('#notificationType').val();
            var notificationDescription = $('#notificationDescription').val();

            // Here you can add your AJAX call to send the notification
            $.ajax({
                url: 'sendNotification', // Replace with your actual URL
                method: 'POST',
                data: {
                    type: notificationType,
                    description: notificationDescription
                },
                success: function (response) {
                    // Handle success response
                    alert('Notification sent successfully!');
                    $('#notificationModal').modal('hide'); // Close the modal
                    $('#notificationForm')[0].reset(); // Reset the form
                },
                error: function (error) {
                    // Handle error response
                    alert('Error sending notification. Please try again.');
                }
            });
        });
    });
</script>

                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-success btn-block">Save</button>
                                        </div>
                                        <div class="col-md-4">
                                        <button type="button" class="btn btn-danger" onclick="window.location.href='<?php echo site_url('meetings'); ?>'">Cancel</button>
                                        </div>
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

<?php foreach ($meetings as $meeting): ?>
    <div class="meeting-details">
        <h4>Meeting: <?php echo htmlspecialchars($meeting->meeting_title); ?></h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Attendee Name</th>
                    <th>RSVP Status</th>
                    <th>Attendance</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($meeting->attendees)): ?>
                    <?php foreach ($meeting->attendees as $attendee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($attendee->employee_name); ?></td>
                            <td><?php echo htmlspecialchars($attendee->rsvp_status); ?></td>
                            <td>
                                <input 
                                    type="checkbox" 
                                    name="attendance[<?php echo $attendee->id; ?>]" 
                                    <?php echo $attendee->attended ? 'checked' : ''; ?>
                                >
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No attendees available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="notificationModalLabel">Send Notifications</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="notificationForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="notificationType">Notification Type</label>
                        <select class="form-control" id="notificationType" name="notificationType" required>
                            <option value="">Select Notification Type</option>
                            <option value="Email">Email</option>
                            <option value="SMS">SMS</option>
                            <option value="Push Notification">Push Notification</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notificationDescription">Description</label>
                        <textarea class="form-control" id="notificationDescription" name="notificationDescription" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm and Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php endforeach; ?>
<?php $this->load->view('backend/footer'); ?>

<script>
    function uploadFiles() {
        // Implement file upload functionality here
        alert('Upload relevant files functionality to be implemented.');
    }

    function createAndSendNotifications() {
        // Implement notification creation and sending functionality here
        alert('Create and send notifications functionality to be implemented.');
    }

    function editMeeting() {
        // Implement edit functionality here
        alert('Edit functionality to be implemented.');
    }

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
    document.addEventListener("DOMContentLoaded", function () {
        const dateInput = document.querySelector('input[name="meeting_date"]');
        const today = new Date().toISOString().split('T')[0];
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <!-- Include Select2 JS -->
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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