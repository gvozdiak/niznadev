<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paymentsettings_m extends MY_Model {

	protected $_table_name = 'paymentsetting';
	protected $_primary_key = 'paymentsettingID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "paymentsettingID asc";

	public function update_key($array) 
	{
		$this->db->update_batch('paymentsetting', $array, 'fieldoption'); 
	}

	public function get_order_by_config() 
	{
		$query = $this->db->get_where($this->_table_name);
		return $query->result();
	}

	public function get_order_by_paymentsetting($id = NULL, $single = FALSE) 
	{
		$query = parent::get($id, $single);
		return $query;
	}

	public function get_paymentsetting() 
	{
		$compress = [];
		$query = $this->db->get($this->_table_name);
		foreach ($query->result() as $row) {
		    $compress[$row->fieldoption] = $row->value;
		}
		return (object) $compress;
	}
}