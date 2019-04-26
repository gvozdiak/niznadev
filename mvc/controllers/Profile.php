<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Admin_Controller {
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
		$this->load->model('usertype_m');
		$this->load->model('section_m');
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("teacher_m");
		$this->load->model("user_m");
		$this->load->model("systemadmin_m");
		$this->load->model('studentrelation_m');
		$this->load->model('document_m');
		$this->load->model('studentgroup_m');
		$this->load->model('subject_m');
		$this->load->model('online_exam_m');
		$this->load->model('online_exam_user_status_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('profile', $language);
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("profile_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("profile_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("profile_message"),
				'rules' => 'trim|xss_clean'
			)
		);
		return $rules;
	}

	public function index() {
		$usertypeID  = $this->session->userdata("usertypeID");
		$loginuserID = $this->session->userdata('loginuserID');
		if($usertypeID == 1) {
			$user = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $loginuserID));
		} elseif($usertypeID == 2) {
			$user = $this->teacher_m->get_single_teacher(array('teacherID' => $loginuserID));
		} elseif($usertypeID == 3) {
			$user = $this->student_m->get_single_student(array('studentID' => $loginuserID));
		} elseif($usertypeID == 4) {
			$user = $this->parents_m->get_single_parents(array("parentsID" => $loginuserID));
		} else {
			$user = $this->user_m->get_single_user(array("userID" => $loginuserID));
		}
		$this->getView($user);
	}

	public function getView($user) {
		if(count($user)) {
			$this->pluckInfo($user);
			$this->basicInfo($user);
			$this->examInfo();
			$this->documentInfo($user);

			$this->data["subview"] = "profile/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data['subview'] ='error';
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function basicInfo($user) {
		if(count($user)) {
			if($user->usertypeID == '3') {
				$this->parentInfo($user);
			}

			if($user->usertypeID == '4') {
				$this->childrenInfo($user);
			}

			$this->data['profile'] = $user;
		} else {
			$this->data['profile'] = [];
		}
	}

	public function pluckInfo($user) {
		if(count($user)) {
			if($user->usertypeID == '3') {
				$this->data['sections'] = pluck($this->section_m->get_section(), 'section', 'sectionID');
				$this->data['classes'] = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
				$this->data['studentgroup'] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID' => $user->studentgroupID));
				$this->data['optionalsubject'] = $this->subject_m->get_single_subject(array('subjectID' => $user->optionalsubjectID));
			} elseif ($user->usertypeID == '4') {
				$this->data['sections'] = pluck($this->section_m->get_section(), 'section', 'sectionID');
				$this->data['classes'] = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
			}
		}
		$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
	}

	private function parentInfo($user) {
		if(count($user)) {
			$this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $user->parentID));
		} else {
			$this->data['parents'] = [];
		}
	}

	private function childrenInfo($user) {
		if(count($user)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->db->order_by('student.classesID', 'asc');
			$this->data['childrens'] = $this->student_m->general_get_order_by_student(array('parentID' => $user->parentsID, 'schoolyearID' => $schoolyearID));
		} else {
			$this->data['childrens'] = [];
		}
	}

	private function documentInfo($user) {
		if(count($user)) {
			if($user->usertypeID == 1) {
				$userID = $user->systemadminID;
			} elseif($user->usertypeID == 2) {
				$userID = $user->teacherID;
			} elseif($user->usertypeID == 3) {
				$userID = $user->studentID;
			} elseif($user->usertypeID == 4) {
				$userID = $user->parentsID;
			} else {
				$userID = $user->userID;
			}
			$this->data['documents'] = $this->document_m->get_order_by_document(array('userID' => $userID, 'usertypeID' => $user->usertypeID));
		} else {
			$this->data['documents'] = [];
		}
	}

	private function examInfo() {
		if($this->session->userdata('usertypeID') == 3) {
			$this->data['onlineexams'] = pluck($this->online_exam_m->get_online_exam(),'obj','onlineExamID');
			$this->data['examresults'] = $this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('userID'=>$this->session->userdata('loginuserID'))); 
		} else {
			$this->data['onlineexams'] = [];
			$this->data['examresults'] = []; 
		}
	}

	public function get_user_exam_status() {
		$retArray = [];
		$retArray['status'] = FALSE;

		$usertypeID   = $this->session->userdata('usertypeID');
		$studentID    = $this->session->userdata('loginuserID');
		$examstatusid = $this->input->post('examstatusid');
		if((int)$studentID && (int)$examstatusid) {
			$this->data['examresult'] = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamUserStatus'=>$examstatusid,'userID'=>$studentID));
			if(count($this->data['examresult'])) {
				$retArray['status'] = TRUE;
				$retArray['render'] = $this->load->view('profile/examresult',$this->data ,TRUE);
			} else {
				$retArray['msg'] = $this->lang->line('profile_data_not_found');
			}
		} else {
			$retArray['msg'] = $this->lang->line('profile_data_not_found');
		}
		echo json_encode($retArray);
	}


	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("profile_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("profile_dob"),
				'rules' => 'trim|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("profile_sex"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("profile_phone"),
				'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("profile_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("profile_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			
			array(
				'field' => 'religion',
				'label' => $this->lang->line("profile_religion"),
				'rules' => 'trim|max_length[25]|xss_clean'
			),
			array(
				'field' => 'bloodgroup',
				'label' => $this->lang->line("profile_bloodgroup"),
				'rules' => 'trim|max_length[5]|xss_clean'
			),
			array(
				'field' => 'state',
				'label' => $this->lang->line("profile_state"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'country',
				'label' => $this->lang->line("profile_country"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),


			array(
				'field' => 'email',
				'label' => $this->lang->line("profile_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("profile_email"),
				'rules' => 'trim|required|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			
			array(
				'field' => 'designation', 
				'label' => $this->lang->line("profile_designation"),
				'rules' => 'trim|required|max_length[128]|xss_clean'
			),
			array(
				'field' => 'father_name',
				'label' => $this->lang->line("profile_father_name"), 
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'mother_name', 
				'label' => $this->lang->line("profile_mother_name"), 
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'father_profession', 
				'label' => $this->lang->line("profile_father_name"), 
				'rules' => 'trim|xss_clean|max_length[40]'
			),
			array(
				'field' => 'mother_profession', 
				'label' => $this->lang->line("profile_mother_name"), 
				'rules' => 'trim|xss_clean|max_length[40]'
			),
		);
		return $rules;
	}

	public function photoupload() {
		$passUserData = array();
		$username = $this->session->userdata('username');
		if($username) {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_single_username($table, array('username' => $username ));
				if(count($user)) {
					$this->form_validation->set_message("unique_email", "%s already exists");
					$passUserData = $user;
				}
			}
		}

		$new_file = "defualt.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = rand(1, 10000000000000000);
	    	$makeRandom = hash('sha512', $random.rand(1, 10000000000000000) . config_item("encryption_key"));
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
			if(count($passUserData)) {
				$this->upload_data['file'] = array('file_name' => $passUserData->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);

		$tableArray = array('1' => 'systemadmin', '2' => 'teacher', '3' => 'student', '4' => 'parents');
		if(!isset($tableArray[$this->session->userdata('usertypeID')])) {
			$tableArray[$this->session->userdata('usertypeID')] = 'user';
		}

		$rules = array();
		$usertypeID = $this->session->userdata('usertypeID');
		$username = $this->session->userdata('username');
		$this->data['usertypeID'] = $usertypeID;
		if($usertypeID == 1) {
			$rules = $this->rules();
			unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
			$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('username' => $username));
		} elseif($usertypeID == 2) {
			$rules = $this->rules();
			unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
			$this->data['user'] = $this->teacher_m->get_single_teacher(array('username' => $username));
		} elseif($usertypeID == 3) {
			$rules = $this->rules();
			unset($rules[11], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
			$this->data['user'] = $this->student_m->get_single_student(array('username' => $username));
		} elseif($usertypeID == 4) {
			$rules = $this->rules();
			unset($rules[1], $rules[2], $rules[6], $rules[7], $rules[8], $rules[9], $rules[11], $rules[12]);
			$this->data['user'] = $this->parents_m->get_single_parents(array('username' => $username));
		} else {
			$rules = $this->rules();
			unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
			$this->data['user'] = $this->user_m->get_single_user(array('username' => $username));
		}


		if($_POST) {
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "profile/edit";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array();
				foreach ($rules as $rulekey => $rule) {
					if($rule['field'] == 'dob') {
						if($this->input->post($rule['field'])) {
							$array[$rule['field']] = date("Y-m-d", strtotime($this->input->post($rule['field'])));	
						}
					} else {
						$array[$rule['field']] = $this->input->post($rule['field']);
					}
				}


				if($usertypeID == 3) {
					$getRelationTableStudent = $this->studentrelation_m->get_single_studentrelation(array('srstudentID' => $this->data['user']->studentID));
					if(count($getRelationTableStudent)) {
						$this->student_m->profileRelationUpdate('studentrelation', array('srname' => $this->input->post('name')), $this->data['user']->studentID);
					}
				}

				$array['photo'] = $this->upload_data['file']['file_name'];
				
				$this->session->set_userdata(array('name' => $this->input->post('name'), 'email' => $this->input->post('email'), 'photo' => $array['photo']));

				$this->student_m->profileUpdate($tableArray[$usertypeID], $array, $username);
				redirect(base_url('profile/index'));
			}
		} else {
			$this->data['subview'] = 'profile/edit';
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function date_valid($date) {
		if($date) {
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
		return TRUE;
	}

	public function unique_email() {
		if($this->input->post('email')) {
			$username = $this->session->userdata('username');
			if($username) {
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $username ));
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
		return TRUE;
	}

	public function print_preview() {
		$usertypeID  = $this->session->userdata("usertypeID");
		$loginuserID = $this->session->userdata('loginuserID');
		if($usertypeID == 1) {
			$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $loginuserID));
		} elseif($usertypeID == 2) {
			$this->data['user'] = $this->teacher_m->get_single_teacher(array('teacherID' => $loginuserID));
		} elseif($usertypeID == 3) {
			$student = $this->student_m->get_single_student(array('studentID' => $loginuserID));
			$this->data['classes'] = $this->classes_m->get_single_classes(array('classesID'=>$student->classesID));
			$this->data['section'] = $this->section_m->get_single_section(array('sectionID'=>$student->sectionID)); 
			$this->data['optionalsubjects'] = $this->subject_m->get_single_subject(array("subjectID" => $student->optionalsubjectID, 'type' => 0));
			$this->data['studentgroup'] = $this->studentgroup_m->get_single_studentgroup(array("studentgroupID" => $student->studentgroupID));
			$this->data['user'] = $student;
		} elseif($usertypeID == 4) {
			$this->data['user'] = $this->parents_m->get_single_parents(array("parentsID" => $loginuserID));
		} else {
			$this->data['user'] = $this->user_m->get_single_user(array("userID" => $loginuserID));
		}

		$this->data['usertypeID'] = $usertypeID;
		$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID'=>$usertypeID));
		if(count($this->data['user'])) {
			$this->reportPDF('profile.css',$this->data, 'profile/print_preview');
		} else {
			$this->data['subview'] ='error';
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if($_POST) {
			$rules = $this->send_mail_rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$retArray = $this->form_validation->error_array();
				$retArray['status'] = FALSE;
			    echo json_encode($retArray);
			    exit;
			} else {
				$usertypeID  = $this->session->userdata("usertypeID");
				$loginuserID = $this->session->userdata('loginuserID');
				if($usertypeID == 1) {
					$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('systemadminID'=> $loginuserID));
				} elseif($usertypeID == 2) {
					$this->data['user'] = $this->teacher_m->get_single_teacher(array('teacherID'=> $loginuserID));
				} elseif($usertypeID == 3) {
					$student = $this->student_m->get_single_student(array('studentID' => $loginuserID));
					$this->data['classes'] = $this->classes_m->get_single_classes(array('classesID'=>$student->classesID));
					$this->data['section'] = $this->section_m->get_single_section(array('sectionID'=>$student->sectionID)); 
					$this->data['optionalsubjects'] = $this->subject_m->get_single_subject(array("subjectID" => $student->optionalsubjectID, 'type' => 0));
					$this->data['studentgroup'] = $this->studentgroup_m->get_single_studentgroup(array("studentgroupID" => $student->studentgroupID));
					$this->data['user'] = $student;
				} elseif($usertypeID == 4) {
					$this->data['user'] = $this->parents_m->get_single_parents(array("parentsID"=> $loginuserID));
				} else {
					$this->data['user'] = $this->user_m->get_single_user(array("userID" => $loginuserID));
				}
				
				$this->data['usertypeID'] =$usertypeID;
				$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID'=>$usertypeID));
				if(count($this->data['user'])) {
					$email   = $this->input->post('to');
					$subject = $this->input->post('subject');
					$message = $this->input->post('message');
					$this->reportSendToMail('profile.css',$this->data, 'profile/print_preview', $email, $subject, $message);
					$retArray['message'] = "Message";
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
				    exit;
				} else {
					$retArray['message'] = $this->lang->line('profile_data_not_found');
					echo json_encode($retArray);
					exit;
				}
			}
		} else {
			$retArray['message'] = $this->lang->line('profile_permissionmethod');
			echo json_encode($retArray);
			exit;
		}
	}

	protected function rules_documentupload() {
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("profile_title"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("profile_file"),
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

	public function documentUpload() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		$usertypeID  = $this->session->userdata('usertypeID');
		$loginuserID = $this->session->userdata('loginuserID');
		if(permissionChecker('student_add') && ($usertypeID == 1) && ($loginuserID==1)) {
			if($_POST) {
				$rules = $this->rules_documentupload();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray['errors'] = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$title  = $this->input->post('title');
					$file   = $this->upload_data['file']['file_name'];
					$userID = $this->input->post('systemadminID');

					$array = array(
						'title' => $title,
						'file' => $file,
						'userID' => $userID,
						'usertypeID' => 1,
						"create_date" => date("Y-m-d H:i:s"),
						"create_userID" => $loginuserID,
						"create_usertypeID" => $usertypeID
					);

					$this->document_m->insert_document($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));

					$retArray['status'] = TRUE;
				    echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['status'] = FALSE;
				$retArray['errors']['render'] = 'Error';
			    echo json_encode($retArray);
			    exit;
			}
		} else {
			$retArray['status'] = FALSE;
			$retArray['errors']['render'] = 'Permission Denay.';
		    echo json_encode($retArray);
		    exit;
		}
	}

	public function download_document() {
		$documentID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$documentID) {
			$document = $this->document_m->get_single_document(array('documentID' => $documentID));
			if(count($document)) {
				if(($document->usertypeID == $this->session->userdata('usertypeID')) && ($document->userID == $this->session->userdata('loginuserID'))) {
					$file = realpath('uploads/documents/'.$document->file);
				    if(file_exists($file)) {
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
				    	redirect(base_url('profile/index'));
				    }
				} else {
					redirect(base_url('profile/index'));
				}
			} else {
				redirect(base_url('profile/index'));
			}
		} else {
			redirect(base_url('profile/index'));
		}
	}

	public function delete_document() {
		$documentID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$documentID && ($this->session->userdata('usertypeID') == 1) && ($this->session->userdata('loginuserID')==1)) {
			$document = $this->document_m->get_single_document(array('documentID' => $documentID));
			if(count($document)) {
				if(config_item('demo') == FALSE) {
					if(file_exists(FCPATH.'uploads/document/'.$document->file)) {
						unlink(FCPATH.'uploads/document/'.$document->file);
					}
				}
				$this->document_m->delete_document($documentID);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url('profile/index'));
			} else {
				redirect(base_url('profile/index'));
			}
		} else {
			redirect(base_url('profile/index'));
		}
	}


}

/* End of file profile.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/profile.php */
