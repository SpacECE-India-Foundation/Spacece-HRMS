<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-fax" style="color:#1976d2"> </i> Leave Report</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Leave Report</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Report List</h4>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="" id="salaryform" class="form-material row">
                                        <div class="form-group col-md-3">
                                            <input type="text" name="datetime" id="date_from" class="form-control mydatetimepicker" placeholder="from" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select class="select2 form-control custom-select col-md-8" data-placeholder="Choose a Category" tabindex="1" id="emid" name="emid" required>
                                                <option value="#">Select Here</option>
                                                <option value="all">All Employee</option>
                                                <?php foreach($employee as $value): ?>
                                                <option value="<?php echo $value->em_id ?>">
                                                    <?php echo $value->first_name.' '.$value->last_name; ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <input type="submit" class="btn btn-success" value="Submit" name="submit" id="BtnSubmit">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>PIN</th>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <!--<th>Total</th>-->
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>PIN </th>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <!--<th>Total</th>-->
                                    </tr>
                                </tfoot>
                                <tbody style="color:green" class="leave">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
          $(document).ready(function() {
    // Initialize the DataTable once
            var table = $('#example23').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                destroy: true // Allow reinitialization if needed
            });

            // Handle form submission
            $("#BtnSubmit").on("click", function(event) {
                event.preventDefault();

                var emid = $('#emid').val();
                var datetime = $('#date_from').val();

                console.log("Fetching leave details for:", emid, datetime);

                $.ajax({
                    url: "Get_LeaveDetails",
                    type: "GET",
                    data: {
                        date_time: datetime,
                        emp_id: emid,
                    },
                    dataType: "json",
                    success: function(response) {
                        // Clear existing data from the table
                        table.clear();

                        // Add new rows to the table
                        if (response.data.length > 0) {
                            $.each(response.data, function(index, row) {
                                table.row.add([
                                    row.em_code,
                                    row.employee_name,
                                    row.leave_type,
                                    row.leave_duration,
                                    row.start_date,
                                    row.end_date
                                ]);
                            });
                        } else {
                            console.log("No data found.");
                        }

                        // Redraw the table
                        table.draw();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching leave details:", error);
                    },
                });
            });
        });


        </script>
        <?php $this->load->view('backend/footer'); ?>
