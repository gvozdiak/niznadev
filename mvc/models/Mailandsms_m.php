<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailandsms_m extends MY_Model {

	protected $_table_name = 'mailandsms';
	protected $_primary_key = 'mailandsmsID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "mailandsmsID desc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_mailandsms($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_mailandsms_with_usertypeID() 
	{
		$this->db->select('*');
		$this->db->from('mailandsms');
		$this->db->join('usertype', 'usertype.usertypeID = mailandsms.usertypeID', 'LEFT');
		$this->db->order_by("mailandsmsID",'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_order_by_mailandsms($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_mailandsms($array=NULL) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_mailandsms($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_mailandsms($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_mailandsms($id)
	{
		parent::delete($id);
	}
}