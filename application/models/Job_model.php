<?php
class Job_model extends CI_Model {
    public function get_all_jobs()
    {
        $query = $this->db->get('job_listings'); // Replace 'job_listings' with your actual table name
        return $query->result_array(); // Returns the job listings as an array
    }
    public function addJob($data)
{
    return $this->db->insert('job_listings', $data);
}
public function getJob($id)
{
    $this->db->where('id', $id);
    $query = $this->db->get('job_listings');
    return $query->row_array();
}

// In your Job_model

public function updateJobStatus($job_id, $status) {
    $this->db->where('id', $job_id);
    $this->db->update('job_listings', ['job_status' => $status]);
}

public function updateWorkMode($job_id, $work_mode) {
    $this->db->where('id', $job_id);
    $this->db->update('job_listings', ['work_mode' => $work_mode]);
}

public function deleteJob($job_id) {
    $this->db->where('id', $job_id);
    $this->db->delete('job_listings');
}
public function getAllDepartments() {
    $query = $this->db->get('department'); // 'department' is your table name
    return $query->result_array();
}



}
?>