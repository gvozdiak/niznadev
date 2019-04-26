<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parents_m extends MY_Model {

	protected $_table_name = 'parents';
	protected $_primary_key = 'parentsID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "parentsID asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_username($table, $data=NULL) 
	{
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	public function get_parents($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_parents($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_parents($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_parents($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_parents($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function update_student($data, $id = NULL) 
	{
		$this->db->where('studentID', $id);
		$this->db->update('student', $data);
		return TRUE;
	}

	public function delete_parents($id)
	{
		parent::delete($id);
		return TRUE;
	}

	public function hash($string) 
	{
		return parent::hash($string);
	}
}