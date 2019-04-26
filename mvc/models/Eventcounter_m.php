<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eventcounter_m extends MY_Model {

	protected $_table_name = 'eventcounter';
	protected $_primary_key = 'eventcounterID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "name asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_eventcounter($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_eventcounter($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_eventcounter($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_eventcounter($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_eventcounter($id)
	{
		parent::delete($id);
	}
}