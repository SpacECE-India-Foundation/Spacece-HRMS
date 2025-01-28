<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('DocumentModel');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model');
        $this->load->model('settings_model');    
        $this->load->model('notice_model');    
        $this->load->model('project_model');    
        $this->load->library('session');
        $this->load->model('leave_model');   
    }
    public function showUploadForm()
{
    if ($this->session->userdata('user_login_access') != False) {
        $this->load->model('DocumentModel'); // Load your model
        $data['document_titles'] = $this->DocumentModel->getDocumentTitles(); // Fetch document titles
        $this->load->view('backend/uploadDoc', $data); // Pass data to the view
    } else {
        redirect(base_url(), 'refresh');
    }
}
public function documentList()
{
    // Fetch all document titles to display
    if ($this->session->userdata('user_login_access') != False) {
        $data['document_titles'] = $this->DocumentModel->getAllTitles();
        $this->load->view('backend/doctitle', $data);
    } else {
        redirect(base_url(), 'refresh');
    }
}

public function editDocumentTitle($id)
{
    $new_title = $this->input->post('document_title');
    $this->DocumentModel->updateTitle($id, $new_title);
    redirect(base_url(), 'refresh');
}

public function deleteDocumentTitle($id)
{
    $this->DocumentModel->deleteTitle($id);
    redirect('document/addDocumentTitle');
}

    public function viewUploadedDocument() {
        // Load the document model
        $this->load->model('DocumentModel');
        $employeeId=$this->session->userdata('user_code');
        // Fetch document details based on employee ID
        $data['document'] = $this->DocumentModel->getDocumentByEmployeeId($employeeId);
        
        // Pass the data to the view
        $this->load->view('backend/getdoc', $data);
    }
    
    public function hrUploadForm()
    {
        if ($this->session->userdata('user_login_access') != False) {
            $data['employee'] = $this->employee_model->emselect();
            $id               = $this->input->get('A');
            $data['documents'] = $this->DocumentModel->getAllDocumenttitle(); 
            if (!empty($id)) {
                $data['attval'] = $this->attendance_model->em_attendanceFor($id);
            }
            $this->load->view('backend/hruploadDoc', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function hr()
    {
        if ($this->session->userdata('user_login_access') != False) {
            $data['employee'] = $this->employee_model->emselect();
            $id               = $this->input->get('A');
            if (!empty($id)) {
                $data['attval'] = $this->attendance_model->em_attendanceFor($id);
            }
            $this->load->view('backend/managedoc', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function uploadDocument() {
        if ($this->session->userdata('user_login_access') != False) {
            $document_id = $this->input->post('id'); // Check if it's an edit request
            $docname = $this->input->post('name');
            
            // Check if it's an add or edit operation
            if ($document_id) {
                // Edit mode: Fetch existing document data
                $existing_document = $this->DocumentModel->getDocumentById($document_id);
                if (!$existing_document) {
                    echo 'Document not found.'; // Return error if document doesn't exist
                    return;
                }
            }
    
            // Validation Rules
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('', ''); // Remove error delimiters
    
            $this->form_validation->set_rules('name', 'Document Name', 'trim|xss_clean', [
                'xss_clean' => 'Invalid input detected.'
            ]);
    
            // Check Validation
            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                echo $errors; // Return validation errors as plain text
                return;
            }
    
            // Edit mode: Use old values if inputs are empty
            if ($document_id) {
                $docname = !empty($docname) ? $docname : $existing_document->name;
            }
    
            // File upload logic: Retain old file URL if no new file is uploaded
            $file_path = $document_id ? $existing_document->file_path : null; // Default to old file URL in edit mode
            if (!empty($_FILES['document']['name'])) {
                $file_name = $_FILES['document']['name'];
                $config = array(
                    'file_name' => $file_name,
                    'upload_path' => "./assets/images/document", // Path for document upload
                    'allowed_types' => "pdf",
                    'overwrite' => False,
                );
    
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = 'Error in uploading.';
                    echo $error; // Return upload error as plain text
                    return;
                }
    
                // File upload successful
                $path = $this->upload->data();
                $file_path =$path['file_name'];
            }
    
            // Prepare data for insertion or update
            $data = array(
                'name' => $docname,
                'file_path' => $file_path,
                'status' => 'Pending',
                'employee_id' => $this->session->userdata('user_login_id')
            );
    
            if ($document_id) {
                // Edit mode: Update existing document
                $this->DocumentModel->updateDocument($document_id, $data);
                echo 'Successfully Updated'; // Return update success message
            } else {
                // Add mode: Insert new document
                if (empty($docname) || empty($file_path)) {
                    echo 'All fields are required for uploading a document.'; // Prevent blank values
                    return;
                }
                $this->DocumentModel->saveDocument($data); // Insert new document
                echo 'Successfully Sent'; // Return add success message
            }
        } else {
            echo 'User not logged in.'; // Return user not logged in error
        }
    }
    public function addHR()
    {
        // Get selected employee code from the POST request
        $em_code = $this->input->post('emid');

        if (!empty($em_code)) {
            $this->load->model('DocumentModel');
            $updateData = ['hr_role' => 'HR']; 
            $this->DocumentModel->updateEmployeeRole($em_code, $updateData);
            echo 'HR role assigned successfully!';
            redirect(base_url(), 'refresh');
        } else {
            echo 'Please select an employee!';
            redirect(base_url(), 'refresh');
        }
    }

    // Handle edit document request
    public function requestEditDocument($id) {
        $document = $this->DocumentModel->getDocumentById($id, $this->session->userdata('employee_id'));

        if ($document && $document['status'] !== 'Pending') {
            $this->DocumentModel->sendEditRequest($id);
            $this->session->set_flashdata('success', 'Edit request sent to HR.');
        } else {
            $this->session->set_flashdata('error', 'You cannot edit this document at this stage.');
        }

        redirect('document/upload');
    }
    public function addDocumentTitle() {
        $this->load->model('DocumentModel');
            $data['document_titles'] = $this->DocumentModel->getDocumentTitles(); // Modify to use your model's method to get titles
            $this->load->view('backend/doctitle', $data);
    }
    
    public function addDocumentTitle1()
    {
        $this->load->model('DocumentModel');
        if ($this->input->post('document_title')) {
            // Get the document title from the form input
            $document_title = $this->input->post('document_title');
            
            // Insert the new document title into the database
            $this->DocumentModel->addTitle($document_title);

            // Set a flash message to indicate success
            echo 'Document Title added successfully!';
        }

        // Redirect to the document titles page
        redirect(base_url(), 'refresh');
    }
    public function DocumentView() {
        if ($this->session->userdata('user_login_access') != False) {
            // Fetch all documents and order by status: 'Pending' first
            $documents = $this->DocumentModel->getAllDocuments();
    
            // Fetch employee details for each document
            foreach ($documents as &$document) {
                $employee_details = $this->DocumentModel->getEmployeeDetails($document['employee_id']);
                $document['first_name'] = $employee_details['first_name'];
                $document['em_email'] = $employee_details['em_email'];
                $document['em_phone'] = $employee_details['em_phone'];
            }
    
            // Pass the documents data to the view
            $data['documents'] = $documents;
    
            // Load the view
            $this->load->view('backend/reviewdocuments', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    
    public function acceptDocument($id)
    {
        if ($this->session->userdata('user_login_access') != False) {
            $this->DocumentModel->updateDocumentStatus($id, 'Accepted');
            $this->session->set_flashdata('success', 'Document accepted.');
            redirect('document/DocumentView');
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    // Reject a document with reason
    public function denyDocument($id)
    {
        if ($this->session->userdata('user_login_access') != False) {
            $reason = $this->input->post('reason');
            if (empty($reason)) {
                $this->session->set_flashdata('error', 'Reason for rejection is required.');
                redirect(base_url(), 'refresh');
            }
            $this->DocumentModel->updateDocumentStatus($id, 'Rejected', $reason);
            $this->session->set_flashdata('success', 'Document rejected.');
            redirect(base_url(), 'refresh');
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    // Bulk Upload Documents
    public function bulkUpload() {
        if ($this->session->userdata('user_login_access') != False) {
            $this->load->library('csvimport');
            $csvFile = $_FILES['csv_file']['tmp_name'];
            
            if ($this->csvimport->get_array($csvFile)) {
                $data = $this->csvimport->get_array($csvFile);
                foreach ($data as $row) {
                    // Handle CSV data processing and save to database
                    $docData = array(
                        'name' => $row['Document Name'],
                        'file_path' => 'uploads/' . $row['File Path'],
                        'status' => 'Pending',
                        'employee_id' => $this->session->userdata('user_login_id')
                    );
                    $this->DocumentModel->saveDocument($docData);
                }
                $this->session->set_flashdata('success', 'Documents uploaded successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to upload documents. Please check the CSV format.');
            }
            redirect('document/pendingDocuments');
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    public function hruploadDocument()
{
    if ($this->session->userdata('user_login_access') != False) {
        // Load necessary models
        $this->load->model('DocumentModel');
        
        // Get form data
        $document_id = $this->input->post('id'); // Check if it's an edit request
        $emid = $this->input->post('emid'); // Employee ID
        $document_name = $this->input->post('name');
        
        // Check if it's an add or edit operation
        if ($document_id) {
            // Edit mode: Fetch existing document data
            $existing_document = $this->DocumentModel->getDocumentById($document_id);
            if (!$existing_document) {
                echo 'Document not found.'; // Return error if document doesn't exist
                return;
            }
        }
        
        // Validation Rules
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', ''); // Remove error delimiters

        $this->form_validation->set_rules('name', 'Document Name', 'trim|xss_clean', [
            'xss_clean' => 'Invalid input detected.'
        ]);
        
        // Check Validation
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo $errors; // Return validation errors as plain text
            return;
        }
        
        // Edit mode: Use old values if inputs are empty
        if ($document_id) {
            $document_name = !empty($document_name) ? $document_name : $existing_document->document_name;
        }

        // File upload logic: Retain old file URL if no new file is uploaded
        $file_path = $document_id ? $existing_document->document_file : null; // Default to old file URL in edit mode
        if (!empty($_FILES['document']['name'])) {
            $file_name = $_FILES['document']['name'];
            $config = array(
                'file_name' => time() . '_' . $file_name,
                'upload_path' => "./assets/images/hrdocuments", // Path for document upload
                'allowed_types' => "pdf",
                'overwrite' => False,
            );

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('document')) {
                $error = 'Error in uploading';
                echo $error; // Return upload error as plain text
                return;
            }

            // File upload successful
            $path = $this->upload->data();
            $file_path = 'assets/images/hrdocuments/' . $path['file_name'];
        }
        
        // Prepare data for insertion or update
        $data = array(
            'document_name' => $document_name,
            'document_file' => $file_path,
            'emid' => $emid,
            'uploaded_at' => date('Y-m-d H:i:s')
        );
        
        if ($document_id) {
            // Edit mode: Update existing document
            $this->DocumentModel->updateDocument($document_id, $data);
            echo 'Successfully Updated'; // Return update success message
        } else {
            // Add mode: Insert new document
            if (empty($document_name) || empty($file_path)) {
                echo 'All fields are required for uploading a document.'; // Prevent blank values
                return;
            }
            $this->DocumentModel->insertDocument($data); // Insert new document
            echo 'Successfully Sent'; // Return add success message
        }
    } else {
        echo 'User not logged in.'; // Return user not logged in error
    }
}
public function status() {
    // Load the document model
    $this->load->model('DocumentModel');

    // Get the employee ID from the session
    $employeeId = $this->session->userdata('user_login_id');

    // Fetch the document details for the employee
    $data['documents'] = $this->DocumentModel->getDocumentByEmpId($employeeId);
    

    // Pass the data to the view
    $this->load->view('backend/status_view', $data);
}
public function hrdoctitle()
    {
        $data['hr_document_titles'] = $this->DocumentModel->getHrDocumentTitles();
        $this->load->view('backend/hr_document_titles', $data); // Load the view with the data
    }
    public function addHrDocumentTitle()
    {
        if ($this->input->post('document_title')) {
            $document_title = $this->input->post('document_title');
            $this->DocumentModel->addHrDocumentTitle($document_title);
            echo 'HR Document Title added successfully!';
        }
        redirect(base_url(), 'refresh');
    }
    public function editHrDocumentTitle($id)
    {
        if ($this->input->post('document_title')) {
            $document_title = $this->input->post('document_title');
            $this->DocumentModel->editHrDocumentTitle($id, $document_title);
            echo 'HR Document Title updated successfully!';
        }
        redirect(base_url(), 'refresh');
    }
    public function deleteHrDocumentTitle($id)
{
    // Validate the ID (ensure it's numeric and positive)
    $this->DocumentModel->deleteHrDocumentTitle($id);
    redirect('document/hrdoctitle');
}


}

?>    