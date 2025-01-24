<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Load the database
    }

    /**
     * Insert a new meeting into the database.
     *
     * @param array $data Meeting data to be inserted.
     * @return bool True if the insert is successful, false otherwise.
     */
    public function insert_meeting($data) {
        // Insert the meeting data
        $this->db->insert('meetings', [
            'meeting_title' => $data['meeting_title'],
            'meeting_description' => $data['meeting_description'],
            'meeting_date' => $data['meeting_date'],
            'meeting_time' => $data['meeting_time'],
            'recurrence' => $data['recurrence']
        ]);
        
        $meeting_id = $this->db->insert_id(); // Get the last inserted meeting ID

        // Insert into the meeting_departments table
        if (!empty($data['departments'])) {
            foreach ($data['departments'] as $department_id) {
                $this->db->insert('meeting_departments', [
                    'meeting_id' => $meeting_id,
                    'department_id' => $department_id
                ]);
            }
        }

        // Insert into the meeting_designations table
        if (!empty($data['designations'])) {
            foreach ($data['designations'] as $designation_id) {
                $this->db->insert('meeting_designations', [
                    'meeting_id' => $meeting_id,
                    'designation_id' => $designation_id
                ]);
            }
        }

        return true; // Return true if everything is successful
    }

    /**
     * Retrieve all meetings from the database.
     *
     * @return array List of all meeting objects.
     */
    public function get_all_meetings() {
        $query = $this->db->get('meetings'); // Replace 'meetings' with your actual table name
        return $query->result(); // Return the result as an array of objects
    }

    /**
     * Retrieve all meetings with details (including department and designation).
     *
     * @return array List of all meeting objects with details.
     */
    public function get_all_meetings_with_details() {
        $this->db->select('meetings.*, department.dep_name, designation.des_name');
        $this->db->from('meetings');
        $this->db->join('department', 'meetings.dep_id = department.id', 'left'); // Join departments using dep_id
        $this->db->join('designation', 'meetings.designation_id = designation.id', 'left'); // Join designations using designation_id
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Update a meeting by its ID.
     *
     * @param int $id Meeting ID.
     * @param array $data Updated meeting data.
     * @return bool True if the update is successful, false otherwise.
     */
    public function update_meeting($id, $data) {
    // Update main meeting details
    $this->db->where('id', $id);
    $this->db->update('meetings', [
        'meeting_title' => $data['meeting_title'],
        'meeting_description' => $data['meeting_description'],
        'meeting_date' => $data['meeting_date'],
        'meeting_time' => $data['meeting_time'],
        'recurrence' => $data['recurrence']
    ]);

    // Update departments
    $this->db->delete('meeting_departments', ['meeting_id' => $id]);
    foreach ($data['departments'] as $department_id) {
        $this->db->insert('meeting_departments', [
            'meeting_id' => $id,
            'department_id' => $department_id
        ]);
    }

    // Update designations
    $this->db->delete('meeting_designations', ['meeting_id' => $id]);
    foreach ($data['designations'] as $designation_id) {
        $this->db->insert('meeting_designations', [
            'meeting_id' => $id,
            'designation_id' => $designation_id
        ]);
    }

    return true;
}
    /**
     * Delete a meeting by its ID.
     *
     * @param int $id Meeting ID.
     * @return bool True if the delete is successful, false otherwise.
     */
    public function delete_meeting($id) {
        // Start a transaction to ensure atomicity
        $this->db->trans_start();
    
        // Delete dependent records from meeting_departments
        $this->db->where('meeting_id', $id);
        $this->db->delete('meeting_departments');

        $this->db->where('meeting_id', $id);
        $this->db->delete('meeting_designations');
    
        // Delete the meeting record
        $this->db->where('id', $id);
        $this->db->delete('meetings');
    
        // Complete the transaction
        $this->db->trans_complete();
    
        // Check transaction status
        if ($this->db->trans_status() === FALSE) {
            // Rollback and return false if the transaction failed
            return FALSE;
        } else {
            // Commit and return true if the transaction was successful
            return TRUE;
        }
    }
    

    /**
     * Filter meetings by specific criteria.
     *
     * @param array $filters Associative array of filter conditions.
     * @return array Filtered list of meeting objects.
     */
    public function filter_meetings($filters) {
        if (!empty($filters['department'])) {
            $this->db->where('department', $filters['department']);
        }
        if (!empty($filters['designation'])) {
            $this->db->where('designation', $filters['designation']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['date'])) {
            $this->db->where('meeting_date', $filters['date']);
        }

        $query = $this->db->get('meetings');
        return $query->result();
    }

    /**
     * Count the total number of meetings.
     *
     * @return int Total number of meetings.
     */
    public function count_meetings() {
        return $this->db->count_all('meetings');
    }

    /**
     * Retrieve meetings using pagination.
     *
     * @param int $limit Number of records to retrieve.
     * @param int $offset Starting point of the records.
     * @return array List of paginated meeting objects.
     */
    public function get_meetings_by_page($limit, $offset) {
        $this->db->limit($limit, $offset);
        $query = $this->db->get('meetings');
        return $query->result();
    }

    /**
     * Bulk delete meetings by their IDs.
     *
     * @param array $ids Array of meeting IDs to delete.
     * @return bool True if the delete is successful, false otherwise.
     */
    public function bulk_delete_meetings($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->delete('meetings');
    }

    /**
     * Get meetings within a specific date range.
     *
     * @param string $start_date Start date of the range (YYYY-MM-DD).
     * @param string $end_date End date of the range (YYYY-MM-DD).
     * @return array List of meeting objects within the date range.
     */
    public function get_meetings_by_date_range($start_date, $end_date) {
        $this->db->where('meeting_date >=', $start_date);
        $this->db->where('meeting_date <=', $end_date);
        $query = $this->db->get('meetings');
        return $query->result();
    }

    /**
     * Search meetings by title or description.
     *
     * @param string $keyword Search keyword.
     * @return array List of meetings that match the search criteria.
     */
    public function search_meetings($keyword) {
        $this->db->like('meeting_title', $keyword);
        $this->db->or_like('meeting_description', $keyword);
        $query = $this->db->get('meetings');
        return $query->result();
    }
    public function get_meeting_details($meeting_id = null) {
        $this->db->select('
            m.id,
            m.meeting_title,
            m.meeting_description,
            m.status,
            m.meeting_date,
            m.meeting_time,
            m.recurrence,
            GROUP_CONCAT(DISTINCT d.dep_name) AS department,
            GROUP_CONCAT(DISTINCT des.des_name) AS designation
        ');
        $this->db->from('meetings m');
        $this->db->join('meeting_departments md', 'm.id = md.meeting_id', 'left');
        $this->db->join('department d', 'md.department_id = d.id', 'left');
        $this->db->join('meeting_designations mdes', 'm.id = mdes.meeting_id', 'left');
        $this->db->join('designation des', 'mdes.designation_id = des.id', 'left');
        
        if ($meeting_id) {
            $this->db->where('m.id', $meeting_id);
        }
        
        $this->db->group_by('m.id'); // Group by meeting ID
        $query = $this->db->get();
        return $query->result_array(); // Return the result as an array
    }
    
    /**
     * Update the status of a meeting.
     *
     * @param int $id Meeting ID.
     * @param string $status New status of the meeting.
     * @return bool True if the update is successful, false otherwise.
     */
    public function update_meeting_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('meetings', ['status' => $status]);
    }

    /**
     * Get all departments.
     *
     * @return array List of department objects.
     */
    public function get_departments() {
        $query = $this->db->get('department'); // Ensure 'department' is the correct table name
        return $query->result(); // This should return an array of department objects
    }
    
    /**
     * Get all designations.
     *
     * @return array List of designation objects.
     */
    public function get_designations() {
        $query = $this->db->get('designation'); // Ensure 'designation' is the correct table name
        return $query->result(); // This should return an array of designation objects
    }
    
    

}