<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends CI_Controller {


    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model'); 
        $this->load->model('notice_model');
        $this->load->model('settings_model');
        $this->load->model('leave_model');
    }
    
	public function index()
	{
		#Redirect to Admin dashboard after authentication
        if ($this->session->userdata('user_login_access') == 1)
            redirect('dashboard/Dashboard');
            $data=array();
            #$data['settingsvalue'] = $this->dashboard_model->GetSettingsValue();
			$this->load->view('login');
	}
    public function All_notice(){
        if($this->session->userdata('user_login_access') != False) {
        $data['notice'] = $this->notice_model->GetNotice();
        $this->load->view('backend/notice',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Published_Notice() {
        if ($this->session->userdata('user_login_access') != False) {
            $filetitle = $this->input->post('title');        
            $ndate = $this->input->post('nodate');    
    
            // Validation Rules
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('', '');  // Remove error delimiters
    
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[25]|max_length[150]|regex_match[/[a-zA-Z]/]|xss_clean', [
                'required' => 'The title is required.',
                'min_length' => 'The title must be at least 25 characters long.',
                'max_length' => 'The title must not exceed 150 characters.',
                'regex_match' => 'The title must contain at least one alphabet.'
            ]);
            $this->form_validation->set_rules('nodate', 'Published Date', 'required');
    
            // Check Validation
            if ($this->form_validation->run() == FALSE) {
                // Return validation errors without page refresh
                $errors = validation_errors();
                echo $errors;  // Return validation errors as plain text
                return;
            }
    
            // Proceed with file upload and database insertion
            if (!empty($_FILES['file_url']['name'])) {
                $file_name = $_FILES['file_url']['name'];
                $config = array(
                    'file_name' => $file_name,
                    'upload_path' => "./assets/images/notice",
                    'allowed_types' => "gif|jpg|png|jpeg|pdf|doc|docx",
                    'overwrite' => False,
                    'max_size' => "50720"
                );
    
                $this->load->library('upload', $config);
                $this->upload->initialize($config);                
                if (!$this->upload->do_upload('file_url')) {
                    $error = $this->upload->display_errors();
                    echo $error;  // Return upload error as plain text
                    return;
                }
    
                // File upload successful
                $path = $this->upload->data();
                $img_url = $path['file_name'];
                $data = array(
                    'title' => $filetitle,
                    'file_url' => $img_url,
                    'date' => $ndate
                );
    
                // Insert into the database without returning any message
                $this->notice_model->Published_Notice($data); 
    
                // Send success response as plain text without wrapping it in quotes or array
                echo 'Successfully Added';  // Return success message
            } else {
                echo 'No file uploaded.';  // Return no file uploaded error
            }
        } else {
            echo 'User not logged in.';  // Return user not logged in error
        }
    }
    
    
    
    
}