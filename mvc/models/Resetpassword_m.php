<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resetpassword_m extends MY_Model {
	
	public function __construct() 
	{
		parent::__construct();
	}

	public function get_username($table, $data=NULL) 
	{
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	public function hash($string) 
	{
		return parent::hash($string);
	}	

	public function update_resetpassword($table, $data=NULL, $tableID, $userID ) 
	{
		$this->db->update($table, $data, $tableID." = ". $userID);
		return TRUE;
	}
}