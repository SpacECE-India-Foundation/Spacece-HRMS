<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
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
        <div class="card">
            <div class="card-header">
                <h4 class="text-white">Assign Course</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo base_url('employee/create_course_assignment'); ?>" id="courseAssignmentForm">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="department">Department</label>
                            <select class="form-control" name="department" id="department" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo $department['dep_name']; ?>"><?php echo $department['dep_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="date" id="date" required>
                        </div>

                        <div class="col-md-4">
                            <label for="nature">Nature</label>
                            <select class="form-control" name="nature" id="nature" required>
                                <option value="">Select Nature</option>
                                <option value="mandatory">Mandatory</option>
                                <option value="not_mandatory">Not Mandatory</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="course">Course</label>
                        <select class="form-control" name="course" id="course" required>
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course->id; ?>"><?php echo $course->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Assign</button>
                </form>
            </div>
        </div>

        <div class="mt-5">
            <h4>Filter Courses</h4>
            <div class="row">
                <div class="col-md-3">
                    <label for="filterDepartment">Department</label>
                    <input type="text" id="filterDepartment" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="filterDate">Date</label>
                    <input type="date" id="filterDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="filterNature">Nature</label>
                    <input type="text" id="filterNature" class="form-control">
                </div>
                <div class="col-md-3 mt-4">
                    <button id="filterButton" class="btn btn-info">Filter</button>
                </div>
            </div>
        </div>

        <div id="filteredCoursesTable" class="mt-5"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#filterButton').click(function () {
            const department = $('#filterDepartment').val();
            const date = $('#filterDate').val();
            const nature = $('#filterNature').val();

            $.post("<?php echo base_url('employee/filter_courses'); ?>", { department, date, nature }, function (response) {
                const data = JSON.parse(response);
                let table = '<table class="table"><thead><tr><th>Title</th><th>Department</th><th>Date</th><th>Nature</th></tr></thead><tbody>';

                data.courses.forEach(course => {
                    table += `<tr><td>${course.title}</td><td>${course.department}</td><td>${course.date}</td><td>${course.nature}</td></tr>`;
                });

                table += '</tbody></table>';
                $('#filteredCoursesTable').html(table);
            });
        });
    });
</script>

<?php $this->load->view('backend/footer'); ?>
