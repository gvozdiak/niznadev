<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class usertype_m extends MY_Model {

	protected $_table_name = 'usertype';
	protected $_primary_key = 'usertypeID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "usertypeID desc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_usertype($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_usertype($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_usertype($array=NULL) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_usertype($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_usertype($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_usertype($id){
		parent::delete($id);
	}
}