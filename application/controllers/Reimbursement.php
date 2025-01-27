<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reimbursement extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        
        // Load all required models
        $this->load->model('login_model');
        $this->load->model('ReimbursementModel');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model');
        $this->load->model('settings_model');    // Added this
        $this->load->model('notice_model');    
        $this->load->model('project_model');    
        $this->load->library('session');
        $this->load->model('leave_model');
        
        // Check if user is logged in
        if ($this->session->userdata('user_login_access') != False) {
            $this->load->model('settings_model');
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    // Show reimbursement form for employees
    public function showForm() {
        $this->load->view('backend/reimbursement_form');
    }

    public function submitRequest()

{
    
    // CSRF Protection
    if (!$this->security->verify_request()) {
        
        echo json_encode(['status' => 'error', 'message' => 'Security check failed']);
        return;
        
    }

    // Validation Rules
    $this->form_validation->set_rules('expense_date', 'Expense Date', 'required');
    $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
    $this->form_validation->set_rules('description', 'Description', 'required|trim');
    $this->form_validation->set_rules('category', 'Category', 'required');

    if ($this->form_validation->run() == FALSE) {
        echo json_encode([
            'status' => 'error',
            'message' => validation_errors()
        ]);
        return;
    }

    // File Upload Configuration
    $config = [
        'upload_path' => './assets/receipts/',
        'allowed_types' => 'jpg|jpeg|png|pdf',
        'max_size' => 2048, // 2MB limit
        'file_name' => uniqid() . '_' . $_FILES['receipt']['name']
    ];
    $this->upload->initialize($config);

    // Check if receipt file was uploaded
    $receipt_path = null;
    if (isset($_FILES['receipt']['name']) && !empty($_FILES['receipt']['name'])) {
        if (!$this->upload->do_upload('receipt')) {
            echo json_encode([
                'status' => 'error',
                'message' => $this->upload->display_errors()
            ]);
            return;
        }
        $receipt_path = $this->upload->data('file_name');
    }

    // Prepare Data
    $data = [
        'employee_id' => $this->session->userdata('user_login_id'),
        'expense_date' => $this->input->post('expense_date'),
        'amount' => $this->input->post('amount'),
        'description' => $this->input->post('description'),
        'category' => $this->input->post('category'),
        'receipt' => $receipt_path,
        'status' => 'Pending',
        'submitted_date' => date('Y-m-d H:i:s')
    ];

    // Debug Logging
    log_message('debug', 'Reimbursement Request Data: ' . print_r($data, true));

    // Save to Database
    $this->load->model('ReimbursementModel'); // Ensure the model is loaded
    $result = $this->ReimbursementModel->saveRequest($data);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Reimbursement request submitted successfully'
        ]);
    } else {
        // Log DB error for debugging
        log_message('error', 'DB Insert Error: ' . $this->db->last_query());
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to save reimbursement request'
        ]);
    }
}

    // Other methods like viewMyRequests, viewAllRequests remain the same

    

    // View employee's own reimbursement requests
    public function viewMyRequests() {
        if ($this->session->userdata('user_login_access') != False) {
            $employee_id = $this->session->userdata('user_login_id');
            $data['requests'] = $this->ReimbursementModel->getRequestsByEmployee($employee_id);
            $this->load->view('backend/my_reimbursements', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    // Admin: View all reimbursement requests
    public function viewAllRequests($status = 'all') {
        if ($this->session->userdata('user_login_access') != False && $this->session->userdata('user_type') == 'ADMIN') {
            $data['requests'] = $this->ReimbursementModel->getAllRequests($status);
            
            // Add employee details to each request
            foreach ($data['requests'] as &$request) {
                $employee = $this->employee_model->emselectByCode($request['employee_id']);
                $request['employee_name'] = $employee->first_name . ' ' . $employee->last_name;
                $request['employee_email'] = $employee->em_email;
            }
            
            $data['current_status'] = $status;
            $this->load->view('backend/admin_reimbursements', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    // Admin: Update request status
    public function updateStatus() {
        if ($this->session->userdata('user_login_access') != False && $this->session->userdata('user_type') == 'ADMIN') {
            $request_id = $this->input->post('request_id');
            $status = $this->input->post('status');
            $remarks = $this->input->post('remarks');

            if (empty($request_id) || empty($status)) {
                echo "Invalid request parameters";
                return;
            }

            $update_data = array(
                'status' => $status,
                'remarks' => $remarks,
                'processed_date' => date('Y-m-d H:i:s'),
                'processed_by' => $this->session->userdata('user_login_id')
            );

            $this->ReimbursementModel->updateRequest($request_id, $update_data);
            echo "Status updated successfully";
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    // Download receipt
    public function downloadReceipt($request_id) {
        if ($this->session->userdata('user_login_access') != False) {
            $request = $this->ReimbursementModel->getRequestById($request_id);
            
            // Check if user has permission to access this receipt
            if ($this->session->userdata('user_type') != 'ADMIN' && 
                $request['employee_id'] != $this->session->userdata('user_login_id')) {
                show_error('Access Denied', 403);
                return;
            }

            $file_path = './assets/receipts/' . $request['receipt'];
            if (file_exists($file_path)) {
                $this->load->helper('download');
                force_download($file_path, NULL);
            } else {
                show_error('File not found', 404);
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    // Get request details for modal/edit
    public function getRequestDetails($request_id) {
        if ($this->session->userdata('user_login_access') != False) {
            $request = $this->ReimbursementModel->getRequestById($request_id);
            
            // Check permission
            if ($this->session->userdata('user_type') != 'ADMIN' && 
                $request['employee_id'] != $this->session->userdata('user_login_id')) {
                echo json_encode(['error' => 'Access denied']);
                return;
            }

            echo json_encode($request);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
}