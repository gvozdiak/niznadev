<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Classes_m.php';

class student_m extends MY_Model {

	protected $_table_name = 'student';
	protected $_primary_key = 'student.studentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "roll asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_username($table, $data=NULL) 
	{
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	public function get_single_username($table, $data=NULL) 
	{
		$query = $this->db->get_where($table, $data);
		return $query->row();
	}

	public function get_student($array=NULL, $signal=FALSE) 
	{
	    $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_student($array) 
	{
		$array = $this->makeArrayWithTableName($array);
        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_student($array=[]) 
	{
		$array = $this->makeArrayWithTableName($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get_order_by($array);
		return $query;
	}

	public function general_get_student($array=NULL, $signal=FALSE) 
	{
		$array = $this->makeArrayWithTableName($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_order_by_student($array=NULL) 
	{
		$array = $this->makeArrayWithTableName($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get_order_by($array);
		return $query;
	}

	public function general_get_single_student($array) 
	{
		$array = $this->makeArrayWithTableName($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_student($array) 
	{
		$id = parent::insert($array);
		return $id;
	}

	public function update_student($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_student($id)
	{
		parent::delete($id);
	}

	public function hash($string) 
	{
		return parent::hash($string);
	}

	public function profileUpdate($table, $data, $username) 
	{
		$this->db->update($table, $data, "username = '".$username."'");
		return TRUE;
	}

	public function profileRelationUpdate($table, $data, $studentID) 
	{
		$this->db->update($table, $data, "srstudentID = '".$studentID."'");
		return TRUE;
	}
}