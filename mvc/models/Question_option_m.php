<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_option_m extends MY_Model {

    protected $_table_name = 'question_option';
    protected $_primary_key = 'optionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "optionID asc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_question_option($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_question_option($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_question_option($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_where_in_question_option($array, $key=NULL) {
        $query = parent::get_where_in($array, $key);
        return $query;
    }

    public function insert_question_option($array) 
    {
        $id = parent::insert($array);
        return $id;
    }

    public function update_question_option($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_question_option($id)
    {
        parent::delete($id);
    }
}
