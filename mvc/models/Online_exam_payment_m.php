<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Online_exam_payment_m extends MY_Model {

    protected $_table_name = 'online_exam_payment';
    protected $_primary_key = 'online_exam_paymentID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "online_exam_paymentID asc"; /* Dont change asc*/

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_online_exam_payment($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_online_exam_payment($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_online_exam_payment($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_online_exam_payment_only_first_row($array)
    {
        $this->db->select_min($this->_primary_key);
        $this->db->where($array);
        $query = $this->db->get($this->_table_name);
        return $query->row();
    }

    public function insert_online_exam_payment($array) 
    {
        $id = parent::insert($array);
        return $id;
    }

    public function update_online_exam_payment($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_online_exam_payment($id)
    {
        parent::delete($id);
    }
}
