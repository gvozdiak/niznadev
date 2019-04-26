<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentextend_m extends MY_Model {

    protected $_table_name = 'studentextend';
    protected $_primary_key = 'studentextendID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "studentextendID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_studentextend($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_studentextend($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_studentextend($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_studentextend($array) 
    {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_studentextend($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function update_studentextend_by_studentID($data, $id = NULL) 
    {
        $this->db->update($this->_table_name, $data, "studentID = $id");
        return $id;
    }

    public function delete_studentextend($id)
    {
        parent::delete($id);
    }

    public function delete_studentextend_by_studentID($id) 
    {
        $this->db->where('studentID', $id);
        $this->db->delete($this->_table_name); 
        return TRUE;
    }
}