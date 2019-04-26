<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_m extends MY_Model {

	protected $_table_name = 'notice';
	protected $_primary_key = 'noticeID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "noticeID desc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_notice($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_notice($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_notice($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_notice($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_notice($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_notice($id){
		parent::delete($id);
	}
}