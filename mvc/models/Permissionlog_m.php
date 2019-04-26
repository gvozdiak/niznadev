<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissionlog_m extends MY_Model {

    protected $_table_name = 'permissions';
    protected $_primary_key = 'permissionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "permissionID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_permissionlog($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_permissionlog($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_permissionlog($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_permissionlog($array) 
    {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_permissionlog($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_permissionlog($id)
    {
        parent::delete($id);
    }
}
