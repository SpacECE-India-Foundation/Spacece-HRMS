<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
      <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-university" aria-hidden="true"></i> Employee</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Employee</li>
                    </ol>
                </div>
            </div>
            <div class="message"></div>
    <?php $degvalue = $this->employee_model->getdesignation(); ?>
    <?php $depvalue = $this->employee_model->getdepartment(); ?>
            <div class="container-fluid">
                <div class="row m-b-10"> 
                    <div class="col-12">
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>employee/Employees" class="text-white"><i class="" aria-hidden="true"></i>  Employee List</a></button>
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>employee/Disciplinary" class="text-white"><i class="" aria-hidden="true"></i>  Disciplinary List</a></button>
                    </div>
                </div>
               <div class="row">
                    <div class="col-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"><i class="fa fa-user-o" aria-hidden="true"></i> Add New Employee<span class="pull-right " ></span></h4>
                            </div>
                            <?php echo validation_errors(); ?>
                               <?php echo $this->upload->display_errors(); ?>
                               
                               <?php echo $this->session->flashdata('formdata'); ?>
                               <?php echo $this->session->flashdata('feedback'); ?>
                            <div class="card-body">
                            <form class="row" method="post" action="Save" enctype="multipart/form-data">
    <!-- First Row -->
    <div class="form-group col-md-3 m-t-20">
        <label>First Name</label>
        <input type="text" name="fname" class="form-control form-control-line" placeholder="Please enter your first name" minlength="2" >
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Last Name</label>
        <input type="text" name="lname" class="form-control form-control-line" placeholder="Please enter your last name" minlength="2" >
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Employee Code</label>
        <input type="text" name="eid" class="form-control form-control-line" placeholder="Please enter your ID" required>
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Department</label>
        <select name="dept" class="form-control custom-select" required>
            <option value="" selected disabled>Select Department</option>
            <?php foreach ($depvalue as $value): ?>
            <option value="<?php echo $value->id ?>"><?php echo $value->dep_name ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Second Row -->
    <div class="form-group col-md-3 m-t-20">
        <label>Blood Group</label>
        <select name="blood" class="form-control custom-select">
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
        </select>
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Designation</label>
        <select name="deg" class="form-control custom-select" required>
            <option value="" selected disabled>Select Designation</option>
            <?php foreach ($degvalue as $value): ?>
            <option value="<?php echo $value->id ?>"><?php echo $value->des_name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Role</label>
        <select name="role" class="form-control custom-select" required>
            <option value="" selected disabled>Select Role</option>
            <option value="ADMIN">ADMIN</option>
            <option value="EMPLOYEE">Employee</option>
            <option value="SUPER ADMIN">Super Admin</option>
        </select>
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Gender</label>
        <select name="gender" class="form-control custom-select" >
            <option value="MALE" <?= (isset($basic->em_gender) && $basic->em_gender == 'MALE') ? 'selected' : '' ?>>Male</option>
            <option value="FEMALE" <?= (isset($basic->em_gender) && $basic->em_gender == 'FEMALE') ? 'selected' : '' ?>>Female</option>
        </select>
    </div>

    <!-- Third Row -->
    <div class="form-group col-md-3 m-t-20">
        <label>NID</label>
        <input type="text" name="nid" class="form-control" placeholder="Please enter your NID" minlength="10">
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Contact Number</label>
        <input type="text" name="contact" class="form-control" placeholder="Please enter your contact number" minlength="10" maxlength="15" >
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Date Of Birth</label>
        <input type="date" name="dob" class="form-control">
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Date Of Joining</label>
        <input type="date" name="joindate" class="form-control" required>
    </div>

    <!-- Fourth Row -->
    <div class="form-group col-md-3 m-t-20">
        <label>Date Of Leaving</label>
        <input type="date" name="leavedate" class="form-control">
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Username</label>
        <input type="text" name="username" class="form-control form-control-line" placeholder="Please enter your username">
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Email</label>
        <input type="email" name="email" class="form-control" placeholder="Please enter your email" minlength="7" required>
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Password</label>
        <input type="text" name="password" placeholder="Please enter your password" class="form-control" required>
    </div>
    <div class="form-group col-md-3 m-t-20">
        <label>Image</label>
        <input type="file" name="image_url" class="form-control">
    </div>
    <!-- Submit Buttons -->
    <div class="form-actions col-md-12">
        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-secondary">Cancel</button>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
<?php $this->load->view('backend/footer'); ?>