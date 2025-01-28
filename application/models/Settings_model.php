<?php

class Settings_model extends CI_Model {

    // Constructor
    function __construct() {
        parent::__construct();
    }

    // Get all settings values
    public function GetSettingsValue() {
        $settings = $this->db->dbprefix('settings');
        $sql = "SELECT * FROM $settings";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }

    // Update settings by ID
    public function SettingsUpdate($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('settings', $data);
    }

    // Fetch all departments
    public function get_departments() {
        $this->db->select('id, dep_name'); // Using 'dep_name' as per the column in your department table
        $query = $this->db->get('department'); // Using 'department' as per the table name
        return $query->result_array(); // Returns departments as an array
    }

    // Fetch all designations
    public function get_designations() {
		// Assuming the designations are stored in a table named 'designation'
		$this->db->select('id, des_name'); // Updated column name to 'des_name'
		$query = $this->db->get('designation'); // Updated table name to 'designation'
		return $query->result_array(); // Returns designations as an array
	}
}
