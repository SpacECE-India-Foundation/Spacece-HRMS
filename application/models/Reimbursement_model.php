<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReimbursementModel extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function saveRequest($data) {
        $data['employee_name'] = $this->session->userdata('user_name');
        $data['employee_email'] = $this->session->userdata('email');
        return $this->db->insert('reimbursement_requests', $data);
    }

    public function getRequestsByEmployee($employee_id) {
        $this->db->where('employee_id', $employee_id);
        $this->db->order_by('submitted_date', 'DESC');
        return $this->db->get('reimbursement_requests')->result_array();
    }

    public function getAllRequests($status = 'all') {
        if ($status !== 'all') {
            $this->db->where('status', $status);
        }
        $this->db->order_by('submitted_date', 'DESC');
        return $this->db->get('reimbursement_requests')->result_array();
    }

    public function getRequestById($request_id) {
        $this->db->where('id', $request_id);
        return $this->db->get('reimbursement_requests')->row_array();
    }

    public function updateRequest($request_id, $data) {
        if (isset($data['status']) && $data['status'] != 'Pending') {
            $data['processed_by'] = $this->session->userdata('user_login_id');
            $data['processor_name'] = $this->session->userdata('user_name');
            $data['processed_date'] = date('Y-m-d H:i:s');
        }
        
        $this->db->where('id', $request_id);
        return $this->db->update('reimbursement_requests', $data);
    }

    public function getStatistics($employee_id = null) {
        if ($employee_id) {
            $this->db->where('employee_id', $employee_id);
        }
        
        $this->db->select('
            COUNT(CASE WHEN status = "Pending" THEN 1 END) as pending_count,
            COUNT(CASE WHEN status = "Approved" THEN 1 END) as approved_count,
            COUNT(CASE WHEN status = "Rejected" THEN 1 END) as rejected_count,
            SUM(CASE WHEN status = "Approved" THEN amount ELSE 0 END) as total_approved_amount,
            SUM(amount) as total_requested_amount
        ');
        
        return $this->db->get('reimbursement_requests')->row_array();
    }

    public function getMonthlySummary($year, $month, $employee_id = null) {
        if ($employee_id) {
            $this->db->where('employee_id', $employee_id);
        }
        
        $this->db->where('YEAR(expense_date)', $year);
        $this->db->where('MONTH(expense_date)', $month);
        $this->db->select('
            category,
            COUNT(*) as request_count,
            SUM(amount) as total_amount,
            SUM(CASE WHEN status = "Approved" THEN amount ELSE 0 END) as approved_amount
        ');
        $this->db->group_by('category');
        
        return $this->db->get('reimbursement_requests')->result_array();
    }

    public function searchRequests($search_term, $status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        
        $this->db->group_start();
        $this->db->like('description', $search_term);
        $this->db->or_like('category', $search_term);
        $this->db->or_like('employee_name', $search_term);
        $this->db->or_like('employee_email', $search_term);
        $this->db->group_end();
        
        $this->db->order_by('submitted_date', 'DESC');
        return $this->db->get('reimbursement_requests')->result_array();
    }

    public function getRequestsByDateRange($start_date, $end_date, $employee_id = null) {
        if ($employee_id) {
            $this->db->where('employee_id', $employee_id);
        }
        
        $this->db->where('expense_date >=', $start_date);
        $this->db->where('expense_date <=', $end_date);
        $this->db->order_by('expense_date', 'ASC');
        
        return $this->db->get('reimbursement_requests')->result_array();
    }
}