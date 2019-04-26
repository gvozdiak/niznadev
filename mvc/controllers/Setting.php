<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class setting extends Admin_Controller {
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
	function __construct() {
		parent::__construct();
		$this->load->model("setting_m");
		$this->load->model("schoolyear_m");
		$this->load->model('themes_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('setting', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'sname',
				'label' => $this->lang->line("setting_name"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("setting_phone"),
				'rules' => 'trim|required|xss_clean|max_length[25]'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("setting_email"),
				'rules' => 'trim|required|valid_email|max_length[40]|xss_clean'
			),
			array(
				'field' => 'note',
				'label' => $this->lang->line("setting_note"),
				'rules' => 'trim|required|max_length[5]|xss_clean'
			),
			array(
				'field' => 'google_analytics',
				'label' => $this->lang->line("setting_google_analytics"),
				'rules' => 'trim|max_length[50]|xss_clean'
			),
			array(
				'field' => 'currency_code',
				'label' => $this->lang->line("setting_currency_code"),
				'rules' => 'trim|required|max_length[11]|xss_clean'
			),
			array(
				'field' => 'currency_symbol',
				'label' => $this->lang->line("setting_currency_symbol"),
				'rules' => 'trim|required|max_length[3]|xss_clean'
			),
			array(
				'field' => 'footer',
				'label' => $this->lang->line("setting_footer"),
				'rules' => 'trim|required|max_length[200]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("setting_address"),
				'rules' => 'trim|required|max_length[200]|xss_clean'
			),
			array(
				'field' => 'language',
				'label' => $this->lang->line("setting_lang"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("setting_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			array(
				'field' => 'captcha_status',
				'label' => $this->lang->line("setting_disable_captcha"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'language_status',
				'label' => $this->lang->line("setting_disable_language"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'profile_edit',
				'label' => $this->lang->line("setting_profile_edit"),
				'rules' => 'trim|required|xss_clean|numeric'
			),
			array(
				'field' => 'time_zone',
				'label' => $this->lang->line("setting_time_zone"),
				'rules' => 'trim|required|xss_clean|callback_unique_time_zone'
			),
			array(
				'field' => 'auto_update_notification',
				'label' => $this->lang->line("setting_auto_update_notification"),
				'rules' => 'trim|required|xss_clean'
			)
		);

		if($this->input->post('captcha_status') == FALSE) {
			$rules[] = array(
				'field' => 'recaptcha_site_key',
				'label' => $this->lang->line("setting_recaptcha_site_key"),
				'rules' => 'trim|required|xss_clean|max_length[255]'
			);

			$rules[] = array(
				'field' => 'recaptcha_secret_key',
				'label' => $this->lang->line("setting_recaptcha_secret_key"),
				'rules' => 'trim|required|xss_clean|max_length[255]'
			);
		}

		return $rules;
	}

	public function photoupload() {
		$setting = $this->setting_m->get_setting(1);	
		$new_file = "site.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = rand(1, 10000000000000000);
	    	$makeRandom = hash('sha512', $random.config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name'] = $new_file;
				$config['max_size'] = '1024';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("photo")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
	     		return FALSE;
			}
		} else {
			if(count($setting)) {
				$this->upload_data['file'] = array('file_name' => $setting->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		
		$usertype = $this->session->userdata("usertype");
		$this->data['setting'] = $this->setting_m->get_setting(1);
		$this->data['settingarray'] = $this->setting_m->get_setting_array();
		$this->data['themes'] = $this->themes_m->get_order_by_themes(array('backend' => 1));

		if($this->data['setting']) {
			if($_POST) {
				$this->data['captcha_status'] = $this->input->post('captcha_status');
				
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "setting/index";
					$this->load->view('_layout_main', $this->data);
				} else {
					if($this->scode($this->data['setting']->purchase_code, $this->data['setting']->purchase_username, config_item('ini_version')) == FALSE) {
						redirect(base_url('setting/index'));
					}

					$array = [];
					for($i=0; $i<count($rules); $i++) {
						if($this->input->post($rules[$i]['field']) == false) {
							$array[$rules[$i]['field']] = 0;
						} else {
							$array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
						}
					}

					$array['google_analytics'] = $this->input->post('google_analytics');
					if(isset($array['language'])) {
						$this->session->set_userdata('lang',$array['language']);
					}

					$array['photo'] = $this->upload_data['file']['file_name'];
					
					$this->setting_m->insertorupdate($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("setting/index"));
				}
			} else {
				$this->data['captcha_status'] = $this->data['setting']->captcha_status;
				$this->data["subview"] = "setting/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function unique_time_zone($data) {
		$timezone = $this->input->post('time_zone');
		if($timezone == 'none') {
			$this->form_validation->set_message('unique_time_zone', 'The %s field is required.');
			return FALSE;
		} else {
			if(isset($this->data['settingarray']['time_zone']) && ($this->data['settingarray']['time_zone'] != $this->input->post('time_zone'))) {
				$timeZone = $this->input->post('time_zone');
				$indexPath = getcwd()."/index.php";
				@chmod($indexPath, 0777);
				$filecontent = "date_default_timezone_set('". $timeZone ."');";
				$fileArray = array(2 => $filecontent);
				$this->replace_lines($indexPath, $fileArray);
				@chmod($indexPath, 0644);
			}
			return TRUE;
		}
		return TRUE;
	}

	private function replace_lines($file, $new_lines, $source_file = NULL) {
        $response = 0;
        $tab = chr(9);
        $lbreak = chr(13) . chr(10);
        if ($source_file) {
            $lines = file($source_file);
        }
        else {
            $lines = file($file);
        }
        foreach ($new_lines as $key => $value) {
            $lines[--$key] = $tab . $value . $lbreak;
        }
        $new_content = implode('', $lines);
        if ($h = fopen($file, 'w')) {
            if (fwrite($h, $new_content)) {
                $response = 1;
            }
            fclose($h);
        }
        return $response;
    }

	public function scode($pcode, $pusername, $version) {
		$email = trim($this->input->post('email'));
        $apiCurl = actionVarifyValidUser($email);
		if($apiCurl->status == FALSE) {
			$this->session->set_flashdata('error', $apiCurl->message);
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function backendtheme() {
		$themesID = htmlentities(escapeString($this->input->post('id')));
		$themeName = 'default';
		if((int) $themesID) {
			$theme = $this->themes_m->get_single_themes(array('themesID' => $themesID));
			if(count($theme)) {
				$themeName = strtolower(str_replace(' ', '', $theme->themename));
			}
		}

		$this->setting_m->update_setting('backend_theme', $themeName);
		echo $themeName;
	}
}