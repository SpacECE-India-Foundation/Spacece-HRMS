<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('dashboard_model');
        $this->load->model('employee_model');
        $this->load->model('login_model');
        $this->load->model('payroll_model');
        $this->load->model('settings_model');
        $this->load->model('leave_model');
        $this->load->model('course_model');
    }
    
	public function index()
	{
		if ($this->session->userdata('user_login_access') != 1)
            redirect(base_url() . 'login', 'refresh');
        if ($this->session->userdata('user_login_access') == 1)
          $data= array();
        redirect('employee/Employees');
	}
    public function Employees(){
        if($this->session->userdata('user_login_access') != False) { 
        $data['employee'] = $this->employee_model->emselect();
        $this->load->view('backend/employees',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Add_employee(){
        if($this->session->userdata('user_login_access') != False) { 
        $this->load->view('backend/add-employee');
        }
    else{
		redirect(base_url() , 'refresh');
	}            
    }
	public function Save(){ 
        if($this->session->userdata('user_login_access') != False) {     
        $eid = $this->input->post('eid');    
        $id = $this->input->post('emid');    
        $fname = $this->input->post('fname');
        $lname = $this->input->post('lname');
        $emrand = substr($lname,0,3).rand(1000,2000);    
        $dept = $this->input->post('dept');
        $deg = $this->input->post('deg');
        $role = $this->input->post('role');
        $gender = $this->input->post('gender');
        $contact = $this->input->post('contact');
        $dob = $this->input->post('dob');	
        $joindate = $this->input->post('joindate');	
        $leavedate = $this->input->post('leavedate');	
        $username = $this->input->post('username');	
        $email = $this->input->post('email');	
        $password = sha1($contact);	
        $confirm = $this->input->post('confirm');	
        $nid = $this->input->post('nid');		
        $blood = $this->input->post('blood');		
        $address = $this->input->post('address');		
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters();
            // Validating Name Field
            $this->form_validation->set_rules('contact', 'contact', 'trim|required|min_length[10]|max_length[15]|xss_clean');
            /*validating email field*/
            $this->form_validation->set_rules('email', 'Email','trim|required|min_length[7]|max_length[100]|xss_clean');
    
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                } else {
                if($this->employee_model->Does_email_exists($email) && $password != $confirm){
                    $this->session->set_flashdata('formdata','Email is already Exist or Check your password');
                    echo "Email is already Exist or Check your password";
                } else {
                if($_FILES['image_url']['name']){
                $file_name = $_FILES['image_url']['name'];
                $fileSize = $_FILES["image_url"]["size"]/1024;
                $fileType = $_FILES["image_url"]["type"];
                $new_file_name='';
                $new_file_name .= $emrand;
    
                $config = array(
                    'file_name' => $new_file_name,
                    'upload_path' => "./assets/images/users",
                    'allowed_types' => "gif|jpg|png|jpeg",
                    'overwrite' => False,
                    'max_size' => "20240000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "800",
                    'max_width' => "800"
                );
        
                $this->load->library('Upload', $config);
                $this->upload->initialize($config);                
                if (!$this->upload->do_upload('image_url')) {
                    echo $this->upload->display_errors();
                }
       
                else {
                    $path = $this->upload->data();
                    $img_url = $path['file_name'];
                    $data = array();
                    $data = array(
                        'em_id' => $emrand,
                        'em_code' => $eid,
                        'des_id' => $deg,
                        'dep_id' => $dept,
                        'first_name' => $fname,
                        'last_name' => $lname,
                        'em_email' => $email,
                        'em_password'=>$password,
                        'em_role'=>$role,
                        'em_gender'=>$gender,
                        'status'=>'ACTIVE',
                        'em_phone'=>$contact,
                        'em_birthday'=>$dob,
                        'em_joining_date'=>$joindate,
                        'em_contact_end'=>$leavedate,
                        'em_image'=>$img_url,
                        'em_nid'=>$nid,
                        'em_blood_group'=> $blood
                    );
                    if($id){
                $success = $this->employee_model->Update($data,$id); 
                #$this->session->set_flashdata('feedback','Successfully Updated');
                echo "Successfully Updated";
                    } else {
                $success = $this->employee_model->Add($data);
                #$this->confirm_mail_send($email,$pass_hash);        
                #$this->session->set_flashdata('feedback','Successfully Created');
                echo "Successfully Added";                     
                    }
                }
            } else {
                    $data = array();
                    $data = array(
                        'em_id' => $emrand,
                        'em_code' => $eid,
                        'des_id' => $deg,
                        'dep_id' => $dept,
                        'first_name' => $fname,
                        'last_name' => $lname,
                        'em_email' => $email,
                        'em_password'=>$password,
                        'em_role'=>$role,
                        'em_gender'=>$gender,
                        'status'=>'ACTIVE',
                        'em_phone'=>$contact,
                        'em_birthday'=>$dob,
                        'em_joining_date'=>$joindate,
                        'em_contact_end'=>$leavedate,
                        'em_address'=>$address,
                        'em_nid'=>$nid,
                        'em_blood_group'=> $blood
                    );
                    if($id){
                $success = $this->employee_model->Update($data,$id); 
                #$this->session->set_flashdata('feedback','Successfully Updated');
                echo "Successfully Updated";        
                #redirect('employee/Add_employee'); 
                    } else {
                $success = $this->employee_model->Add($data);
                #$this->confirm_mail_send($email,$pass_hash);        
                echo "Successfully Added";
                #redirect('employee/Add_employee');                     
                    }
                }
                }
            }
            }
        else{
            redirect(base_url() , 'refresh');
               }        
            }
            public function Update() {
                if ($this->session->userdata('user_login_access') != False) {
                    $eid = $this->input->post('eid');
                    $id = $this->input->post('emid');
                    $fname = $this->input->post('fname');
                    $lname = $this->input->post('lname');
                    $dept = $this->input->post('dept');
                    $deg = $this->input->post('deg');
                    $role = $this->input->post('role');
                    $gender = $this->input->post('gender');
                    $contact = $this->input->post('contact');
                    $dob = $this->input->post('dob');
                    $joindate = $this->input->post('joindate');
                    $leavedate = $this->input->post('leavedate');
                    $username = $this->input->post('username');
                    $email = $this->input->post('email');
                    $password = $this->input->post('password');
                    $address = $this->input->post('address');
                    $nid = $this->input->post('nid');
                    $status = $this->input->post('status');
                    $blood = $this->input->post('blood');
            
                    $this->load->library('form_validation');
                    $this->form_validation->set_error_delimiters();
                    $this->form_validation->set_rules('contact', 'contact', 'trim|required|min_length[10]|max_length[15]|xss_clean');
                    $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[7]|max_length[100]|xss_clean');
            
                    if ($this->form_validation->run() == FALSE) {
                        echo validation_errors();
                    } else {
                        $data = array(
                            'em_code' => $eid,
                            'des_id' => $deg,
                            'dep_id' => $dept,
                            'first_name' => $fname,
                            'last_name' => $lname,
                            'em_email' => $email,
                            'em_role' => $role,
                            'em_gender' => $gender,
                            'status' => $status,
                            'em_phone' => $contact,
                            'em_birthday' => $dob,
                            'em_joining_date' => $joindate,
                            'em_contact_end' => $leavedate,
                            'em_address' => $address,
                            'em_nid' => $nid,
                            'em_blood_group' => $blood
                        );
            
                        // Check if a new password is provided
                        if (!empty($password)) {
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
                            $data['em_password'] = $hashedPassword; // Add password to the update array
                        }
            
                        // Image upload logic
                        if ($_FILES['image_url']['name']) {
                            $file_name = $_FILES['image_url']['name'];
                            $fileSize = $_FILES["image_url"]["size"] / 1024;
                            $fileType = $_FILES["image_url"]["type"];
                            $new_file_name = $id;
            
                            $config = array(
                                'file_name' => $new_file_name,
                                'upload_path' => "./assets/images/users",
                                'allowed_types' => "gif|jpg|png|jpeg",
                                'overwrite' => False,
                                'max_size' => "20240000",
                                'max_height' => "600",
                                'max_width' => "600"
                            );
            
                            $this->load->library('Upload', $config);
                            $this->upload->initialize($config);
            
                            if (!$this->upload->do_upload('image_url')) {
                                echo $this->upload->display_errors();
                            } else {
                                $employee = $this->employee_model->GetBasic($id);
                                $checkimage = "./assets/images/users/$employee->em_image";
                                if (file_exists($checkimage)) {
                                    unlink($checkimage);
                                }
                                $path = $this->upload->data();
                                $data['em_image'] = $path['file_name'];
                            }
                        }
            
                        // Update the database
                        if ($id) {
                            $success = $this->employee_model->Update($data, $id);
                            $this->session->set_flashdata('feedback', 'Successfully Updated');
                            echo "Successfully Updated";
                        }
                    }
                } else {
                    redirect(base_url(), 'refresh');
                }
            }
            
    public function view(){
        if($this->session->userdata('user_login_access') != False) {
        $id = base64_decode($this->input->get('I'));
        $data['basic'] = $this->employee_model->GetBasic($id);
        $data['permanent'] = $this->employee_model->GetperAddress($id);
        $data['present'] = $this->employee_model->GetpreAddress($id);
        $data['education'] = $this->employee_model->GetEducation($id);
        $data['experience'] = $this->employee_model->GetExperience($id);
        $data['bankinfo'] = $this->employee_model->GetBankInfo($id);
        $data['fileinfo'] = $this->employee_model->GetFileInfo($id);
        $data['typevalue'] = $this->payroll_model->GetsalaryType();
        $data['leavetypes'] = $this->leave_model->GetleavetypeInfo();    
        $data['salaryvalue'] = $this->employee_model->GetsalaryValue($id);
        $data['socialmedia'] = $this->employee_model->GetSocialValue($id);
            $year = date('Y');
        $data['Leaveinfo'] = $this->employee_model->GetLeaveiNfo($id,$year);
        $this->load->view('backend/employee_view',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}         
    }
    public function deleteEmployee($emp_id) {
        // Check if the user is logged in
        if($this->session->userdata('user_login_access') != False) {
            // Call the delete function in the model to delete the employee
            $result = $this->employee_model->deleteEmployee($emp_id);
            
            // Redirect back to the employee list with a success or failure message
            if($result) {
                $this->session->set_flashdata('message', 'Employee deleted successfully.');
            } else {
                $this->session->set_flashdata('message', 'Failed to delete employee.');
            }
            redirect('employee'); // Redirect back to the employee list page
        } else {
            redirect(base_url(), 'refresh'); // Redirect to login page if not logged in
        }
    }
    
    public function Parmanent_Address(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('id');
        $em_id = $this->input->post('emid');
        $paraddress = $this->input->post('paraddress');
        $parcity = $this->input->post('parcity');
        $parcountry = $this->input->post('parcountry');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('paraddress', 'address', 'trim|required|min_length[5]|max_length[100]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			#redirect("employee/view?I=" .base64_encode($em_id));
			} else {
            $data = array();
                $data = array(
                    'emp_id' => $em_id,
                    'city' => $parcity,
                    'country' => $parcountry,
                    'address' => $paraddress,
                    'type' => 'Permanent'
                );
            if(!empty($id)){
                $success = $this->employee_model->UpdateParmanent_Address($id,$data);
                $this->session->set_flashdata('feedback','Successfully Updated');
                echo "Successfully Updated";                
            } else {
                $success = $this->employee_model->AddParmanent_Address($data);
                $this->session->set_flashdata('feedback','Successfully Added');
                echo "Successfully Added";
            }
                       
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}             
    }
    public function Present_Address(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('id');
        $em_id = $this->input->post('emid');
        $presaddress = $this->input->post('presaddress');
        $prescity = $this->input->post('prescity');
        $prescountry = $this->input->post('prescountry');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('presaddress', 'address', 'trim|required|min_length[5]|max_length[100]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			#redirect("employee/view?I=" .base64_encode($em_id));
			} else {
            $data = array();
                $data = array(
                    'emp_id' => $em_id,
                    'city' => $prescity,
                    'country' => $prescountry,
                    'address' => $presaddress,
                    'type' => 'Present'
                );
            if(empty($id)){
                $success = $this->employee_model->AddParmanent_Address($data);
                $this->session->set_flashdata('feedback','Successfully Added');
                echo "Successfully Updated";
            } else {
                $success = $this->employee_model->UpdateParmanent_Address($id,$data);
                $this->session->set_flashdata('feedback','Successfully Updated');
                echo "Successfully Added";
            }
                       
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Add_Education(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('id');
        $em_id = $this->input->post('emid');
        $certificate = $this->input->post('name');
        $institute = $this->input->post('institute');
        $eduresult = $this->input->post('result');
        $eduyear = $this->input->post('year');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('name', 'name', 'trim|required|min_length[2]|max_length[150]|xss_clean');
        $this->form_validation->set_rules('institute', 'institute', 'trim|required|min_length[5]|max_length[250]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			#redirect("employee/view?I=" .base64_encode($em_id));
			} else {
            $data = array();
                $data = array(
                    'emp_id' => $em_id,
                    'edu_type' => $certificate,
                    'institute' => $institute,
                    'result' => $eduresult,
                    'year' => $eduyear
                );
            if(empty($id)){
                $success = $this->employee_model->Add_education($data);
                $this->session->set_flashdata('feedback','Successfully Added');
                echo "Successfully Added";
            } else {
                $success = $this->employee_model->Update_Education($id,$data);
                #$this->session->set_flashdata('feedback','Successfully Updated');
                echo "Successfully Updated";
            }
                       
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}            
    }
    public function Save_Social(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('id');
        $em_id = $this->input->post('emid');
        $facebook = $this->input->post('facebook');
        $twitter = $this->input->post('twitter');
        $google = $this->input->post('google');
        $skype = $this->input->post('skype');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('facebook', 'company_name', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			} else {
            $data = array();
                $data = array(
                    'emp_id' => $em_id,
                    'facebook' => $facebook,
                    'twitter' => $twitter,
                    'google_plus' => $google,
                    'skype_id' => $skype
                );
            if(empty($id)){
                $success = $this->employee_model->Insert_Media($data);
                echo "Successfully Added";
            } else {
                $success = $this->employee_model->Update_Media($id,$data);
                echo "Successfully Updated";
            }
                       
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Add_Experience(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('id');
        $em_id = $this->input->post('emid');
        $company = $this->input->post('company_name');
        $position = $this->input->post('position_name');
        $address = $this->input->post('address');
        $start = $this->input->post('work_duration');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('company_name', 'company_name', 'trim|required|min_length[5]|max_length[150]|xss_clean');
        $this->form_validation->set_rules('position_name', 'position_name', 'trim|required|min_length[5]|max_length[250]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			#redirect("employee/view?I=" .base64_encode($em_id));
			} else {
            $data = array();
                $data = array(
                    'emp_id' => $em_id,
                    'exp_company' => $company,
                    'exp_com_position' => $position,
                    'exp_com_address' => $address,
                    'exp_workduration' => $start
                );
            if(empty($id)){
                $success = $this->employee_model->Add_Experience($data);
                $this->session->set_flashdata('feedback','Successfully Added');
                echo "Successfully Updated";
            } else {
                $success = $this->employee_model->Update_Experience($id,$data);
                #$this->session->set_flashdata('feedback','Successfully Updated');
                echo "Successfully Updated";
            }
                       
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Disciplinary(){
        if($this->session->userdata('user_login_access') != False) {
        $data['desciplinary'] = $this->employee_model->desciplinaryfetch();
        $this->load->view('backend/disciplinary',$data); 
        }
    else{
		redirect(base_url() , 'refresh');
	}            
    }
    public function add_Desciplinary(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('id');
        $em_id = $this->input->post('emid');
        $warning = $this->input->post('warning');
        $title = $this->input->post('title');
        $details = $this->input->post('details');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('title', 'title', 'trim|required|min_length[5]|max_length[150]|xss_clean');
        $this->form_validation->set_rules('details', 'details', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			#redirect('Disciplinary');
			} else {
            $data = array();
                $data = array(
                    'em_id' => $em_id,
                    'action' => $warning,
                    'title' => $title,
                    'description' => $details
                );
            if(empty($id)){
                $success = $this->employee_model->Add_Desciplinary($data);
                $this->session->set_flashdata('feedback','Successfully Added');
                #redirect('employee/Disciplinary');
                echo "Successfully Added";
            } else {
                $success = $this->employee_model->Update_Desciplinary($id,$data);
                #$this->session->set_flashdata('feedback','Successfully Updated');
                #redirect("employee/view?I=" .base64_encode($em_id));
                echo "Successfully Updated";
            }
                       
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Add_bank_info(){
        if($this->session->userdata('user_login_access') != FALSE) {
            $id = $this->input->post('id');
            $em_id = $this->input->post('emid');
            $holder = $this->input->post('holder_name');
            $bank = $this->input->post('bank_name');
            $branch = $this->input->post('branch_name');
            $number = $this->input->post('account_number');
            $account = $this->input->post('account_type');
            
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            
            // Set validation rules for each field
            $this->form_validation->set_rules('holder_name', 'Holder Name', 'trim|required|max_length[120]|xss_clean|callback_alpha_spaces');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required|max_length[120]|xss_clean|callback_alpha_spaces');
        
        // Other validation rules
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required|max_length[120]|xss_clean|callback_alpha_spaces');
        $this->form_validation->set_rules('account_number', 'Account Number', 'trim|required|max_length[20]|xss_clean|numeric');
        $this->form_validation->set_rules('account_type', 'Account Type', 'required|in_list[Savings,Current]');
        
            if ($this->form_validation->run() == FALSE) {
                // Validation failed, echo errors and redirect back
                echo validation_errors();
            } else {
                // Prepare data array
                $data = array(
                    'em_id' => $em_id,
                    'holder_name' => $holder,
                    'bank_name' => $bank,
                    'branch_name' => $branch,
                    'account_number' => $number,
                    'account_type' => $account
                );
                
                // Insert or update based on whether $id is empty
                if(empty($id)){
                    $success = $this->employee_model->Add_BankInfo($data);
                    echo "Successfully Added";
                } else {
                    $success = $this->employee_model->Update_BankInfo($id, $data);
                    echo "Successfully Updated";
                }
            }
        } else {
            // Redirect if not logged in
            redirect(base_url(), 'refresh');
        }
    }
    public function alpha_spaces($str)
{
    if (!preg_match("/^[A-Za-z ]*$/", $str)) {
        $this->form_validation->set_message('alpha_spaces', 'The {field} field can only contain alphabetic characters.');
        return FALSE;
    }
    return TRUE;
}
    
    public function Reset_Password_Hr(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('emid');
        $onep = $this->input->post('new1');
        $twop = $this->input->post('new2');
        $currentPassword = $this->employee_model->getCurrentPassword($id);
        
            if($onep == $twop && sha1($onep) !== $currentPassword ){
                $data = array();
                $data = array(
                    'em_password'=> sha1($onep)
                );
        $success = $this->employee_model->Reset_Password($id,$data);
        #$this->session->set_flashdata('feedback','Successfully Updated');
        #redirect("employee/view?I=" .base64_encode($id));
                echo "Successfully Updated";
            } else if (sha1($onep) === $currentPassword){
        $this->session->set_flashdata('feedback','New password cannot be the same as the old password.');
        #redirect("employee/view?I=" .base64_encode($id)); 
                echo "New password cannot be the same as the old password.";
            }
            else{
                $this->session->set_flashdata('feedback','Passwords do not match.');
        #redirect("employee/view?I=" .base64_encode($id)); 
                echo "Passwords do not match.";
            }

        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Reset_Password(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('emid');
        $oldp = sha1($this->input->post('old'));
        $onep = $this->input->post('new1');
        $twop = $this->input->post('new2');
        $pass = $this->employee_model->GetEmployeeId($id);
        if($pass->em_password == $oldp){
            if($onep == $twop){
                $data = array();
                $data = array(
                    'em_password'=> sha1($onep)
                );
        $success = $this->employee_model->Reset_Password($id,$data);
        $this->session->set_flashdata('feedback','Successfully Updated');
        #redirect("employee/view?I=" .base64_encode($id));
                echo "Successfully Updated";
            } else {
        $this->session->set_flashdata('feedback','Please enter valid password');
        #redirect("employee/view?I=" .base64_encode($id));
                echo "Please enter valid password";
            }
        } else {
            $this->session->set_flashdata('feedback','Please enter valid password');
            #redirect("employee/view?I=" .base64_encode($id));
            echo "Please enter valid password";
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Department(){
        if($this->session->userdata('user_login_access') != False) {
        $data['department'] = $this->employee_model->depselect();
        $this->load->view('backend/department',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}            
    }
    public function Save_dep(){
        if($this->session->userdata('user_login_access') != False) {
       $dep = $this->input->post('department');
       $this->load->library('form_validation');
       $this->form_validation->set_error_delimiters();
       $this->form_validation->set_rules('department','department','trim|required|xss_clean');

       if ($this->form_validation->run() == FALSE) {
           echo validation_errors();
           redirect('employee/Department');
       }else{
        $data = array();
        $data = array('dep_name' => $dep);
        $success = $this->employee_model->Add_Department($data);
        #$this->session->set_flashdata('feedback','Successfully Added');
        #redirect('employee/Department');
       }
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Designation(){
        if($this->session->userdata('user_login_access') != False) {
        $data['designation'] = $this->employee_model->desselect();
        $this->load->view('backend/designation',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}            
    }
    public function Des_Save(){
        if($this->session->userdata('user_login_access') != False) {
        $des = $this->input->post('designation');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('designation','designation','trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
            redirect('employee/Designation');
        }else{
            $data = array();
            $data = array('des_name' => $des);
            $success = $this->employee_model->Add_Designation($data);
            $this->session->set_flashdata('feedback','Successfully Added');
            redirect('employee/Designation');
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}
    }
    public function Assign_leave(){
        if($this->session->userdata('user_login_access') != False) {
            $emid = $this->input->post('em_id');
            $type = $this->input->post('typeid');
            $day = (string) $this->input->post('noday'); // Ensure it's a string
            $year = (int) $this->input->post('year'); // Convert to integer for the year
    
            // Log the data to check what is being posted
            log_message('debug', 'Form Data: ' . print_r($this->input->post(), true));
    
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters();
            $this->form_validation->set_rules('typeid','typeid','trim|required|xss_clean');
    
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $data = array(
                    'emp_id' => $emid,
                    'type_id' => $type,
                    'day' => $day,
                    'total_day' => '0', // Assuming '0' is a placeholder for total_day
                    'dateyear' => $year
                );
                
                // Log data before insertion
                log_message('debug', 'Data to Insert: ' . print_r($data, true));
    
                $success = $this->employee_model->Add_Assign_Leave($data);
                if ($success) {
                    echo "Successfully Added";
                } else {
                    echo "Failed to add leave";
                }
            }
        } else {
            // redirect(base_url(), 'refresh');
        }
    }
    
    public function Add_File(){
    if($this->session->userdata('user_login_access') != False) { 
    $em_id = $this->input->post('em_id');    		
    $filetitle = $this->input->post('title');    		
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('title', 'title', 'trim|required|min_length[10]|max_length[120]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			
			} else {
            if($_FILES['file_url']['name']){
            $file_name = $_FILES['file_url']['name'];
			$fileSize = $_FILES["file_url"]["size"]/1024;
			$fileType = $_FILES["file_url"]["type"];
			$new_file_name='';
            $new_file_name .= $file_name;

            $config = array(
                'file_name' => $new_file_name,
                'upload_path' => "./assets/images/users",
                'allowed_types' => "gif|jpg|png|jpeg|pdf|doc|docx|xml|text|txt",
                'overwrite' => False,
                'max_size' => "40480000"
            );
    
            $this->load->library('Upload', $config);
            $this->upload->initialize($config);                
            if (!$this->upload->do_upload('file_url')) {
                echo $this->upload->display_errors();
                #redirect("employee/view?I=" .base64_encode($em_id));
			}
   
			else {
                $path = $this->upload->data();
                $img_url = $path['file_name'];
                $data = array();
                $data = array(
                    'em_id' => $em_id,
                    'file_title' => $filetitle,
                    'file_url' => $img_url
                );
            $success = $this->employee_model->File_Upload($data); 
            #$this->session->set_flashdata('feedback','Successfully Updated');
            #redirect("employee/view?I=" .base64_encode($em_id));
                echo "Successfully Updated";
			}
        }
            
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function educationbyib(){
        if($this->session->userdata('user_login_access') != False) {  
		$id= $this->input->get('id');
		$data['educationvalue'] = $this->employee_model->GetEduValue($id);
		echo json_encode($data);
        }
    else{
		redirect(base_url() , 'refresh');
	} 
        
    }
    public function experiencebyib(){
        if($this->session->userdata('user_login_access') != False) {  
		$id= $this->input->get('id');
		$data['expvalue'] = $this->employee_model->GetExpValue($id);
		echo json_encode($data);
        }
    else{
		redirect(base_url() , 'refresh');
	} 
        
    }
    public function DisiplinaryByID(){
        if($this->session->userdata('user_login_access') != False) {  
		$id= $this->input->get('id');
		$data['desipplinary'] = $this->employee_model->GetDesValue($id);
		echo json_encode($data);
        }
    else{
		redirect(base_url() , 'refresh');
	} 
        
    }
    public function EduvalueDelet(){
        if($this->session->userdata('user_login_access') != False) {  
		$id= $this->input->get('id');
		$success = $this->employee_model->DeletEdu($id);
		echo "Successfully Deletd";
        }
    else{
		redirect(base_url() , 'refresh');
	} 
    }
    public function EXPvalueDelet(){
        if($this->session->userdata('user_login_access') != False) {  
		$id= $this->input->get('id');
		$success = $this->employee_model->DeletEXP($id);
		echo "Successfully Deletd";
        }
    else{
		redirect(base_url() , 'refresh');
	} 
    }
    public function DeletDisiplinary(){
        if($this->session->userdata('user_login_access') != False) {  
		$id= $this->input->get('D');
		$success = $this->employee_model->DeletDisiplinary($id);
		#echo "Successfully Deletd";
            redirect('employee/Disciplinary');
        }
    else{
		redirect(base_url() , 'refresh');
	} 
    }
    public function Add_Salary(){
        if($this->session->userdata('user_login_access') != False) { 
        $sid = $this->input->post('sid');
        $aid = $this->input->post('aid');
        $did = $this->input->post('did');
        $em_id = $this->input->post('emid');
        $type = $this->input->post('typeid');
        $total = $this->input->post('total');
        $basic = $this->input->post('basic');
        $medical = $this->input->post('medical');
        $houserent = $this->input->post('houserent');
        $conveyance = $this->input->post('conveyance');
        $provident = $this->input->post('provident');
        $bima = $this->input->post('bima');
        $tax = $this->input->post('tax');
        $others = $this->input->post('others');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('total', 'total', 'trim|required|min_length[3]|max_length[10]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			#redirect("employee/view?I=" .base64_encode($em_id));
			} else {
            $data = array();
                $data = array(
                    'emp_id' => $em_id,
                    'type_id' => $type,
                    'total' => $total
                );
            if(!empty($sid)){
                $success = $this->employee_model->Update_Salary($sid,$data);
                #$this->session->set_flashdata('feedback','Successfully Updated');
                #echo "Successfully Updated";
                #$success = $this->employee_model->Add_Salary($data);
                #$insertId = $this->db->insert_id();
                #$this->session->set_flashdata('feedback','Successfully Added');
                #echo "Successfully Added";
                if(!empty($aid)){
                $data1 = array();
                $data1 = array(
                    'salary_id' => $sid,
                    'basic' => $basic,
                    'medical' => $medical,
                    'house_rent' => $houserent,
                    'conveyance' => $conveyance
                );
                $success = $this->employee_model->Update_Addition($aid,$data1);                    
                }
                if(!empty($did)){
                 $data2 = array();
                $data2 = array(
                    'salary_id' => $sid,
                    'provident_fund' => $provident,
                    'bima' => $bima,
                    'tax' => $tax,
                    'others' => $others
                );
                $success = $this->employee_model->Update_Deduction($did,$data2);                    
                }

                echo "Successfully Updated";                
            } else {
                $success = $this->employee_model->Add_Salary($data);
                $insertId = $this->db->insert_id();
                #$this->session->set_flashdata('feedback','Successfully Added');
                #echo "Successfully Added";
                $data1 = array();
                $data1 = array(
                    'salary_id' => $insertId,
                    'basic' => $basic,
                    'medical' => $medical,
                    'house_rent' => $houserent,
                    'conveyance' => $conveyance
                );
                $success = $this->employee_model->Add_Addition($data1);
                $data2 = array();
                $data2 = array(
                    'salary_id' => $insertId,
                    'provident_fund' => $provident,
                    'bima' => $bima,
                    'tax' => $tax,
                    'others' => $others
                );
                $success = $this->employee_model->Add_Deduction($data2); 
                echo "Successfully Added";
            }           
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
	public function confirm_mail_send($email,$pass_hash){
		$config = Array( 
		'protocol' => 'smtp', 
		'smtp_host' => 'ssl://smtp.googlemail.com', 
		'smtp_port' => 465, 
		'smtp_user' => 'mail.imojenpay.com', 
		'smtp_pass' => ''
		); 		  
         $from_email = "imojenpay@imojenpay.com"; 
         $to_email = $email; 
   
         //Load email library 
         $this->load->library('email',$config); 
   
         $this->email->from($from_email, 'Dotdev'); 
         $this->email->to($to_email);
         $this->email->subject('Hr Syatem'); 
		 $message	 =	"Your Login Email:"."$email";
		 $message	.=	"Your Password :"."$pass_hash"; 
         $this->email->message($message); 
   
         //Send mail 
         if($this->email->send()){ 
         	$this->session->set_flashdata('feedback','Kindly check your email To reset your password');
		 }
         else {
         $this->session->set_flashdata("feedback","Error in sending Email."); 
		 }			
	}
    public function Inactive_Employee(){
        $data['invalidem'] = $this->employee_model->getInvalidUser();
        $this->load->view('backend/invalid_user',$data);
    }
    public function EducationDelete() {
        $id = $this->input->get('id'); // Get the education record ID from query parameters
    
        // Load the model and delete the record
        $this->load->model('Employee_Model');
        $result = $this->Employee_Model->delete_record($id);
    
        if ($result) {
            echo "success"; // Success response
        } else {
            echo "failure"; // Failure response
        }
    }
    public function ExperienceDelete() {
        $id = $this->input->get('id'); // Get the education record ID from query parameters
    
        // Load the model and delete the record
        $this->load->model('Employee_Model');
        $result = $this->Employee_Model->delete_exprecord($id);
    
        if ($result) {
            echo "success"; // Success response
        } else {
            echo "failure"; // Failure response
        }
    }
    public function createcourse() {
        if ($this->session->userdata('user_login_access') != False) {
            // Load necessary models
            $this->load->model('employee_model');
            $this->load->model('course_model'); // Assuming you have a model for courses

            // Fetch employee data if needed
            $data['employee'] = $this->employee_model->emselect();
            $data['departments'] = $this->course_model->getAllDepartments();
            // Check if there's an ID in the request (for editing an existing course)
           
                $data['courses'] = $this->course_model->get_all_courses(); // Fetch course data for editing
            

            // Load the view for creating a course
            $this->load->view('backend/create_course', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function add_course() {
        // Load the database library
        $this->load->model('Course_model');
    
        // Validate form inputs
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required|trim');
        $this->form_validation->set_rules('due_date', 'Due Date', 'required');
        $this->form_validation->set_rules('course_url', 'Course URL', 'required|valid_url');
    
        if ($this->form_validation->run() == FALSE) {
            // Validation failed, return errors
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => validation_errors()
                ]));
            return;
        }
    
        // Prepare course data
        $courseData = [
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'mandatory' => $this->input->post('mandatory') ? 1 : 0,
            'due_date' => $this->input->post('due_date'),
            'course_url' => $this->input->post('course_url'),
            'recurrence' => $this->input->post('recurrence'),
            'department' =>$this->input->post('department')
        ];
    
        // Add file upload handling if needed
        if (!empty($_FILES['course_files']['name'])) {
            // Configure upload settings
            $config['upload_path'] = './uploads/courses/';
            $config['allowed_types'] = 'pdf|doc|docx|jpg|png';
            $this->load->library('upload', $config);
    
            if ($this->upload->do_upload('course_files')) {
                $fileData = $this->upload->data();
                $courseData['file_path'] = $fileData['file_name'];
            }
        }
    
        // Insert course to database
        $result = $this->Course_model->add_course($courseData);
    
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Course added successfully'
                ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to add course'
                ]));
        }
    }
    public function edit_course() {
        if ($this->session->userdata('user_login_access') != false) {
            $id = $this->input->get('id'); // Get the course ID from the URL
            $this->load->model('course_model');
            
            // Fetch the course data
            $data['course'] = $this->course_model->get_course_by_id($id);
            $data['departments'] = $this->course_model->getAllDepartments(); // Fetch departments for the dropdown
    
            // Load the edit view
            $this->load->view('backend/edit_course', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function update_course() {
        if ($this->session->userdata('user_login_access') != false) {
            // Set validation rules
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');
            $this->form_validation->set_rules('course_url', 'Course URL', 'required|valid_url');
            $this->form_validation->set_rules('recurrence', 'Recurrence', 'required');
        
            if ($this->form_validation->run() == FALSE) {
                // Validation failed, reload the form with error messages
                $id = $this->input->post('id'); // Get the course ID
                $this->edit_course($id); // Reload the edit course view with errors and course data
            } else {
                // Validation passed, process the form data
                $data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'department' => $this->input->post('department'), // Capture department
                    'mandatory' => $this->input->post('mandatory') ? 1 : 0,
                    'due_date' => $this->input->post('due_date'),
                    'course_url' => $this->input->post('course_url'),
                    'recurrence' => $this->input->post('recurrence')
                );
        
                $id = $this->input->post('id'); // Get the course ID
                
                // Call the model method to update the course
                if ($this->course_model->update_course($id, $data)) {
                    // Redirect to the course list or success page
                    $this->session->set_flashdata('success', 'Course updated successfully!');
                    redirect('employee/createcourse'); // Redirect to the course list
                } else {
                    // Handle update failure
                    $this->session->set_flashdata('error', 'Failed to update course.');
                    redirect('employee/edit_course?id=' . $id); // Redirect back to the edit form
                }
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
 // Display Course Assignment Page
 public function course_assignment() {
    $data['departments'] = $this->Course_model->get_all_departments();
    $data['courses'] = $this->Course_model->get_all_courses();

    $this->load->view('backend/header');
    $this->load->view('backend/sidebar');
    $this->load->view('backend/course_assignment', $data);
    $this->load->view('backend/footer');
}

// Handle Course Filtering
public function filter_courses() {
    $department = $this->input->post('department');
    $date = $this->input->post('date');
    $nature = $this->input->post('nature');

    $courses = $this->Course_model->filter_courses($department, $nature, $date);

    echo json_encode(['courses' => $courses]);
}

// Create Course Assignment
public function create_course_assignment() {
    $data = [
        'course_id' => $this->input->post('course'),
        'department' => $this->input->post('department'),
        'date' => $this->input->post('date'),
        'nature' => $this->input->post('nature'),
    ];

    if ($this->Course_model->assign_course($data)) {
        $this->session->set_flashdata('success', 'Course assignment created successfully!');
    } else {
        $this->session->set_flashdata('error', 'Failed to create course assignment.');
    }

    redirect('employee/course_assignment');
}
}
    
