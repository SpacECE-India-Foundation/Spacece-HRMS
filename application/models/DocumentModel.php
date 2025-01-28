<?php
class DocumentModel extends CI_Model {

    public function saveDocument($data) {
        $this->db->insert('documents', $data);
    }

    public function getDocumentById($id, $employee_id) {
        return $this->db->get_where('documents', ['id' => $id, 'employee_id' => $employee_id])->row_array();
    }

    public function sendEditRequest($id) {
        $this->db->where('id', $id);
        $this->db->update('documents', ['edit_request' => true]);
    }
    public function getAllDocuments() {
        // Fetch all documents, regardless of status
        $query = $this->db->get('documents');
        return $query->result_array();
    }
    // In Document_model.php
    public function getDocumentByEmployeeId($employeeId) {
        $this->db->where('emid', $employeeId);
        $query = $this->db->get('hrdocuments');  // 'hrdocuments' is the table storing document data
    
        if ($query->num_rows() > 0) {
            return $query->result(); // Return all rows (documents) as an array of objects
        } else {
            return null; // No documents found
        }
    }
    

    // Update the document status to 'Accepted' or 'Denied' and store reason (if any)
    public function updateDocumentStatus($document_id, $status, $reason = NULL) {
        $data = ['status' => $status];
        if ($reason !== NULL) {
            $data['reason'] = $reason;
        }
        $this->db->where('id', $document_id);
        $this->db->update('documents', $data);
    }
    public function updateEmployeeRole($em_code, $data)
    {
        $this->db->where('em_code', $em_code);
        return $this->db->update('employee', $data); // Replace 'employee' with your table name if different
    }
    // Fetch employee's email and phone details using employee ID
    public function getEmployeeDetails($employee_id) {
        $this->db->select('first_name, last_name, em_email, em_phone');
        $this->db->where('em_id', $employee_id);
        $query = $this->db->get('employee');
        return $query->row_array();
    }

    // Send message to employee (you can use an email service or other notifications)
    public function sendMessageToEmployee($employee_id, $message) {
        $employee = $this->getEmployeeDetails($employee_id);
        if ($employee) {
            // Send the message via email or SMS (Email in this case)
            mail($employee['em_email'], "Document Status Update", $message);
        }
    }
    public function insertDocument($data)
    {
        return $this->db->insert('hrdocuments', $data);
    }
    public function getDocumentTitles()
{
    $this->db->select('*');
    $this->db->from('document_titles');
    $query = $this->db->get();
    return $query->result_array(); // Return as an array of document titles
}
public function addTitle($title)
{
    $data = array('title' => $title);
    $this->db->insert('document_titles', $data);
}

public function getAllTitles()
{
    return $this->db->get('document_titles')->result_array();
}

public function updateTitle($id, $title)
{
    $this->db->set('title', $title);
    $this->db->where('id', $id);
    $this->db->update('document_titles');
}

public function deleteTitle($id)
{
    $this->db->where('id', $id);
    $this->db->delete('document_titles');
}

    public function getDocumentByEmpId($employeeId) {
        $this->db->select('*');
        $this->db->from('documents');
        $this->db->where('employee_id', $employeeId);
        $query = $this->db->get();
        return $query->result_array(); // Ensure this returns an array
    }
    public function insertDocumentTitle($data) {
        return $this->db->insert('document_titles', $data);
    }
    
    public function getHrDocumentTitles()
    {
        $query = $this->db->get('hr_document_names'); // Fetch the HR document names
        return $query->result_array();
    }

    // Add a new HR document title
    public function addHrDocumentTitle($title)
    {
        $data = array('title' => $title);
        $this->db->insert('hr_document_names', $data);
    }

    // Edit an existing HR document title
    public function editHrDocumentTitle($id, $title)
    {
        $data = array('title' => $title);
        $this->db->where('id', $id);
        $this->db->update('hr_document_names', $data);
    }

    // Delete an HR document title
    public function getAllDocumenttitle() {
        $query = $this->db->get('hr_document_names'); // Assuming the table is named 'documents'
        return $query->result();
    }
    
    public function deleteHrDocumentTitle($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('hr_document_names');
    }
}
?>