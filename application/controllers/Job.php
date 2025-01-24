<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends CI_Controller {

	    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model');
        $this->load->model('settings_model');    
        $this->load->model('notice_model');    
        $this->load->model('project_model');    
        $this->load->model('leave_model');    
        $this->load->model('job_model'); // Ensure you have a model to fetch job data

    }
    
	public function joblisting()
{
    if ($this->session->userdata('user_login_access') != False) {
        // Load job data from the database
        $this->load->model('job_model'); // Ensure you have a model to fetch job data
        $data['jobs'] = $this->job_model->get_all_jobs(); // Fetch job listings
        $data['departments'] = $this->job_model->getAllDepartments();
        // Pass the job data to the view
        $this->load->view('backend/joblisting', $data);
    } else {
        redirect(base_url(), 'refresh');
    }
}
public function empjoblisting()
{
    if ($this->session->userdata('user_login_access') != False) {
        // Load job data from the database
        $this->load->model('job_model'); // Ensure you have a model to fetch job data
        $data['jobs'] = $this->job_model->get_all_jobs(); // Fetch job listings
        $data['departments'] = $this->job_model->getAllDepartments();
        // Pass the job data to the view
        $this->load->view('backend/empjoblisting', $data);
    } else {
        redirect(base_url(), 'refresh');
    }
}
public function addJob()
{
    if ($this->session->userdata('user_login_access') != False) {
        $data = [
            'job_title' => $this->input->post('job_title'),
            'department' => $this->input->post('department'),
            'location' => $this->input->post('location'),
            'job_status' => $this->input->post('job_status'), // Captures Open or Closed
            'work_mode' => $this->input->post('work_mode'),
            'description' => $this->input->post('description'),
        ];
        echo "Successfully added";
        $this->db->insert('job_listings', $data);
        redirect(base_url(), 'refresh');
    } else {
        redirect(base_url(), 'refresh');
    }
}
public function getJob($id)
{
    $this->load->model('Job_model'); // Replace 'Job_model' with the actual name of your model
    $job = $this->Job_model->getJob($id);
    echo json_encode($job);
}

public function updateJob()
{
    if ($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('id');
        $data = [
            'job_title' => $this->input->post('job_title'),
            'department' => $this->input->post('department'),
            'location' => $this->input->post('location'),
            'job_status' => $this->input->post('job_status'),
            'work_mode' => $this->input->post('work_mode'),
            'description' => $this->input->post('description'),
        ];

        $this->db->where('id', $id);
        $this->db->update('job_listings', $data);
        redirect(base_url(), 'refresh');
    } else {
        redirect(base_url(), 'refresh');
    }
}

public function bulkActions() {
    $this->load->model('Job_model'); // Ensure you have a model to fetch job data

    $data['jobs'] = $this->Job_model->get_all_jobs(); // Fetch all jobs
    $this->load->view('backend/bulk_action', $data);
}

public function processBulkActions() {
    $this->load->model('Job_model'); // Ensure you have a model to fetch job data

    $job_ids = $this->input->post('job_ids');
    $bulk_action = $this->input->post('bulk_action');
    $job_status = $this->input->post('job_status');
    $work_mode = $this->input->post('work_mode');

    if (!empty($job_ids)) {
        foreach ($job_ids as $id) {
            // Update job status if selected
            if (!empty($job_status)) {
                $this->Job_model->updateJobStatus($id, $job_status);
            }
            // Update work mode if selected
            if (!empty($work_mode)) {
                $this->Job_model->updateWorkMode($id, $work_mode);
            }

            // Perform the bulk action (delete if applicable)
            switch ($bulk_action) {
                case 'delete':
                    $this->Job_model->deleteJob($id);
                    break;
            }
        }
        $this->session->set_flashdata('message', 'Bulk action applied successfully.');
    } else {
        $this->session->set_flashdata('error', 'Please select jobs and an action.');
    }

    redirect('Job/bulkActions');
}


public function deleteJob($id)
{
    // Load the Job model if not already loaded
    $this->load->model('Job_model');
    $this->Job_model->deleteJob($id);
    redirect('Job/joblisting');
}

    
}