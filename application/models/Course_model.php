<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        // Load the database library
        $this->load->database();
    }

    // Method to insert a new course
    public function insert_course($data) {
        // Insert the course data into the 'courses' table
        if ($this->db->insert('courses', $data)) {
            return $this->db->insert_id(); // Return the ID of the newly inserted course
        } else {
            log_message('error', 'Failed to insert course: ' . $this->db->last_query());
            return false; // Return false if the insert fails
        }
    }

    // Method to get a course by its ID
    public function get_course_by_id() {
        // Fetch the course data from the 'courses' table where the ID matches

        $query = $this->db->get('courses');

        // Check if a course was found and return the result
        if ($query->num_rows() > 0) {
            return $query->row(); // Return a single row
        } else {
            return null; // No course found
        }
    }

    // Method to get all courses
    public function get_all_courses() {
        $query = $this->db->get('courses');
        return $query->result(); // Return all courses as an array of objects
    }

    // Method to update a course
    public function update_course($id, $data) {
        $this->db->where('id', $id);
        if ($this->db->update('courses', $data)) {
            return true; // Return true on success
        } else {
            log_message('error', 'Failed to update course: ' . $this->db->last_query());
            return false; // Return false on failure
        }
    }

    // Method to delete a course
    public function delete_course($id) {
        $this->db->where('id', $id);
        if ($this->db->delete('courses')) {
            return true; // Return true on success
        } else {
            log_message('error', 'Failed to delete course: ' . $this->db->last_query());
            return false; // Return false on failure
        }
    }

    // Method to get courses with optional filtering
    public function get_courses($department = null, $nature = null) {
        if ($department) {
            $this->db->where('department', $department);
        }
        if ($nature) {
            $this->db->where('nature', $nature);
        }
        $query = $this->db->get('courses');
        return $query->result(); // Return filtered courses as an array of objects
    }
    public function add_course($data) {
        return $this->db->insert('courses', $data);
    }
    public function getAllDepartments() {
        $query = $this->db->get('department'); // 'department' is your table name
        return $query->result_array();
    }
    
    
}