<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	protected $_memoryLimit = '1024M';
	
	function __construct() {
		parent::__construct();
		$language = $this->session->userdata('lang');
		$this->lang->load('backup', $language);
	}

	public function index() {
		ini_set('memory_limit', $this->_memoryLimit);
		if ($_POST) {
			if(config_item('demo') == FALSE) {
				$this->load->dbutil();
				$prefs = array(
					'format'        => 'zip',
	            	'filename'    => 'mybackup.sql'
	          	);
				$backup =& $this->dbutil->backup($prefs);
				$this->load->helper('download');
				force_download('mybackup.zip', $backup);
				redirect(base_url('backup/index'));
			} else {
				$this->session->set_flashdata('error', 'In demo backup module is disable!');
				redirect(base_url('backup/index'));
			}
		} else {
		    $this->data["subview"] = "backup/index";
		    $this->load->view('_layout_main', $this->data);
		}
	}
}

/* End of file backup.php */
/* Location: .//var/www/html/schoolv2/mvc/controllers/backup.php */
