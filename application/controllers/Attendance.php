<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model');
        $this->load->model('employee_model');
        $this->load->model('loan_model');
        $this->load->model('settings_model');
        $this->load->model('leave_model');
        $this->load->model('attendance_model');
        $this->load->model('project_model');
        $this->load->library('csvimport');
    }
    
    public function Attendance()
    {
        if ($this->session->userdata('user_login_access') != False) {
            #$data['employee'] = $this->employee_model->emselect();
            $data['attendancelist'] = $this->attendance_model->getAllAttendance();
            $this->load->view('backend/attendance', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function Save_Attendance()
    {
        if ($this->session->userdata('user_login_access') != False) {
            $data['employee'] = $this->employee_model->emselect();
            $id               = $this->input->get('A');
            if (!empty($id)) {
                $data['attval'] = $this->attendance_model->em_attendanceFor($id);
            }
            #$data['attendancelist'] = $this->attendance_model->em_attendance();
            $this->load->view('backend/add_attendance', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    public function Attendance_Report()
    {
        if ($this->session->userdata('user_login_access') != False) {
            
            $data['employee'] = $this->employee_model->emselect();
            $id               = $this->input->get('A');
            if (!empty($id)) {
                $data['attval'] = $this->attendance_model->em_attendanceFor($id);
            }
            
            $this->load->view('backend/attendance_report', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function getPINFromID($employee_ID) {
        return $this->attendance_model->getPINFromID($employee_ID);
    }
    
    public function Get_attendance_data_for_report()
    {
        if ($this->session->userdata('user_login_access') != False) {
            $date_from   = $this->input->post('date_from');
            $date_to   = $this->input->post('date_to');
            $employee_id   = $this->input->post('employee_id');
            $employee_PIN = $this->getPINFromID($employee_id)->em_code;
            $attendance_data = $this->attendance_model->getAttendanceDataByID($employee_PIN, $date_from, $date_to);
            $data['attendance'] = $attendance_data;
            $attendance_hours = $this->attendance_model->getTotalAttendanceDataByID($employee_PIN, $date_from, $date_to);
            if(!empty($attendance_data)){
            $data['name'] = $attendance_data[0]->name;
            $data['days'] = count($attendance_data);
            $data['hours'] = $attendance_hours;                
            }
            echo json_encode($data);

            /*foreach ($attendance_data as $row) {
                $row =  
                    "<tr>
                        <td>$numbering</td>
                        <td>$row->first_name $row->first_name</td>
                        <td>$row->atten_date</td>
                        <td>$row->signin_time</td>
                        <td>$row->signout_time</td>
                        <td>$row->working_hour</td>
                        <td>Type</td>
                    </tr>";
            }*/
            
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    public function Add_Attendance()
{
    if ($this->session->userdata('user_login_access') != False) {
        $id      = $this->input->post('id');
        $em_id   = $this->input->post('emid');
        $attdate = $this->input->post('attdate');
        $signin  = $this->input->post('signin');
        $signout = $this->input->post('signout');
        $place = $this->input->post('place');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('attdate', 'Date details', 'trim|required|xss_clean');
        $this->form_validation->set_rules('emid', 'Employee', 'trim|required|xss_clean');
        
        $old_date           = $attdate;
        $old_date_timestamp = strtotime($old_date);
        $new_date           = date('m/d/Y', $old_date_timestamp);
        $new_date_changed   = date('Y-m-d', strtotime(str_replace('-', '/', $new_date)));
        
        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
        } else {
            $sin  = new DateTime($new_date . $signin);
            $sout = new DateTime($new_date . $signout);
            $hour = $sin->diff($sout);
            $work = $hour->format('%H h %i m');
            
            if (empty($id)) {
                $day = date("D", strtotime($new_date_changed));
                if ($day == "Fri") {
                    $duplicate = $this->attendance_model->getDuplicateVal($em_id, $new_date_changed);
                    if (!empty($duplicate)) {
                        echo "Already Exist";
                    } else {
                        $emcode = $this->employee_model->emselectByCode($em_id);
                        $emid = $emcode->em_id;
                        $earnval = $this->leave_model->emEarnselectByLeave($emid);
                        $data = array(
                            'present_date' => $earnval->present_date + 1,
                            'hour' => $earnval->hour + 480,
                            'status' => '1'
                        );
                        $success = $this->leave_model->UpdteEarnValue($emid, $data);
                        $data = array(
                            'emp_id' => $em_id,
                            'atten_date' => $new_date_changed,
                            'signin_time' => $signin,
                            'signout_time' => $signout,
                            'working_hour' => $work,
                            'place' => $place,
                            'status' => 'E'
                        );
                        $this->attendance_model->Add_AttendanceData($data);
                        echo "Successfully added.";
                    }
                } else {
                    $holiday = $this->leave_model->get_holiday_between_dates($new_date_changed);
                    if ($holiday) {
                        $duplicate = $this->attendance_model->getDuplicateVal($em_id, $new_date_changed);
                        if (!empty($duplicate)) {
                            echo "Already Exist";
                        } else {
                            $emcode = $this->employee_model->emselectByCode($em_id);
                            $emid = $emcode->em_id;
                            $earnval = $this->leave_model->emEarnselectByLeave($emid);
                            $data = array(
                                'present_date' => $earnval->present_date + 1,
                                'hour' => $earnval->hour + 480,
                                'status' => '1'
                            );
                            $this->leave_model->UpdteEarnValue($emid, $data);
                            $data = array(
                                'emp_id' => $em_id,
                                'atten_date' => $new_date_changed,
                                'signin_time' => $signin,
                                'signout_time' => $signout,
                                'working_hour' => $work,
                                'place' => $place,
                                'status' => 'E'
                            );
                            $this->attendance_model->Add_AttendanceData($data);
                            echo "Successfully added.";
                        }
                    } else {
                        $duplicate = $this->attendance_model->getDuplicateVal($em_id, $new_date_changed);
                        if (!empty($duplicate)) {
                            echo "Already Exist";
                        } else {
                            $data = array(
                                'emp_id' => $em_id,
                                'atten_date' => $new_date_changed,
                                'signin_time' => $signin,
                                'signout_time' => $signout,
                                'working_hour' => $work,
                                'place' => $place,
                                'status' => 'A'
                            );
                            $this->attendance_model->Add_AttendanceData($data);
                            echo "Successfully added.";
                        }
                    }
                }
            } else {
                $data = array(
                    'emp_id' => $em_id,  // Make sure emp_id is included when updating
                    'signin_time' => $signin,
                    'signout_time' => $signout,
                    'working_hour' => $work,
                    'place' => $place,
                    'status' => 'A'
                );
                $this->attendance_model->Update_AttendanceData($id, $data);
                echo "Successfully updated.";
            }
        }
    } else {
        redirect(base_url(), 'refresh');
    }
}

public function import()
{
    // Check if the file is uploaded
    if (empty($_FILES["csv_file"]["tmp_name"])) {
        echo "Error: No file uploaded. Please upload a valid CSV file.";
        return;
    }

    // Load the CSV import library
    $this->load->library('csvimport');
    
    // Check if the file is a valid CSV
    if (($fileData = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"])) === false) {
        echo "Error: The uploaded file is not a valid CSV file.";
        return;
    }

    // Ensure the required headers exist
    $requiredHeaders = ["Employee No", "Check-in at", "Check-out at", "Date"];
    $fileHeaders = array_keys($fileData[0]);  // Get the headers from the first row

    $missingHeaders = array_diff($requiredHeaders, $fileHeaders);
    if (!empty($missingHeaders)) {
        echo "Error: The following required columns are missing from the CSV file: " . implode(", ", $missingHeaders);
        return;
    }

    // Iterate through the CSV data and check for missing fields
    foreach ($fileData as $row) {
        // Check if any required fields are missing
        $missingFields = [];

        if (empty($row["Employee No"])) {
            $missingFields[] = 'Employee No';
        }
        if (empty($row["Check-in at"])) {
            $missingFields[] = 'Check-in at';
        }
        if (empty($row["Check-out at"])) {
            $missingFields[] = 'Check-out at';
        }
        if (empty($row["Date"])) {
            $missingFields[] = 'Date';
        }

        // If any required fields are missing, display an error and skip this entry
        if (!empty($missingFields)) {
            echo "Error: Missing fields (" . implode(", ", $missingFields) . ") for Employee No: " . $row["Employee No"] . ". Skipping this entry.<br>";
            continue;  // Skip this row and move to the next one
        }

        // If Check-in time is greater than '0:00:00', proceed with the processing
        if ($row["Check-in at"] > '0:00:00') {
            $date = date('Y-m-d', strtotime($row["Date"]));

            // Calculate working hours if 'Work Duration' is not provided
            if (empty($row["Work Duration"])) {
                $checkInTime = strtotime($row["Check-in at"]);
                $checkOutTime = strtotime($row["Check-out at"]);
                if ($checkInTime && $checkOutTime) {
                    $workingSeconds = $checkOutTime - $checkInTime; // Difference in seconds
                    $row["Work Duration"] = gmdate('H:i:s', $workingSeconds); // Convert seconds to H:i:s format
                } else {
                    $row["Work Duration"] = '00:00:00'; // Default if no valid time
                }
            }

            // Prepare data to update or insert
            $data = array(
                'signin_time' => $row["Check-in at"],
                'signout_time' => $row["Check-out at"],
                'working_hour' => $row["Work Duration"],
                'status' => 'A',
                'place' => 'office'
            );

            // Add absence and overtime if they exist
            if (!empty($row["Absence Duration"])) {
                $data['absence'] = $row["Absence Duration"];
            }
            if (!empty($row["Overtime Duration"])) {
                $data['overtime'] = $row["Overtime Duration"];
            }

            // Check if attendance record exists for the employee on the given date
            $duplicate = $this->attendance_model->getDuplicateVal($row["Employee No"], $date);

            if (!empty($duplicate)) {
                // Update existing record if duplicate found
                $this->attendance_model->bulk_Update($row["Employee No"], $date, $data);
            } else {
                // Insert new record if no duplicate found
                $data['emp_id'] = $row["Employee No"];
                $data['atten_date'] = $date;
                $this->attendance_model->Add_AttendanceData($data);
            }
        }
    }

    echo "Successfully Updated";
}




}
?>