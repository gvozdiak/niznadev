<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends Admin_Controller {
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
	function __construct () {
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("teacher_m");
		$this->load->model("section_m");
		$this->load->model("classes_m");
		$this->load->model('studentrelation_m');
		$this->load->model('studentgroup_m');
		$this->load->model('studentextend_m');
		$this->load->model('document_m');
		$this->load->model('subject_m');
		$this->load->model('online_exam_user_status_m');
		$this->load->model('online_exam_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('student', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("student_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("student_dob"),
				'rules' => 'trim|max_length[10]|xss_clean|callback_date_valid'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("student_sex"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'bloodgroup',
				'label' => $this->lang->line("student_bloodgroup"),
				'rules' => 'trim|max_length[5]|xss_clean'
			),
			array(
				'field' => 'religion',
				'label' => $this->lang->line("student_religion"),
				'rules' => 'trim|max_length[25]|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("student_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("student_phone"),
				'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("student_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'state',
				'label' => $this->lang->line("student_state"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'country',
				'label' => $this->lang->line("student_country"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("student_classes"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classesID'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("student_section"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_sectionID|callback_unique_capacity'
			),
			array(
				'field' => 'registerNO',
				'label' => $this->lang->line("student_registerNO"),
				'rules' => 'trim|required|max_length[40]|callback_unique_registerNO|xss_clean'
			),
			array(
				'field' => 'roll',
				'label' => $this->lang->line("student_roll"),
				'rules' => 'trim|required|max_length[11]|numeric|callback_unique_roll|xss_clean'
			),
			array(
				'field' => 'guargianID',
				'label' => $this->lang->line("student_guargian"),
				'rules' => 'trim|required|max_length[11]|xss_clean|numeric'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("student_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),

            array(
                'field' => 'studentGroupID',
                'label' => $this->lang->line("student_studentgroup"),
                'rules' => 'trim|max_length[11]|xss_clean|numeric'
            ),

            array(
                'field' => 'optionalSubjectID',
                'label' => $this->lang->line("student_optionalsubject"),
                'rules' => 'trim|max_length[11]|xss_clean|numeric'
            ),

            array(
                'field' => 'extraCurricularActivities',
                'label' => $this->lang->line("student_extracurricularactivities"),
                'rules' => 'trim|max_length[128]|xss_clean'
            ),

            array(
                'field' => 'remarks',
                'label' => $this->lang->line("student_remarks"),
                'rules' => 'trim|max_length[128]|xss_clean'
            ),

			array(
				'field' => 'username',
				'label' => $this->lang->line("student_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("student_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
			)
		);
		return $rules;
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("student_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("student_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("student_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("student_studentID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("student_classesID"),
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
		$student = array();
		if((int)$id) {
			$student = $this->student_m->get_student($id);
		}

		$new_file = "default.png";
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
			if(count($student)) {
				$this->upload_data['file'] = array('file_name' => $student->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
			return TRUE;
			}
		}
	}

	public function index() {
		$usertypeID   = $this->session->userdata('usertypeID');
		$loginuserID  = $this->session->userdata("loginuserID");
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		if($usertypeID == 3) {
			if(permissionChecker('student_view')) {
				$singleStudent = $this->student_m->get_single_student(array("studentID" => $loginuserID, 'schoolyearID' => $schoolyearID));
				if(count($singleStudent)) {
					$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $singleStudent->classesID, 'schoolyearID' => $schoolyearID));
					if(count($this->data['students'])) {
						$sections = $this->section_m->get_order_by_section(array("classesID" => $singleStudent->classesID));
						if(count($sections)) {
							foreach ($sections as $section) {
								$this->data['allsection'][$section->sectionID] = $this->student_m->get_order_by_student(array('classesID' => $singleStudent->classesID, "sectionID" => $section->sectionID, 'schoolyearID' => $schoolyearID));
							}
						}
					} else {
						$this->data['students'] = NULL;
					}
					$this->data['sections'] = $sections;

					$this->data["subview"] = "student/index_parents";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$loginuserID = $this->session->userdata("loginuserID");
				$student = $this->student_m->get_single_student(array('studentID' => $loginuserID, 'schoolyearID' => $schoolyearID));
				if(count($student)) {
					$this->data['classesID'] = $student->classesID;
					$this->data['studentID'] = $student->studentID;
					$this->getView($student->studentID,$student->classesID);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			$parents      = $this->parents_m->get_single_parents(array('parentsID' => $loginuserID));
			if(count($parents)) {
				$this->data['students'] = $this->student_m->get_order_by_student(array('parentID' => $loginuserID, 'schoolyearID' => $schoolyearID));
				$this->data["subview"] = "student/index_parents";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);

			$classesID = htmlentities(escapeString($this->uri->segment(3)));
			$this->data['students'] = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
			if(count($this->data['students'])) {
				$sections = $this->section_m->get_order_by_section(array("classesID" => $classesID));
				if(count($sections)) {
					foreach ($sections as $section) {
						$this->data['allsection'][$section->sectionID] = $this->student_m->get_order_by_student(array('classesID' => $classesID, "sectionID" => $section->sectionID, 'schoolyearID' => $schoolyearID));
					}
				}
				$this->data['sections'] = $sections;
			} else {
				$this->data['students'] = [];
			}
			$this->data['set'] = $classesID;
			$this->data['classes'] = $this->classes_m->get_classes();

			$this->data["subview"] = "student/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function add() {
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

		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data['sections'] = $this->section_m->get_section();
		$this->data['parents'] = $this->parents_m->get_parents();
		$this->data['studentgroups'] = $this->studentgroup_m->get_studentgroup();

		$classesID = $this->input->post("classesID");

		if($classesID != 0) {
			$this->data['sections'] = $this->section_m->get_order_by_section(array("classesID" =>$classesID));
            $this->data['optionalSubjects'] = $this->subject_m->get_order_by_subject(array("classesID" =>$classesID, 'type' => 0));
		} else {
			$this->data['sections'] = "empty";
			$this->data['optionalSubjects'] = 'empty';
		}

		$this->data['sectionID'] = $this->input->post("sectionID");
        $this->data['optionalSubjectID'] = 0;

		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "student/add";
				$this->load->view('_layout_main', $this->data);
			} else {

				$sectionID = $this->input->post("sectionID");
				if($sectionID == 0) {
					$this->data['sectionID'] = 0;
				} else {
					$this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
					$this->data['sectionID'] = $this->input->post("sectionID");
				}

				if($this->input->post('optionalSubjectID')) {
                    $this->data['optionalSubjectID'] = $this->input->post('optionalSubjectID');
                } else {
                    $this->data['optionalSubjectID'] = 0;
                }

				$array = array();
				$array["name"] = $this->input->post("name");
				$array["sex"] = $this->input->post("sex");
				$array["religion"] = $this->input->post("religion");
				$array["email"] = $this->input->post("email");
				$array["phone"] = $this->input->post("phone");
				$array["address"] = $this->input->post("address");
				$array["classesID"] = $this->input->post("classesID");
				$array["sectionID"] = $this->input->post("sectionID");
				$array["roll"] = $this->input->post("roll");
				$array["bloodgroup"] = $this->input->post("bloodgroup");
				$array["state"] = $this->input->post("state");
				$array["country"] = $this->input->post("country");
				$array["registerNO"] = $this->input->post("registerNO");
				$array["username"] = $this->input->post("username");
				$array['password'] = $this->student_m->hash($this->input->post("password"));
				$array['usertypeID'] = 3;
				$array['parentID'] = $this->input->post('guargianID');
				$array['library'] = 0;
				$array['hostel'] = 0;
				$array['transport'] = 0;
				$array['create_date'] = date("Y-m-d");
				$array['createschoolyearID'] = $this->data['siteinfos']->school_year;
				$array['schoolyearID'] = $this->data['siteinfos']->school_year;
				$array["create_date"] = date("Y-m-d h:i:s");
				$array["modify_date"] = date("Y-m-d h:i:s");
				$array["create_userID"] = $this->session->userdata('loginuserID');
				$array["create_username"] = $this->session->userdata('username');
				$array["create_usertype"] = $this->session->userdata('usertype');
				$array["active"] = 1;

				if($this->input->post('dob')) {
					$array["dob"] 		= date("Y-m-d", strtotime($this->input->post("dob")));
				}
				$array['photo'] = $this->upload_data['file']['file_name'];
				
				

				$this->student_m->insert_student($array);
				$studentID = $this->db->insert_id();

				$section = $this->section_m->get_section($this->input->post("sectionID"));
				$classes = $this->classes_m ->get_classes($this->input->post("classesID"));

				if(count($classes)) {
					$setClasses = $classes->classes;
				} else {
					$setClasses = NULL;
				}

				if(count($section)) {
					$setSection = $section->section;
				} else {
					$setSection = NULL;
				}

				$arrayStudentRelation = array(
					'srstudentID' => $studentID,
					'srname' => $this->input->post("name"),
					'srclassesID' => $this->input->post("classesID"),
					'srclasses' => $setClasses,
					'srroll' => $this->input->post("roll"),
					'srregisterNO' => $this->input->post("registerNO"),
					'srsectionID' => $this->input->post("sectionID"),
					'srsection' => $setSection,
					'srstudentgroupID' => $this->input->post('studentGroupID'),
					'sroptionalsubjectID' => $this->input->post('optionalSubjectID'),
					'srschoolyearID' => $this->data['siteinfos']->school_year
				);

                $studentExtendArray = array(
                    'studentID' => $studentID,
                    'studentgroupID' => $this->input->post('studentGroupID'),
                    'optionalsubjectID' => $this->input->post('optionalSubjectID'),
                    'extracurricularactivities' => $this->input->post('extraCurricularActivities'),
                    'remarks' => $this->input->post('remarks')
                );

                $this->studentextend_m->insert_studentextend($studentExtendArray);
				$this->studentrelation_m->insert_studentrelation($arrayStudentRelation);
				$this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("student/index"));
			}
		} else {
			$this->data["subview"] = "student/add";
			$this->load->view('_layout_main', $this->data);
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
		$usertype = $this->session->userdata("usertype");
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$studentID = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$studentID && (int)$url) {
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $studentID, 'schoolyearID' => $schoolyearID));


			$this->data['parents'] = $this->parents_m->get_parents();
            $this->data['studentgroups'] = $this->studentgroup_m->get_studentgroup();


			if($this->data['student']) {
				$classesID = $this->data['student']->classesID;
				$this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
                $this->data['optionalSubjects'] = $this->subject_m->get_order_by_subject(array("classesID" =>$classesID, 'type' => 0));

                if($this->input->post('optionalSubjectID')) {
                    $this->data['optionalSubjectID'] = $this->input->post('optionalSubjectID');
                } else {
                    $this->data['optionalSubjectID'] = 0;
                }
			}

			$this->data['set'] = $url;
			if($this->data['student']) {
				if($_POST) {
					$rules = $this->rules();
					unset($rules[21]);
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "student/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = array();
						$array["name"] = $this->input->post("name");
						$array["sex"] = $this->input->post("sex");
						$array["religion"] = $this->input->post("religion");
						$array["email"] = $this->input->post("email");
						$array["phone"] = $this->input->post("phone");
						$array["address"] = $this->input->post("address");
						$array["classesID"] = $this->input->post("classesID");
						$array["sectionID"] = $this->input->post("sectionID");
						$array["roll"] = $this->input->post("roll");
						$array["bloodgroup"] = $this->input->post("bloodgroup");
						$array["state"] = $this->input->post("state");
						$array["country"] = $this->input->post("country");
						$array["registerNO"] = $this->input->post("registerNO");
						$array["parentID"] = $this->input->post("guargianID");
						$array["username"] = $this->input->post("username");
						$array["modify_date"] = date("Y-m-d h:i:s");
						$array['photo'] = $this->upload_data['file']['file_name'];

						if($this->input->post('dob')) {
							$array["dob"] 	= date("Y-m-d", strtotime($this->input->post("dob")));
						} else {
							$array["dob"] = NULL;
						}


						$studentReletion = $this->studentrelation_m->get_order_by_studentrelation(array('srstudentID' => $studentID, 'srschoolyearID' => $this->data['siteinfos']->school_year));
						$section = $this->section_m->get_section($this->input->post("sectionID"));
						$classes = $this->classes_m ->get_classes($this->input->post("classesID"));

						if(count($classes)) {
							$setClasses = $classes->classes;
						} else {
							$setClasses = NULL;
						}

						if(count($section)) {
							$setSection = $section->section;
						} else {
							$setSection = NULL;
						}

						if(!count($studentReletion)) {
							$arrayStudentRelation = array(
								'srstudentID' => $studentID,
								'srname' => $this->input->post("name"),
								'srclassesID' => $this->input->post("classesID"),
								'srclasses' => $setClasses,
								'srroll' => $this->input->post("roll"),
								'srregisterNO' => $this->input->post("registerNO"),
								'srsectionID' => $this->input->post("sectionID"),
								'srsection' => $setSection,
								'srstudentgroupID' => $this->input->post("studentGroupID"),
								'sroptionalsubjectID' => $this->input->post("optionalSubjectID"),
								'srschoolyearID' => $this->data['siteinfos']->school_year
							);
							$this->studentrelation_m->insert_studentrelation($arrayStudentRelation);
						} else {
							$arrayStudentRelation = array(
								'srname' => $this->input->post("name"),
								'srclassesID' => $this->input->post("classesID"),
								'srclasses' => $setClasses,
								'srroll' => $this->input->post("roll"),
								'srregisterNO' => $this->input->post("registerNO"),
								'srsectionID' => $this->input->post("sectionID"),
								'srsection' => $setSection,
								'srstudentgroupID' => $this->input->post("studentGroupID"),
								'sroptionalsubjectID' => $this->input->post("optionalSubjectID"),
							);

							$this->studentrelation_m->update_studentrelation_with_multicondition($arrayStudentRelation, array('srstudentID' => $studentID, 'srschoolyearID' => $this->data['siteinfos']->school_year));
						}

                        $studentExtendArray = array(
                            'studentgroupID' => $this->input->post('studentGroupID'),
                            'optionalsubjectID' => $this->input->post('optionalSubjectID'),
                            'extracurricularactivities' => $this->input->post('extraCurricularActivities'),
                            'remarks' => $this->input->post('remarks')
                        );

                        $this->studentextend_m->update_studentextend_by_studentID($studentExtendArray, $studentID);
						$this->student_m->update_student($array, $studentID);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("student/index/$url"));
					}
				} else {
					$this->data["subview"] = "student/edit";
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

	public function view() {
		$classesID = htmlentities(escapeString($this->uri->segment(4)));
		$studentID = htmlentities(escapeString($this->uri->segment(3)));
		$usertypeID   = $this->session->userdata('usertypeID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$username     = $this->session->userdata("username");
		
		$this->data['classesID'] = $classesID;
		$this->data['studentID'] = $studentID;
		
		if($usertypeID == 3) {
			if(permissionChecker('student_view')) {
				if((int)$studentID && (int)$classesID) {
					$originalStudent = $this->student_m->get_single_student(array("username" => $username));
					if(count($originalStudent)) {
						$student = $this->student_m->get_single_student(array('studentID' => $studentID, 'schoolyearID' => $schoolyearID));
						if(count($student)) {
							if($originalStudent->classesID == $student->classesID) {
								$this->getView($studentID,$classesID);
							} else {
								$this->data["subview"] = "error";
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
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$student = $this->student_m->get_single_student(array('username' => $username, 'schoolyearID' => $schoolyearID));
				if(count($student)) {
					$this->getView($student->studentID,$student->classesID);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} elseif($usertypeID == 4) {
			$parents = $this->parents_m->get_single_parents(array('username' => $username));
			if(count($parents)) {
				if((int)$studentID && (int)$classesID) {
					$checkstudent = $this->student_m->get_single_student(array('studentID' => $studentID, 'schoolyearID' => $schoolyearID));
					if(count($checkstudent)) {
						if($checkstudent->parentID == $parents->parentsID) {
							$this->getView($studentID, $classesID);
						} else {
							$this->data["subview"] = "error";
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
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$student = $this->student_m->get_single_student(array('studentID' => $studentID, 'schoolyearID' => $schoolyearID));
			if(count($student)) {
				$this->getView($studentID, $classesID);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	private function getView($studentID, $classesID) {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$studentID && (int)$classesID) {
			$studentInfo = $this->student_m->get_single_student(array('studentID' => $studentID, 'classesID' => $classesID, 'schoolyearID' => $schoolyearID));

			$this->basicInfo($studentInfo);
			$this->parentInfo($studentInfo);
			$this->examInfo($studentInfo);
			$this->documentInfo($studentInfo);

			if(count($studentInfo)) {
				$this->data["subview"] = "student/getView";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	private function pluckInfo() {
		$this->data['subjects'] = pluck($this->subject_m->get_subject(), 'subject', 'subjectID');
		$this->data['teachers'] = pluck($this->teacher_m->get_teacher(), 'name', 'teacherID');
	}

	private function basicInfo($studentInfo) {
		if(count($studentInfo)) {
			$this->data['profile'] = $studentInfo;
			$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $studentInfo->usertypeID));
			$this->data['class'] = $this->classes_m->get_single_classes(array('classesID' => $studentInfo->classesID));
			$this->data['section'] = $this->section_m->get_single_section(array('sectionID' => $studentInfo->sectionID));

			$this->data['group'] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID' => $studentInfo->studentgroupID));
			$this->data['optionalsubject'] = $this->subject_m->get_single_subject(array('subjectID' => $studentInfo->optionalsubjectID));
		} else {
			$this->data['profile'] = [];
		}
	}

	private function parentInfo($studentInfo) {
		if(count($studentInfo)) {
			$this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $studentInfo->parentID));
		} else {
			$this->data['parents'] = [];
		}
	}

	private function examInfo($studentInfo) {
		$this->data['onlineexams'] = pluck($this->online_exam_m->get_online_exam(),'obj','onlineExamID');
		$this->data['examresults'] = $this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('userID'=>$studentInfo->studentID)); 
	}

	public function print_preview() {
		$studentID = htmlentities(escapeString($this->uri->segment(3)));
		$classesID = htmlentities(escapeString($this->uri->segment(4)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$usertypeID   = $this->session->userdata('usertypeID');
		$loginuserID  = $this->session->userdata('loginuserID');

		if(permissionChecker('student_view') || (($usertypeID == 3) && permissionChecker('student') && ($loginuserID == htmlentities(escapeString($this->uri->segment(3)))))) {
			if((int)$studentID && (int)$classesID) {
				$student = $this->student_m->get_single_student(array('studentID' => $studentID, 'schoolyearID' => $schoolyearID));
				if(count($student)) {
					$this->data["class"]   = $this->classes_m->get_single_classes(array('classesID'=>$student->classesID));
					$this->data["section"] = $this->section_m->get_single_section(array('sectionID'=>$student->sectionID));
					$this->data["studentgroup"] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID'=>$student->studentgroupID));
					$this->data["usertype"] = $this->usertype_m->get_single_usertype(array('usertypeID'=> $student->usertypeID));
					$this->data["allbloodgroup"] = $this->getAllBloodgroup();
					$this->data["optionalsubject"] = $this->subject_m->get_single_subject(array('subjectID'=> $student->optionalsubjectID,'type'=>0));

					$this->data['student'] = $student;
					$this->reportPDF('studentprofile.css',$this->data, 'student/print_preview');
				} else {
					$this->data["subview"] = "error";
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

	public function send_mail() {
		$studentID = $this->input->post('studentID');
		$classesID = $this->input->post('classesID');
		$usertypeID   = $this->session->userdata('usertypeID');
		$loginuserID  = $this->session->userdata('loginuserID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		
		$retArray['message'] = '';
		$retArray['status'] = FALSE;
		if(permissionChecker('student_view') || (($usertypeID == 3) && permissionChecker('student') && ($loginuserID == $studentID))) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					if ((int)$studentID && (int)$classesID) {
						$student = $this->student_m->get_single_student(array('studentID' => $studentID, 'schoolyearID' => $schoolyearID));
						if(count($student)) {
							$this->data["class"]        = $this->classes_m->get_single_classes(array('classesID'=>$student->classesID));
							$this->data["section"]      = $this->section_m->get_single_section(array('sectionID'=>$student->sectionID));
							$this->data["studentgroup"] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID'=>$student->studentgroupID));
							$this->data["usertype"]        = $this->usertype_m->get_single_usertype(array('usertypeID'=> $student->usertypeID));
							$this->data["allbloodgroup"]   = $this->getAllBloodgroup();
							$this->data["optionalsubject"] = $this->subject_m->get_single_subject(array('subjectID'=> $student->optionalsubjectID,'type'=>0));
							$this->data['student'] = $student;
							
							$email   = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$this->reportSendToMail('studentprofile.css', $this->data, 'student/print_preview', $email, $subject, $message);
							$retArray['message'] = "Success";
							$retArray['status']  = TRUE;
							echo json_encode($retArray);
						    exit;
						} else {
							$retArray['message'] = $this->lang->line('student_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('student_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('student_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('student_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function delete() {
		$studentID = htmlentities(escapeString($this->uri->segment(3)));
		$classesID = htmlentities(escapeString($this->uri->segment(4)));
		$usertype = $this->session->userdata("usertype");
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ((int)$studentID && (int)$classesID) {
			$this->data['student'] = $this->student_m->get_single_student(array('studentID' => $studentID, 'schoolyearID' => $schoolyearID));
			if(count($this->data['student'])) {
				if(config_item('demo') == FALSE) {
					if($this->data['student']->photo != 'default.png' && $this->data['student']->photo != 'defualt.png') {
						if(file_exists(FCPATH.'uploads/images/'.$this->data['student']->photo)) {
							unlink(FCPATH.'uploads/images/'.$this->data['student']->photo);
						}
					}
				}
				$this->student_m->delete_student($studentID);
				$this->studentextend_m->delete_studentextend_by_studentID($studentID);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("student/index/$classesID"));
			} else {
				redirect(base_url("student/index"));
			}
		} else {
			redirect(base_url("student/index/$classesID"));
		}
	}

	public function get_user_exam_status() {
		$retArray = [];
		$retArray['status'] = FALSE;

		$usertypeID   = $this->session->userdata('usertypeID');
		$loginuserID  = $this->session->userdata('loginuserID');
		$studentID    = $this->input->post('studentID');
		$examstatusid = $this->input->post('examstatusid');
		if(permissionChecker('student_view') || (($usertypeID == 3) && permissionChecker('student') && ($loginuserID == $studentID))) {
			if((int)$studentID && (int)$examstatusid) {
				$this->data['examresult'] = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamUserStatus'=>$examstatusid,'userID'=>$studentID));
				if(count($this->data['examresult'])) {
					$retArray['status'] = TRUE;
					$retArray['render'] = $this->load->view('student/examresult',$this->data ,TRUE);
				} else {
					$retArray['msg'] = $this->lang->line('student_data_not_found');
				}
			} else {
				$retArray['msg'] = $this->lang->line('student_data_not_found');
			}
		} else {
			$retArray['msg'] = $this->lang->line('student_permission');
		}
		echo json_encode($retArray);
	}

	public function unique_roll() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID = $this->data['siteinfos']->school_year;
		if((int)$id) {
			$student = $this->student_m->get_order_by_student(array("roll" => $this->input->post("roll"), "studentID !=" => $id, "classesID" => $this->input->post('classesID'), 'schoolyearID' => $schoolyearID));
			if(count($student)) {
				$this->form_validation->set_message("unique_roll", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
		} else {
			$student = $this->student_m->get_order_by_student(array("roll" => $this->input->post("roll"), "classesID" => $this->input->post('classesID'), 'schoolyearID' => $schoolyearID));

			if(count($student)) {
				$this->form_validation->set_message("unique_roll", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function lol_username() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$student_info = $this->student_m->get_single_student(array('studentID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $student_info->username));
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
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username')));
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

	public function unique_classesID() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classesID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_sectionID() {
		if($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("unique_sectionID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("student/index/$classID");
			echo $string;
		} else {
			redirect(base_url("student/index"));
		}
	}

	public function unique_email() {
		if($this->input->post('email')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$student_info = $this->student_m->get_single_student(array('studentID' => $id));
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $student_info->username ));
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
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email')));
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

	public function sectioncall() {
		$classesID = $this->input->post('id');
		if((int)$classesID) {
			$allsection = $this->section_m->get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("student_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		}
	}

    public function optionalsubjectcall() {
        $classesID = $this->input->post('id');
        if((int)$classesID) {
            $allOptionalSubjects = $this->subject_m->get_order_by_subject(array("classesID" =>$classesID, 'type' => 0));
            echo "<option value='0'>", $this->lang->line("student_select_optionalsubject"),"</option>";
            foreach ($allOptionalSubjects as $value) {
                echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
            }
        }
    }

	public function unique_capacity() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			if($this->input->post('sectionID')) {
				$sectionID = $this->input->post('sectionID');
				$classesID = $this->input->post('classesID');
				$schoolyearID = $this->data['siteinfos']->school_year;
				$section = $this->section_m->get_section($this->input->post('sectionID'));
				$student = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'sectionID' => $sectionID, 'schoolyearID' => $schoolyearID, 'studentID !=' => $id));
				if(count($student) >= $section->capacity) {
					$this->form_validation->set_message("unique_capacity", "The %s capacity is full.");
		     		return FALSE;
				}
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_capacity", "The %s field is required.");
		     	return FALSE;
			}
		} else {
			if($this->input->post('sectionID')) {
				$sectionID = $this->input->post('sectionID');
				$classesID = $this->input->post('classesID');
				$schoolyearID = $this->data['siteinfos']->school_year;
				$section = $this->section_m->get_section($this->input->post('sectionID'));
				$student = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'sectionID' => $sectionID, 'schoolyearID' => $schoolyearID));
				if(count($student) >= $section->capacity) {
					$this->form_validation->set_message("unique_capacity", "The %s capacity is full.");
		     		return FALSE;
				}
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_capacity", "The %s field is required.");
		     	return FALSE;
			}
		}
	}

	public function unique_registerNO() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID = $this->data['siteinfos']->school_year;
		if((int)$id) {
			$student = $this->student_m->get_single_student(array("registerNO" => $this->input->post("registerNO"), "studentID !=" => $id, "classesID" => $this->input->post('classesID'), 'schoolyearID' => $schoolyearID));
			if(count($student)) {
				$this->form_validation->set_message("unique_registerNO", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
		} else {
			$student = $this->student_m->get_single_student(array("registerNO" => $this->input->post("registerNO"), "classesID" => $this->input->post('classesID'), 'schoolyearID' => $schoolyearID));

			if(count($student)) {
				$this->form_validation->set_message("unique_registerNO", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function active() {
		if(permissionChecker('student_edit')) {
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			if($id != '' && $status != '') {
				if((int)$id) {
					if($status == 'chacked') {
						$this->student_m->update_student(array('active' => 1), $id);
						echo 'Success';
					} elseif($status == 'unchacked') {
						$this->student_m->update_student(array('active' => 0), $id);
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

	protected function rules_documentupload() {
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("student_title"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("student_file"),
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

		if(permissionChecker('student_add')) {
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
					$userID = $this->input->post('studentID');

					$array = array(
						'title' => $title,
						'file' => $file,
						'userID' => $userID,
						'usertypeID' => 3,
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

	private function documentInfo($studentInfo) {
		if(count($studentInfo)) {
			$this->data['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 3, 'userID' => $studentInfo->studentID));
		} else {
			$this->data['documents'] = [];
		}
	}

	public function download_document() {
		$documentID = htmlentities(escapeString($this->uri->segment(3)));
		$studentID 	= htmlentities(escapeString($this->uri->segment(4)));
		$classesID 	= htmlentities(escapeString($this->uri->segment(5)));
		if((int)$documentID && (int)$studentID && (int)$classesID) {
			if((permissionChecker('student_add') && permissionChecker('student_delete')) || ($this->session->userdata('usertypeID') == 3 && $this->session->userdata('loginuserID') == $studentID)) {
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
			    	redirect(base_url('student/view/'.$studentID.'/'.$classesID));
			    }
			} else {
				redirect(base_url('student/view/'.$studentID.'/'.$classesID));
			}
		} else {
			redirect(base_url('student/index'));
		}
	}

	public function delete_document() {
		$documentID = htmlentities(escapeString($this->uri->segment(3)));
		$studentID 	= htmlentities(escapeString($this->uri->segment(4)));
		$classesID 	= htmlentities(escapeString($this->uri->segment(5)));
		if((int)$documentID && (int)$studentID && (int)$classesID) {
			if(permissionChecker('student_add') && permissionChecker('student_delete')) {
				$document = $this->document_m->get_single_document(array('documentID' => $documentID));
				if(count($document)) {
					if(config_item('demo') == FALSE) {
						if(file_exists(FCPATH.'uploads/document/'.$document->file)) {
							unlink(FCPATH.'uploads/document/'.$document->file);
						}
					}
					$this->document_m->delete_document($documentID);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url('student/view/'.$studentID.'/'.$classesID));
				} else {
					redirect(base_url('student/view/'.$studentID.'/'.$classesID));
				}
			} else {
				redirect(base_url('student/view/'.$studentID.'/'.$classesID));
			}
		} else {
			redirect(base_url('student/index'));
		}
	}
	
}