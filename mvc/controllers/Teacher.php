<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher extends Admin_Controller {
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
		$this->load->model("teacher_m");
		$this->load->model("document_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('teacher', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("teacher_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'designation',
				'label' => $this->lang->line("teacher_designation"),
				'rules' => 'trim|required|max_length[128]|xss_clean'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("teacher_dob"),
				'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("teacher_sex"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'religion',
				'label' => $this->lang->line("teacher_religion"),
				'rules' => 'trim|max_length[25]|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("teacher_email"),
				'rules' => 'trim|required|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("teacher_phone"),
				'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("teacher_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'jod',
				'label' => $this->lang->line("teacher_jod"),
				'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("teacher_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			array(
				'field' => 'username',
				'label' => $this->lang->line("teacher_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("teacher_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
			)
		);
		return $rules;
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("teacher_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("teacher_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("teacher_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'teacherID',
				'label' => $this->lang->line("teacher_teacherID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

	public function unique_data($data) {
		if($data != '') {
			if($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function photoupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$user = array();
		if((int)$id) {
			$user = $this->teacher_m->get_teacher($id);
		}

		$new_file = "defualt.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = rand(1, 10000000000000000);
	    	$makeRandom = hash('sha512', $random.$this->input->post('username') . config_item("encryption_key"));
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
			if(count($user)) {
				$this->upload_data['file'] = array('file_name' => $user->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
			return TRUE;
			}
		}
	}

	public function index() {
		$this->data['teachers'] = $this->teacher_m->get_teacher();
		$this->data["subview"] = "teacher/index";
		$this->load->view('_layout_main', $this->data);

	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js'
			)
		);
		if($_POST) {
			if($this->tcode($this->data['siteinfos']->purchase_code, $this->data['siteinfos']->purchase_username, config_item('ini_version')) == FALSE) {
				redirect(base_url('teacher/add'));
			}

			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->data["subview"] = "teacher/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array();
				$array['name'] = $this->input->post("name");
				$array['designation'] = $this->input->post("designation");
				$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
				$array["sex"] = $this->input->post("sex");
				$array['religion'] = $this->input->post("religion");
				$array['email'] = $this->input->post("email");
				$array['phone'] = $this->input->post("phone");
				$array['address'] = $this->input->post("address");
				$array['jod'] = date("Y-m-d", strtotime($this->input->post("jod")));
				$array['username'] = $this->input->post("username");
				$array['password'] = $this->teacher_m->hash($this->input->post("password"));
				$array['usertypeID'] = 2;
				$array["create_date"] = date("Y-m-d h:i:s");
				$array["modify_date"] = date("Y-m-d h:i:s");
				$array["create_userID"] = $this->session->userdata('loginuserID');
				$array["create_username"] = $this->session->userdata('username');
				$array["create_usertype"] = $this->session->userdata('usertype');
				$array["active"] = 1;
				$array['photo'] = $this->upload_data['file']['file_name'];

				$this->teacher_m->insert_teacher($array);
				$this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("teacher/index"));
			}
		} else {
			$this->data["subview"] = "teacher/add";
			$this->load->view('_layout_main', $this->data);
		}

	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js'
			)
		);
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['teacher'] = $this->teacher_m->get_teacher($id);
			if($this->data['teacher']) {
				if($_POST) {
					$rules = $this->rules();
					unset($rules[11]);
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "teacher/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = array();
						$array['name'] = $this->input->post("name");
						$array['designation'] = $this->input->post("designation");
						$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
						$array["sex"] = $this->input->post("sex");
						$array['religion'] = $this->input->post("religion");
						$array['email'] = $this->input->post("email");
						$array['phone'] = $this->input->post("phone");
						$array['address'] = $this->input->post("address");
						$array['jod'] = date("Y-m-d", strtotime($this->input->post("jod")));
						$array['username'] = $this->input->post('username');
						$array["modify_date"] = date("Y-m-d h:i:s");
						$array['photo'] = $this->upload_data['file']['file_name'];

						$this->teacher_m->update_teacher($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("teacher/index"));

					}
				} else {
					$this->data["subview"] = "teacher/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['teacher'] = $this->teacher_m->get_teacher($id);
			if($this->data['teacher']) {
				if(config_item('demo') == FALSE) {
					if($this->data['teacher']->photo != 'defualt.png') {
	                    if($this->data['teacher']->photo != 'default.png' && $this->data['teacher']->photo != 'defualt.png') {
							unlink(FCPATH.'uploads/images/'.$this->data['teacher']->photo);
	                    }
					}
				}
				$this->teacher_m->delete_teacher($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("teacher/index"));
			} else {
				redirect(base_url("teacher/index"));
			}
		} else {
			redirect(base_url("teacher/index"));
		}
	}

	public function view() {
		$teacherID = htmlentities(escapeString($this->uri->segment(3)));
		$this->data['teacherID'] = $teacherID;
		if ((int)$teacherID) {
			$this->getView($teacherID);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	private function getView($teacherID) {
		if((int)$teacherID) {
			$teacherinfo = $this->teacher_m->get_teacher($teacherID);
			$this->teacherInfo($teacherinfo);
			$this->documentInfo($teacherinfo);

			if(count($teacherinfo)) {
				$this->data["subview"] = "teacher/getView";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	
	private function teacherinfo($teacherinfo) {
		if(count($teacherinfo)) {
			$this->data['profile'] = $teacherinfo;
		} else {
			$this->data['profile'] = [];
		}
	}

	private function documentInfo($teacherinfo) {
		if(count($teacherinfo)) {
			$this->data['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 2, 'userID' => $teacherinfo->teacherID));
		} else {
			$this->data['documents'] = [];
		}
	}

	public function documentUpload() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		$retArray['errors'] = '';

		if(permissionChecker('teacher_add') && permissionChecker('teacher_delete')) {
			if($_POST) {
				$rules = $this->rules_documentupload();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray['errors'] = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$title = $this->input->post('title');
					$file = $this->upload_data['file']['file_name'];
					$userID = $this->input->post('teacherID');

					$array = array(
						'title' => $title,
						'file' => $file,
						'userID' => $userID,
						'usertypeID' => 2,
						"create_date" => date("Y-m-d H:i:s"),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_usertypeID" => $this->session->userdata('usertypeID')
					);

					$this->document_m->insert_document($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));

					$retArray['status'] = TRUE;
					$retArray['render'] = 'Success';
				    echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['status'] = FALSE;
				$retArray['render'] = 'Error';
			    echo json_encode($retArray);
			    exit;
			}
		} else {
			$retArray['status'] = FALSE;
			$retArray['render'] = 'Permission Denay.';
		    echo json_encode($retArray);
		    exit;
		}
	}

	public function download_document() {
		$documentID  = htmlentities(escapeString($this->uri->segment(3)));
		$teacherID 	 = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$documentID && (int)$teacherID) {
			if((permissionChecker('teacher_add') && permissionChecker('teacher_delete')) || ($this->session->userdata('usertypeID') == 2 && $this->session->userdata('loginuserID') == $teacherID)) {
				$document = $this->document_m->get_single_document(array('documentID' => $documentID));
				$file = realpath('uploads/documents/'.$document->file);
			    if (file_exists($file)) {
			    	$expFileName = explode('.', $file);
					$originalname = ($document->title).'.'.end($expFileName);
			    	header('Content-Description: File Transfer');
				    header('Content-Type: application/octet-stream');
				    header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
				    header('Expires: 0');
				    header('Cache-Control: must-revalidate');
				    header('Pragma: public');
				    header('Content-Length: ' . filesize($file));
				    readfile($file);
				    exit;
			    } else {
			    	redirect(base_url('teacher/view/'.$teacherID));
			    }
			} else {
				redirect(base_url('teacher/view/'.$teacherID));
			}
		} else {
			redirect(base_url('teacher/index'));
		}
	}

	public function delete_document() {
		$documentID = htmlentities(escapeString($this->uri->segment(3)));
		$teacherID 	= htmlentities(escapeString($this->uri->segment(4)));
		if((int)$documentID && (int)$teacherID) {
			if(permissionChecker('teacher_add') && permissionChecker('teacher_delete')) {
				$document = $this->document_m->get_single_document(array('documentID' => $documentID));
				if(count($document)) {
					if(config_item('demo') == FALSE) {
						if(file_exists(FCPATH.'uploads/document/'.$document->file)) {
							unlink(FCPATH.'uploads/document/'.$document->file);
						}
					}
					$this->document_m->delete_document($documentID);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url('teacher/view/'.$teacherID));
				} else {
					redirect(base_url('teacher/view/'.$teacherID));
				}
			} else {
				redirect(base_url('teacher/view/'.$teacherID));
			}
		} else {
			redirect(base_url('teacher/index'));
		}
	}

	protected function rules_documentupload() {
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("teacher_title"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("teacher_file"),
				'rules' => 'trim|xss_clean|max_length[200]|callback_unique_document_upload'
			)
		);
		return $rules;
	}

	public function unique_document_upload() {
		$new_file = '';
		if($_FILES["file"]['name'] !="") {
			$file_name = $_FILES["file"]['name'];
			$random = rand(1, 10000000000000000);
	    	$makeRandom = hash('sha512', $random.(strtotime(date('Y-m-d H:i:s'))). config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/documents";
				$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
				$config['file_name'] = $new_file;
				$config['max_size'] = '5120';
				$config['max_width'] = '10000';
				$config['max_height'] = '10000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("file")) {
					$this->form_validation->set_message("unique_document_upload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_document_upload", "Invalid file");
	     		return FALSE;
			}
		} else {
			$this->form_validation->set_message("unique_document_upload", "The file is required.");
			return FALSE;
		}
	}

	public function lol_username() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$teacher_info = $this->teacher_m->get_teacher($id);
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $teacher_info->username));
				if(count($user)) {
					$this->form_validation->set_message("lol_username", "%s already exists");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}
			if(in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("username" => $this->input->post('username')));
				if(count($user)) {
					$this->form_validation->set_message("lol_username", "%s already exists");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}

			if(in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function date_valid($date) {
		if(strlen($date) <10) {
			$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     	return FALSE;
		} else {
	   		$arr = explode("-", $date);
	        $dd = $arr[0];
	        $mm = $arr[1];
	        $yyyy = $arr[2];
	      	if(checkdate($mm, $dd, $yyyy)) {
	      		return TRUE;
	      	} else {
	      		$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     		return FALSE;
	      	}
	    }
	}

	public function tcode($pcode, $pusername, $version) {
		
		$email = trim($this->data['siteinfos']->email);

        $apiCurl = siteVarifyValidUser($email);

		if($apiCurl->status == FALSE) {
			$this->session->set_flashdata('error', $apiCurl->message);
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function print_preview() {
		if(permissionChecker('teacher_view')) {
			$teacherID = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$teacherID) {
				$this->data["teacher"] = $this->teacher_m->get_teacher($teacherID);
				if(count($this->data["teacher"])) {
					$this->data['panel_title'] = $this->lang->line('panel_title');
					$this->reportPDF('teacherprofile.css',$this->data, 'teacher/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('teacher_view')) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$teacherID = $this->input->post('teacherID');
					if ((int)$teacherID) {
						$this->data["teacher"] = $this->teacher_m->get_teacher($teacherID);
						if(count($this->data["teacher"])) {
							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$this->data['panel_title'] = $this->lang->line('panel_title');
							$this->reportSendToMail('teacherprofile.css', $this->data, 'teacher/print_preview', $email, $subject, $message);
							$retArray['message'] = "Message";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
						    exit;
						} else {
							$retArray['message'] = $this->lang->line('teacher_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('teacher_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('teacher_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('teacher_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function unique_email() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$teacher_info = $this->teacher_m->get_single_teacher(array('teacherID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $teacher_info->username));
				if(count($user)) {
					$this->form_validation->set_message("unique_email", "%s already exists");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}
			if(in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->teacher_m->get_username($table, array("email" => $this->input->post('email')));
				if(count($user)) {
					$this->form_validation->set_message("unique_email", "%s already exists");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}

			if(in_array('no', $array['permition'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	function active() {
		if(permissionChecker('teacher_edit')) {
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			if($id != '' && $status != '') {
				if((int)$id) {
					if($status == 'chacked') {
						$this->teacher_m->update_teacher(array('active' => 1), $id);
						echo 'Success';
					} elseif($status == 'unchacked') {
						$this->teacher_m->update_teacher(array('active' => 0), $id);
						echo 'Success';
					} else {
						echo "Error";
					}
				} else {
					echo "Error";
				}
			} else {
				echo "Error";
			}
		} else {
			echo "Error";
		}
	}

}

/* End of file teacher.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/teacher.php */
