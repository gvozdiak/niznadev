<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Themes_m extends MY_Model {

	protected $_table_name = 'themes';
	protected $_primary_key = 'themesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "sortID asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_themes($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_themes($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_themes($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_themes($array) 
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_themes($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_themes($id)
	{
		parent::delete($id);
	}

	public function hash($string) 
	{
		return parent::hash($string);
	}
}