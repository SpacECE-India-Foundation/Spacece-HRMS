<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model'); 
        $this->load->model('event_model'); // Changed from notice_model to event_model
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

    // Display all events
    public function All_event() {
        if ($this->session->userdata('user_login_access') != False) {
            $data['event'] = $this->event_model->GetEvent(); // Changed from GetNotice to GetEvent
            $this->load->view('backend/event', $data); // Changed view name from notice to event
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    // Publish a new event
    public function Published_Event() {
        if ($this->session->userdata('user_login_access') != False) {
            $event_id = $this->input->post('id'); // Check if it's an edit request
            $filetitle = $this->input->post('title');
            $edate = $this->input->post('eventdate'); // Changed 'nodate' to 'eventdate'
    
            // Determine if it's an add or edit operation
            if ($event_id) {
                // Edit mode: Fetch existing event data
                $existing_event = $this->event_model->GetEventById($event_id); // Changed from GetNoticeById to GetEventById
                if (!$existing_event) {
                    echo 'Event not found.'; // Return error if event doesn't exist
                    return;
                }
            }
    
            // Validation Rules
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('', ''); // Remove error delimiters
    
            $this->form_validation->set_rules('title', 'Title', 'trim|xss_clean', [
                'xss_clean' => 'Invalid input detected.'
            ]);
            $this->form_validation->set_rules('eventdate', 'Event Date', 'trim'); // Changed to eventdate
    
            // Check Validation
            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                echo $errors; // Return validation errors as plain text
                return;
            }
    
            // Edit mode: Use old values if inputs are empty
            if ($event_id) {
                $filetitle = !empty($filetitle) ? $filetitle : $existing_event->title; // Use object syntax
                $edate = !empty($edate) ? $edate : $existing_event->date; // Use object syntax
            }
    
            // File upload logic: Retain old file URL if no new file is uploaded
            $img_url = $event_id ? $existing_event->file_url : null; // Default to old file URL in edit mode
            if (!empty($_FILES['file_url']['name'])) {
                $file_name = $_FILES['file_url']['name'];
                $config = array(
                    'file_name' => $file_name,
                    'upload_path' => "./assets/images/event", // Changed path to event
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
                'date' => $edate, // Changed to eventdate
                'file_url' => $img_url
            );
    
            if ($event_id) {
                // Edit mode: Update existing event
                $this->event_model->Update_Event($event_id, $data); // Changed from Update_Notice to Update_Event
                echo 'Successfully Updated'; // Return update success message
            } else {
                // Add mode: Insert new event
                if (empty($filetitle) || empty($edate) || empty($img_url)) {
                    echo 'All fields are required for adding a new event.'; // Prevent blank values in add mode
                    return;
                }
                $this->event_model->Published_Event($data); // Changed from Published_Notice to Published_Event
                echo 'Successfully Added'; // Return add success message
            }
        } else {
            echo 'User not logged in.'; // Return user not logged in error
        }
    }

    // Delete an existing event
    public function Delete_Event($id) {
        if ($this->session->userdata('user_login_access') != False) {
            $this->event_model->Delete_Event($id); // Changed from Delete_Notice to Delete_Event
            redirect('Event/All_event'); // Changed from Notice/All_notice to Event/All_event
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function EventByID() {
        // Get the 'id' parameter from the GET request
        $id = $this->input->get('id');

        // Load the Event_model if not already loaded
        $this->load->model('Event_model');

        // Fetch the event by ID
        $event = $this->Event_model->GetEventById($id);

        // Return the event data as JSON
        if ($event) {
            echo json_encode(['event' => $event]);
        } else {
            echo json_encode(['error' => 'Event not found']);
        }
    }
}
?>
