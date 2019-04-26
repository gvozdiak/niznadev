<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolyear_m extends MY_Model {

	protected $_table_name = 'schoolyear';
	protected $_primary_key = 'schoolyearID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "schoolyear desc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_schoolyear($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_schoolyear($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_schoolyear($array=NULL) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_schoolyear($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_schoolyear($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_schoolyear($id){
		parent::delete($id);
	}

	public function get_schoolyear_where($schoolyear) 
	{
		$where = '(schoolyear="'.$schoolyear.'" AND (schoolyeartitle IS NULL OR schoolyeartitle = ""))';
		$this->db->select('*');
		$this->db->from('schoolyear');
       	$this->db->where($where);
       	$query = $this->db->get();
       	return $query->result();

	}

	public function get_schoolyear_where_not($schoolyear, $id) 
	{
		$where = '(schoolyear="'.$schoolyear.'" AND schoolyearID !="'.$id.'" AND (schoolyeartitle IS NULL OR schoolyeartitle = ""))';
		$this->db->select('*');
		$this->db->from('schoolyear');
       	$this->db->where($where);
       	$query = $this->db->get();
       	return $query->result();
	}
}