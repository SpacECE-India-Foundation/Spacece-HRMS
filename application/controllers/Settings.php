<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model'); 
        $this->load->model('project_model'); 
        $this->load->model('settings_model'); 
        $this->load->model('leave_model'); 
    }

    public function index(){
        #Redirect to Admin dashboard after authentication
        if ($this->session->userdata('user_login_access') == 1)
            redirect('dashboard/Dashboard');
        $this->load->view('login');        
    }

    public function Settings(){
        if($this->session->userdata('user_login_access') != False) { 
            $data['settingsvalue'] = $this->settings_model->GetSettingsValue();
            $this->load->view('backend/settings', $data);
        } else {
            redirect(base_url(), 'refresh');
        }            
    }

    public function Add_Settings() {
        if ($this->session->userdata('user_login_access') != False) {
            $id = $this->input->post('id');
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $copyright = $this->input->post('copyright');
            $contact = $this->input->post('contact');
            $currency = $this->input->post('currency');
            $symbol = $this->input->post('symbol');
            $email = $this->input->post('email');
            $address = $this->input->post('address');
            $address2 = $this->input->post('address2');
            
            // Load form validation library
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters();
            
            // Validating fields
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[5]|max_length[60]|xss_clean');
            $this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[20]|max_length[512]|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|min_length[5]|max_length[600]|xss_clean');
            $this->form_validation->set_rules('address2', 'Address 2', 'trim|min_length[5]|max_length[600]|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('symbol', 'Symbol', 'trim|required|min_length[1]|max_length[10]|xss_clean');
    
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                // Handle first image upload
                if ($_FILES['img_url']['name']) {
                    $settings = $this->settings_model->GetSettingsValue();
                    $file_name = $_FILES['img_url']['name'];
                    $checkimage = "./assets/images/$settings->sitelogo";
                    
                    $config = array(
                        'file_name' => $file_name,
                        'upload_path' => "./assets/images/",
                        'allowed_types' => "gif|jpg|png|jpeg|svg",
                        'overwrite' => False,
                        'max_size' => "13038", // Max size 13MB
                        'max_height' => "850",
                        'max_width' => "850"
                    );
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    
                    if (!$this->upload->do_upload('img_url')) {
                        $error_message = "The file you uploaded is not allowed. Please upload one of the following file types: gif, jpg, png, jpeg, svg. ";
                        echo $error_message;
                    } else {
                        if (file_exists($checkimage)) {
                            unlink($checkimage); // Remove old image if exists
                        }
                        $path = $this->upload->data();
                        $img_url = $path['file_name'];
                    }
                }
    
                // Handle second image upload
                if ($_FILES['img_url2']['name']) {
                    $settings = $this->settings_model->GetSettingsValue();
                    $file_name = $_FILES['img_url2']['name'];
                    $checkimage = "./assets/images/$settings->site2logo"; // Update check path for second image
                    
                    $config = array(
                        'file_name' => $file_name,
                        'upload_path' => "./assets/images/",
                        'allowed_types' => "gif|jpg|png|jpeg|svg",
                        'overwrite' => False,
                        'max_size' => "13038", // Max size 13MB
                        'max_height' => "850",
                        'max_width' => "850"
                    );
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    
                    if (!$this->upload->do_upload('img_url2')) {
                        $error_message = "The file you uploaded is not allowed. Please upload one of the following file types: gif, jpg, png, jpeg, svg. ";
                        echo $error_message;
                    } else {
                        // Delete the old second image if exists
                        if (file_exists($checkimage)) {
                            unlink($checkimage);
                        }
                        $path = $this->upload->data();
                        $img_url2 = $path['file_name'];
                    }
                }
    
                // Prepare data for updating settings
                $data = array(
                    'sitelogo' => isset($img_url) ? $img_url : $settings->sitelogo,
                    'site2logo' => isset($img_url2) ? $img_url2 : $settings->site2logo,
                    'sitetitle' => $title,
                    'description' => $description,
                    'copyright' => $copyright,
                    'contact' => $contact,
                    'currency' => $currency,
                    'symbol' => $symbol,
                    'system_email' => $email,
                    'address' => $address,
                    'address2' => $address2
                );
                
                // Update settings in the database
                $success = $this->settings_model->SettingsUpdate($id, $data);
                echo 'Successfully Updated';
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    
}
