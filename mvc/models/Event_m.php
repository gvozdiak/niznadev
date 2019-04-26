<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_m extends MY_Model {

	protected $_table_name = 'event';
	protected $_primary_key = 'eventID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "fdate desc,ftime asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_event($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_event($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_event($array) 
	{
        $query = parent::get_single($array);
        return $query;
    }

	public function insert_event($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_event($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_event($id){
		parent::delete($id);
	}
}