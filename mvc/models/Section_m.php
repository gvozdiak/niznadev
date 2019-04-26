<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Section_m extends MY_Model {

	protected $_table_name = 'section';
	protected $_primary_key = 'sectionID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "sectionID asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_join_section($id) 
	{
		$this->db->select('*');
		$this->db->from('section');
		$this->db->join('teacher', 'section.teacherID = teacher.teacherID', 'LEFT');
		$this->db->where('section.classesID', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_single_section($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_section($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_section($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_section($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_section($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_section($id)
	{
		parent::delete($id);
	}
}