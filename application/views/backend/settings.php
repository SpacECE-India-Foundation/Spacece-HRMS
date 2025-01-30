<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Settings</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </div>
    </div>
    <?php echo validation_errors(); ?>
    <?php echo $this->upload->display_errors(); ?>
    <?php echo $this->session->flashdata('formdata'); ?>
    <?php echo $this->session->flashdata('feedback'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="table_body">
                            <form action="Add_Settings" id="fileUploadForm" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                                <div class="form-group clearfix">
                                    <label for="" class="col-md-3">Upload site logo</label>
                                    <div class="col-md-9">
                                        <div class="file_prev inb">
                                            <?php if($settingsvalue->sitelogo){ ?>
                                                <img src="<?php echo base_url(); ?>assets/images/<?php echo $settingsvalue->sitelogo; ?>" height="100" width="167">
                                            <?php } else { ?>
                                                <img src="<?php echo base_url(); ?>assets/img/ci-logo.png" height="100" width="167">
                                            <?php } ?>
                                        </div>
                                        <label for="img_url" class="custom-file-upload"><i class="fa fa-camera" aria-hidden="true"></i> Upload Logo</label>
                                        <input type="file" value="" class="" id="img_url" name="img_url" aria-describedby="fileHelp">
                                    </div>
                                </div>
                                <!-- Second site logo upload -->
                                
                                <div class="form-group clearfix">
                                    <label for="title" class="col-md-3">Site Title</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="title" value="<?php echo $settingsvalue->sitetitle; ?>" id="title" placeholder="Title..." required minlength="5" maxlength="120">
                                        <small id="titleError" class="text-danger"></small>
                                    </div>
                                </div>                                  
                                <div class="form-group clearfix">
                                    <label for="description" class="col-md-3">Description</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="description" name="description" rows="6" required minlength="20" maxlength="512"><?php echo $settingsvalue->description; ?></textarea>
                                        <small id="descriptionError" class="text-danger"></small>
                                    </div>                                      
                                </div>                                                                
                                                               
                                <div class="form-group clearfix">
                                <label for="contact" class="col-md-3">Contact</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="contact" value="<?php echo $settingsvalue->contact; ?>" id="contact" placeholder="contact..." required>
                                    <span id="contactError" class="text-danger"></span>
                                </div>
                            </div>

                       

                          
                                <div class="form-group clearfix">
                                    <label for="email" class="col-md-3">System Email</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="email" id="email" value="<?php echo $settingsvalue->system_email; ?>" placeholder="email..." required>
                                        <small id="emailError" class="text-danger"></small>
                                    </div>
                                </div>                                  
                                <div class="form-group clearfix">
                                    <label for="address" class="col-md-3">Address</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="address" value="<?php echo $settingsvalue->address; ?>" id="address" placeholder="address..." required>
                                        <small id="addressError" class="text-danger"></small>
                                    </div>
                                </div>                                  
                                
                                <div class="form-group clearfix">
                                    <div class="col-md-9 col-md-offset-3">
                                        <input type="hidden" name="id" value="<?php echo $settingsvalue->id; ?>"/>
                                        <button type="submit" name="submit" id="btnSubmit" class="btn btn-custom">Submit</button>
                                        <span class="flashmessage"><?php echo $this->session->flashdata('feedback'); ?></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
.error {
    color: red;
    font-size: 14px;
    margin-top: 5px;
}
.input-error {
    border-color: red;
}
</style>
        <script>
            $(document).ready(function() {
    // Real-time validation for Title
    // Real-time validation for Title
$('#title').on('input', function() {
    var title = $(this).val();
    var alphaPattern = /[a-zA-Z]/;  // Check for at least one alphabet
    if (!title) {
        $(this).addClass('input-error'); // Add the error class (red border)
        console.log("Title error: Adding red border");
    } 
    if (title.length > 0 && (!alphaPattern.test(title))) {
        $('#titleError').text('Title contain at least one alphabet.');
        $(this).addClass('input-error'); // Add the error class (red border)
    } else {
        $('#titleError').text('');
        $(this).removeClass('input-error'); // Remove the error class (remove red border)
        console.log("Title valid: Removing red border");
    }
});


    // Real-time validation for Description
    // Real-time validation for Description
    $('#description').on('input', function() {
    var description = $(this).val();
    var alphaPattern = /[a-zA-Z]/;  // Check for at least one alphabet

    // Check if the description is empty
    if (!description) {
        // $('#descriptionError').text('');
        $(this).addClass('input-error'); // Add the error class (red border)
        console.log("Description error: Empty input");
    } 
    // Check if the description fails other conditions (length and alphabet check)
    else if (!alphaPattern.test(description)) {
        $('#descriptionError').text('Description must contain at least one alphabet.');
        $(this).addClass('input-error'); // Add the error class (red border)
        console.log("Description error: Invalid length or no alphabet");
    } else {
        // Clear error message and remove red border when valid
        $('#descriptionError').text('');
        $(this).removeClass('input-error'); // Remove the error class (remove red border)
        console.log("Description valid: Removing red border");
    }
});

    
// Contact validation
$('#contact').on('input', function() {
    var contact = $(this).val();
    if (!contact ) {
        $(this).addClass('input-error'); // Add red border
    } else {
        $('#contactError').text('');
        $(this).removeClass('input-error'); // Remove red border
    }
});

// Currency validation
$('#currency').on('input', function() {
    var currency = $(this).val();
    if (!currency || currency.length < 3) {
        $(this).addClass('input-error'); // Add red border
    } else {
        $('#currencyError').text('');
        $(this).removeClass('input-error'); // Remove red border
    }
});

// Symbol validation
$('#symbol').on('input', function() {
    var symbol = $(this).val();
    if (!symbol || symbol.length < 1) {
        $(this).addClass('input-error'); // Add red border
    } else {
        $('#symbolError').text('');
        $(this).removeClass('input-error'); // Remove red border
    }
});

    // Real-time validation for Email
    $('#email').on('input', function() {
    var email = $(this).val();
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    // Check if email is empty
    if (!email) {
        // $('#emailError').text('Email is required.');
        $(this).addClass('input-error'); // Add the error class (red border)
        console.log("Email error: Empty input");
    } 
    // Check if email matches the valid email pattern
    else if (!emailPattern.test(email)) {
        $('#emailError').text('Please enter a valid email address.');
        $(this).addClass('input-error'); // Add the error class (red border)
        console.log("Email error: Invalid email format");
    } else {
        // Clear the error message and remove the red border when valid
        $('#emailError').text('');
        $(this).removeClass('input-error'); // Remove the error class (remove red border)
        console.log("Email valid: Removing red border");
    }
});


    // Real-time validation for Address
    $('#address').on('input', function() {
    var address = $(this).val();

    // Check if address is empty or too short
    if (!address || symbol.length < 1) {
        $(this).addClass('input-error'); // Add the error class (red border)
        console.log("Address error: Empty input");
    } 
    // Check if address length is less than 5 characters
    else {
        // Clear the error message and remove the red border when valid
        $('#addressError').text('');
        $(this).removeClass('input-error'); // Remove the error class (remove red border)
        console.log("Address valid: Removing red border");
    }
});

    // Form submission
    $('#fileUploadForm').submit(function(event) {
        var valid = true;
        var errorMessage = '';

        // Validate inputs when submitting
        $('#title, #description, #email, #address, #symbol').each(function() {
            if ($(this).next('.text-danger').text() !== '') {
                valid = false;
                errorMessage += 'There are errors in the form. Please correct them.\n';
                return false; // Stop the loop if there is an error
            }
        });

        if (!valid) {
            event.preventDefault();  // Prevent the form from submitting
            alert(errorMessage);
        }
    });
});

        </script>
    <?php $this->load->view('backend/footer'); ?> 
