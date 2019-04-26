<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class teacher_m extends MY_Model {

	protected $_table_name = 'teacher';
	protected $_primary_key = 'teacherID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "name asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_username($table, $data=NULL) 
	{
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}


	public function get_teacher($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_teacher($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_teacher($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_teacher($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_teacher($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_teacher($id)
	{
		parent::delete($id);
	}

	public function hash($string) 
	{
		return parent::hash($string);
	}
}