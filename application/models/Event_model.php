<?php

class Event_model extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    public function GetEvent(){
        $sql = "SELECT * FROM `event` ORDER BY `event`.`date` DESC;";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result; 
    }

    public function Published_Event($data){
        $this->db->insert('event', $data);
    }

    public function GetEventlimit(){
        $this->db->order_by('date', 'DESC');
        $query = $this->db->get('event');
        $result = $query->result();
        return $result;        
    }

    public function GetEventById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('event');
        return $query->row(); // Return a single result
    }

    public function Update_Event($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('event', $data);
    }

    public function Delete_Event($id) {
        $this->db->where('id', $id);
        return $this->db->delete('event');
    }
}
?>
