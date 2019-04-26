<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classes_m extends MY_Model {

	protected $_table_name = 'classes';
	protected $_primary_key = 'classesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classes_numeric asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_join_classes() 
	{
		$this->db->select('*');
		$this->db->from('classes');
		$this->db->join('teacher', 'classes.teacherID = teacher.teacherID', 'LEFT');
		$this->db->order_by('classes_numeric asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function general_get_classes($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_order_by_classes($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_classes($id=NULL, $signal=false) 
	{
		$query = parent::get($id, $signal);
		return $query;
	}

	public function get_single_classes($array) 
	{
        $query = parent::get_single($array);
        return $query;
    }

	public function get_order_by_classes($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_classes($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_classes($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_classes($id) 
	{
		parent::delete($id);
	}

	public function get_order_by_numeric_classes() 
	{
		$this->db->select('*')->from('classes')->order_by('classes_numeric asc');
		$query = $this->db->get();
		return $query->result();
	}
}