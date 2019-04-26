<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Online_exam_user_status_m extends MY_Model {

    protected $_table_name = 'online_exam_user_status';
    protected $_primary_key = 'onlineExamUserStatus';
    protected $_primary_filter = 'intval';
    protected $_order_by = "totalObtainedMark desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_online_exam_user_status($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_online_exam_user_status($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_online_exam_user_status($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_online_exam_user_status($array) 
    {
        $id = parent::insert($array);
        return $id;
    }

    public function update_online_exam_user_status($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_online_exam_user_status($id) 
    {
        parent::delete($id);
    }
}
