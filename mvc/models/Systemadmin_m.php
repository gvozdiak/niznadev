<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class systemadmin_m extends MY_Model {

	protected $_table_name = 'systemadmin';
	protected $_primary_key = 'systemadminID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "systemadminID";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_systemadmin_by_usertype($systemadminID = null) 
	{
		$this->db->select('*');
		$this->db->from('systemadmin');
		$this->db->join('usertype', 'usertype.usertypeID = systemadmin.usertypeID', 'LEFT');
		if($systemadminID) {
			$this->db->where(array('systemadminID' => $systemadminID));
			$query = $this->db->get();
			return $query->row();
		} else {
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_username($table, $data=NULL) 
	{
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	public function get_systemadmin($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_systemadmin($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_systemadmin($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_systemadmin($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_systemadmin($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_systemadmin($id)
	{
		parent::delete($id);
	}

	public function hash($string) 
	{
		return parent::hash($string);
	}	
}