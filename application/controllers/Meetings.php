<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meetings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Meeting_model'); // Load the model for database operations
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model');
        $this->load->model('settings_model');  
        $this->load->model('notice_model');    
        $this->load->model('project_model');    
        $this->load->library('session');
        $this->load->model('leave_model');   
        $this->load->library('form_validation');
    }

    public function index() {
        // Retrieve all meetings with department and designation details
        $data['meetings'] = $this->Meeting_model->get_meeting_details();
        $this->load->view('backend/meeting_list', $data);
    }
    
    public function create() {
        // Fetch departments and designations for dropdowns
        $data['departments'] = $this->Meeting_model->get_departments();
        $data['designations'] = $this->Meeting_model->get_designations();
    
        // Debugging: Check if data is retrieved
        log_message('debug', 'Departments: ' . print_r($data['departments'], true));
        log_message('debug', 'Designations: ' . print_r($data['designations'], true));
        
        // Load the create meeting view
        $this->load->view('backend/create_meeting', $data);
    }

    public function store() {
        // Validate the form data
        $this->form_validation->set_rules('meeting_title', 'Meeting Title', 'required|trim');
        $this->form_validation->set_rules('meeting_description', 'Description', 'required|trim');
        $this->form_validation->set_rules('meeting_date', 'Date', 'required|trim');
        $this->form_validation->set_rules('meeting_time', 'Time', 'required|trim');
        $this->form_validation->set_rules('departments[]', 'Departments', 'required');
        $this->form_validation->set_rules('designations[]', 'Designations', 'required');
        $this->form_validation->set_rules('recurrence', 'Recurrence', 'required'); // Ensure this is validated
    
        if ($this->form_validation->run() == FALSE) {
            // Validation failed, reload the form with errors
            $data['departments'] = $this->settings_model->get_departments();
            $data['designations'] = $this->settings_model->get_designations();
            $this->load->view('backend/create_meeting', $data);
        } else {
            // Prepare data for insertion
            $data = array(
                'meeting_title' => $this->input->post('meeting_title', TRUE),
                'meeting_description' => $this->input->post('meeting_description', TRUE),
                'meeting_date' => $this->input->post('meeting_date', TRUE),
                'meeting_time' => $this->input->post('meeting_time', TRUE),
                'recurrence' => $this->input->post('recurrence', TRUE), // Ensure this is included
                'departments' => json_encode($this->input->post('departments', TRUE)), // Store departments as JSON
                'designations' => json_encode($this->input->post('designations', TRUE)) // Store designations as JSON
            );
    
            // Save the meeting data using the model
            if ($this->Meeting_model->insert_meeting($data)) {
                // Set success message and redirect to the meeting list page
                $this->session->set_flashdata('success', 'Meeting scheduled successfully!');
            } else {
                // Set error message
                $this->session->set_flashdata('error', 'Failed to schedule the meeting.');
            }
    
            redirect('meetings/index'); // Adjust the redirection as needed
        }
    }
    

    public function edit($id) {
        // Fetch meeting details for editing
        $data['meeting'] = $this->Meeting_model->get_meeting_by_id($id);
        if (!$data['meeting']) {
            $this->session->set_flashdata('error', 'Meeting not found.');
            redirect('meetings');
        }
        $data['departments'] = $this->settings_model->get_departments();
        $data['designations'] = $this->settings_model->get_designations();
        $this->load->view('backend/edit_meeting', $data);
    }

    public function update($id) {
        // Validate the form data
        $this->form_validation->set_rules('meeting_title', 'Meeting Title', 'required|trim');
        $this->form_validation->set_rules('meeting_description', 'Description', 'required|trim');
        $this->form_validation->set_rules('meeting_date', 'Date', 'required|trim');
        $this->form_validation->set_rules('meeting_time', 'Time', 'required|trim');
        $this->form_validation->set_rules('departments[]', 'Departments', 'required');
        $this->form_validation->set_rules('designations[]', 'Designations', 'required');
        $this->form_validation->set_rules('recurrence', 'Recurrence', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed, reload the form with errors
            $data['meeting'] = $this->Meeting_model->get_meeting_by_id($id);
            if (!$data['meeting']) {
                $this->session->set_flashdata('error', 'Meeting not found.');
                redirect('meetings');
            }
            $data['departments'] = $this->settings_model->get_departments();
            $data['designations'] = $this->settings_model->get_designations();
            $this->load->view('backend/edit_meeting', $data);
        } else {
            // Prepare data for updating
            $data = array(
                'meeting_title' => $this->input->post('meeting_title', TRUE),
                'meeting_description' => $this->input->post('meeting_description', TRUE),
                'meeting_date' => $this->input->post('meeting_date', TRUE),
                'meeting_time' => $this->input->post('meeting_time', TRUE),
                'recurrence' => $this->input->post('recurrence', TRUE),
                'departments' => $this->input->post('departments', TRUE),
                'designations' => $this->input->post('designations', TRUE)
            );

            // Update meeting data using the model
            if ($this->Meeting_model->update_meeting($id, $data)) {
                // Set success message and redirect to the meeting list page
                $this->session->set_flashdata('success', 'Meeting updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update the meeting.');
            }
            redirect('meetings');
        }
    }

    public function delete($id) {
        // Delete the meeting
        if ($this->Meeting_model->delete_meeting($id)) {
            $this->session->set_flashdata('success', 'Meeting deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete the meeting.');
        }
        redirect('meetings');
    }
    
    
    public function view($id) {
        // Fetch meeting details for viewing
        $data['meeting'] = $this->Meeting_model->get_meeting_by_id($id);
        if ($data['meeting']) {
            $this->load->view('backend/view_meeting', $data);
        } else {
            // If the meeting does not exist, set an error and redirect
            $this->session->set_flashdata('error', 'Meeting not found.');
            redirect('meetings');
        }
    }

    public function meeting_views() {
        // Check user authentication
        
        if (!$this->session->userdata('user_login_access')) {
            redirect(base_url(), 'refresh');
        }

        // Load the Meeting model
        $this->load->model('Meeting_model');

        // Fetch departments and designations for dropdowns
        $data['departments'] = $this->Meeting_model->get_departments();
        $data['designations'] = $this->Meeting_model->get_designations();

        // Load the views
        $this->load->view('backend/meeting_view', $data); // Load the meeting form view
        $this->load->view('backend/footer');
    }

    public function meeting_notification() {
        // Fetch meetings from the model
        $data['meetings'] = $this->Meeting_model->get_all_meetings(); // Adjust the method name as necessary

        // Load the view and pass the meetings data
       
        $this->load->view('backend/meeting_notification', $data); // Ensure this path is correct

    }
    
    
}