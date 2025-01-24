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

    public function index() {
        // Redirect to Admin dashboard after authentication
        if ($this->session->userdata('user_login_access') == 1)
            redirect('dashboard/Dashboard');
        
        $data = array();
        $this->load->view('login');
    }

    // Display all notices
    public function All_notice() {
        if ($this->session->userdata('user_login_access') != False) {
            $data['notice'] = $this->notice_model->GetNotice();
            $this->load->view('backend/notice', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    // Publish a new notice
    public function Published_Notice() {
        if ($this->session->userdata('user_login_access') != False) {
            $notice_id = $this->input->post('id'); // Check if it's an edit request
            $filetitle = $this->input->post('title');
            $ndate = $this->input->post('nodate');
    
            // Determine if it's an add or edit operation
            if ($notice_id) {
                // Edit mode: Fetch existing notice data
                $existing_notice = $this->notice_model->GetNoticeById($notice_id);
                if (!$existing_notice) {
                    echo 'Notice not found.'; // Return error if notice doesn't exist
                    return;
                }
            }
    
            // Validation Rules
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('', ''); // Remove error delimiters
    
            $this->form_validation->set_rules('title', 'Title', 'trim|xss_clean', [
                'xss_clean' => 'Invalid input detected.'
            ]);
            $this->form_validation->set_rules('nodate', 'Published Date', 'trim');
    
            // Check Validation
            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                echo $errors; // Return validation errors as plain text
                return;
            }
    
            // Edit mode: Use old values if inputs are empty
            if ($notice_id) {
                $filetitle = !empty($filetitle) ? $filetitle : $existing_notice->title; // Use object syntax
                $ndate = !empty($ndate) ? $ndate : $existing_notice->date; // Use object syntax
            }
    
            // File upload logic: Retain old file URL if no new file is uploaded
            $img_url = $notice_id ? $existing_notice->file_url : null; // Default to old file URL in edit mode
            if (!empty($_FILES['file_url']['name'])) {
                $file_name = $_FILES['file_url']['name'];
                $config = array(
                    'file_name' => $file_name,
                    'upload_path' => "./assets/images/notice",
                    'allowed_types' => "pdf|doc|docx",
                    'overwrite' => False,
                    'max_size' => "50720"
                );
    
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('file_url')) {
                    $error = 'Invalid file type. Only PDF, DOC, and DOCX files are allowed.';
                    echo $error; // Return upload error as plain text
                    return;
                }
    
                // File upload successful
                $path = $this->upload->data();
                $img_url = $path['file_name'];
            }
    
            // Prepare data for insertion or update
            $data = array(
                'title' => $filetitle,
                'date' => $ndate,
                'file_url' => $img_url
            );
    
            if ($notice_id) {
                // Edit mode: Update existing notice
                $this->notice_model->Update_Notice($notice_id, $data);
                echo 'Successfully Updated'; // Return update success message
            } else {
                // Add mode: Insert new notice
                if (empty($filetitle) || empty($ndate) || empty($img_url)) {
                    echo 'All fields are required for adding a new notice.'; // Prevent blank values in add mode
                    return;
                }
                $this->notice_model->Published_Notice($data);
                echo 'Successfully Added'; // Return add success message
            }
        } else {
            echo 'User not logged in.'; // Return user not logged in error
        }
    }
    
    

    
    // Delete an existing notice
    public function Delete_Notice($id) {
        if ($this->session->userdata('user_login_access') != False) {
            $this->notice_model->Delete_Notice($id); // Delete the notice
            redirect('Notice/All_notice');
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function NoticeByID()
{
    // Get the 'id' parameter from the GET request
    $id = $this->input->get('id');

    // Load the Notice_model if not already loaded
    $this->load->model('Notice_model');

    // Fetch the notice by ID
    $notice = $this->Notice_model->GetNoticeById($id);

    // Return the notice data as JSON
    if ($notice) {
        echo json_encode(['notice' => $notice]);
    } else {
        echo json_encode(['error' => 'Notice not found']);
    }
}

}
?>
