<?php

class Payroll_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    public function Add_typeInfo($data){
        $this->db->insert('salary_type', $data);
    }

    public function insert_Salary_Pay($data){
        $this->db->insert('pay_salary', $data);
    }

    public function GetsalaryType(){
        $sql = "SELECT * FROM `salary_type` ORDER BY `salary_type` ASC";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;         
    }

    public function GetBankInfo($eid){
        $sql = "SELECT * FROM `bank_info` WHERE `em_id`='$eid'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;         
    }

    public function GetDepEmployee($depid){
        $sql = "SELECT `employee`.*, `emp_salary`.`total`
                FROM `employee`
                LEFT JOIN `emp_salary` ON `employee`.`em_id`=`emp_salary`.`emp_id`
                WHERE `employee`.`dep_id`='$depid'";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;         
    }

    public function Get_typeValue($id){
        $sql = "SELECT * FROM `salary_type` WHERE `salary_type`.`id`= '$id'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;         
    }

    public function GetLoanValueByID($id){
        $sql = "SELECT * FROM `loan` WHERE `loan`.`emp_id`= '$id' AND `status`='Granted' AND `status` != 'Done'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;         
    }

    public function hasLoanOrNot($id){
        $sql = "SELECT * FROM `loan` WHERE `loan`.`emp_id`= '$id' AND `status`='Granted' AND `status` != 'Done'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result ? 1 : 0;    
    }

    public function GetHolidayByYear($dateval){
        $sql = "SELECT * FROM `holiday` WHERE `holiday`.`year`= '$dateval'";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;         
    }

    public function GetloanInfo($emid){
        $sql = "SELECT * FROM `loan` WHERE `loan`.`emp_id`= '$emid'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;         
    }

    public function Update_typeInfo($id, $data){
        $this->db->where('id', $id);
        $this->db->update('salary_type', $data);        
    }

    public function Update_SalaryPayInfo($id, $data){
        $this->db->where('pay_id', $id);
        $this->db->update('pay_salary', $data);        
    }

    public function Get_Salary_Value($id){
        $sql = "SELECT `emp_salary`.*, `addition`.*, `deduction`.*
                FROM `emp_salary`
                LEFT JOIN `addition` ON `emp_salary`.`id`=`addition`.`salary_id`
                LEFT JOIN `deduction` ON `emp_salary`.`id`=`deduction`.`salary_id`
                WHERE `emp_salary`.`emp_id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
        return $result;        
    }

    public function Get_Salarypay_Value($id){
        $sql = "SELECT `pay_salary`.*, `employee`.`em_id`, `first_name`, `last_name`, `salary_type`.*
                FROM `pay_salary`
                LEFT JOIN `employee` ON `pay_salary`.`emp_id`=`employee`.`em_id`
                LEFT JOIN `salary_type` ON `pay_salary`.`type_id`=`salary_type`.`id`
                WHERE `pay_salary`.`pay_id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
        return $result;        
    }

    public function getAllSalaryDataByMonthYearEm($eid, $month, $year){
        $sql = "SELECT `pay_salary`.*, `employee`.`em_id`, `first_name`, `last_name`, `salary_type`.*
                FROM `pay_salary`
                LEFT JOIN `employee` ON `pay_salary`.`emp_id`=`employee`.`em_id`
                LEFT JOIN `salary_type` ON `pay_salary`.`type_id`=`salary_type`.`id`
                WHERE `pay_salary`.`emp_id`='$eid' AND `pay_salary`.`month`='$month' AND `pay_salary`.`year`='$year'";
        $query=$this->db->query($sql);
        $result = $query->row();
        return $result;        
    }

    public function GetsalaryValueEm(){
        $sql = "SELECT `emp_salary`.*, `addition`.*, `deduction`.*, `salary_type`.`salary_type`, `employee`.`first_name`, `last_name`, `em_id`
                FROM `emp_salary`
                LEFT JOIN `salary_type` ON `emp_salary`.`type_id`=`salary_type`.`id`
                LEFT JOIN `addition` ON `emp_salary`.`id`=`addition`.`salary_id`
                LEFT JOIN `deduction` ON `emp_salary`.`id`=`deduction`.`salary_id`
                LEFT JOIN `employee` ON `emp_salary`.`emp_id`=`employee`.`em_id`
                ORDER BY `emp_salary`.`id` DESC";
        $query=$this->db->query($sql);
        $result = $query->result();
        return $result;        
    }

    public function GetAllSalary(){
        $sql = "SELECT `pay_salary`.*, `employee`.`first_name`, `last_name`, `em_code`, `salary_type`.`salary_type`
                FROM `pay_salary`
                LEFT JOIN `employee` ON `pay_salary`.`emp_id`=`employee`.`em_id`
                LEFT JOIN `salary_type` ON `pay_salary`.`type_id`=`salary_type`.`id`
                ORDER BY `pay_salary`.`pay_id` DESC";
        $query=$this->db->query($sql);
        $result = $query->result();
        return $result;        
    }

    public function getAllSalaryID($id){
        $sql = "SELECT *
                FROM `pay_salary` WHERE `emp_id` = '$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
        return $result;        
    }

    public function getEmployeeID($id){
        $sql = "SELECT `employee`.*, `designation`.*, `department`.*
                FROM `employee`
                LEFT JOIN `designation` ON `employee`.`des_id`=`designation`.`id`
                LEFT JOIN `department` ON `employee`.`dep_id`=`department`.`id`
                WHERE `em_id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
        return $result;         
    }

    public function Get_SalaryID($id){
        $sql = "SELECT `emp_salary`.*, `addition`.*, `deduction`.*
                FROM `emp_salary`
                LEFT JOIN `addition` ON `emp_salary`.`id`=`addition`.`salary_id`
                LEFT JOIN `deduction` ON `emp_salary`.`id`=`deduction`.`salary_id`
                WHERE `emp_salary`.`emp_id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
        return $result;        
    }

    public function getAllSalaryData(){
        $sql = "SELECT `pay_salary`.*, `employee`.`first_name`, `last_name`, `em_code`
                FROM `pay_salary`
                LEFT JOIN `employee` ON `pay_salary`.`emp_id`=`employee`.`em_id`
                ORDER BY `pay_salary`.`month` DESC";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function getAllSalaryDataById($id){
        $sql = "SELECT `pay_salary`.*, `employee`.`first_name`, `last_name`, `em_code`
                FROM `pay_salary`
                LEFT JOIN `employee` ON `pay_salary`.`emp_id`=`employee`.`em_id`
                WHERE `pay_salary`.pay_id = '$id'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }

    public function getAdditionDataBySalaryID($salaryID){
        $sql = "SELECT `addition`.*
                FROM `addition`
                WHERE `addition`.salary_id = '$salaryID'";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function getDiductionDataBySalaryID($salaryID){
        $sql = "SELECT `deduction`.*
                FROM `deduction`
                WHERE `deduction`.salary_id = '$salaryID'";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }}