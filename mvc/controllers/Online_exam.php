<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Online_exam extends Admin_Controller {
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
        $this->load->model("online_exam_m");
        $this->load->model("classes_m");
        $this->load->model("section_m");
        $this->load->model("subject_m");
        $this->load->model("studentgroup_m");
        $this->load->model("usertype_m");
        $this->load->model("exam_type_m");
        $this->load->model("question_bank_m");
        $this->load->model("question_level_m");
        $this->load->model("question_group_m");
        $this->load->model("question_type_m");
        $this->load->model("question_option_m");
        $this->load->model("question_answer_m");
        $this->load->model("online_exam_question_m");
        $this->load->model("exam_type_m");
        $this->load->model("instruction_m");
        $this->load->model("student_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('online_exam', $language);
    }

    public function index() {
        $usertypeID  = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');

        $this->data['userSubjectPluck'] = pluck($this->subject_m->get_order_by_subject(array('type' => 1)), 'subjectID', 'subjectID');
        if($usertypeID == '3') {
            $this->data['student'] = $this->student_m->get_single_student(array('studentID'=>$loginuserID));
            if(count($this->data['student'])) {
                $optionalSubject = $this->subject_m->get_single_subject(array('type' => 0, 'subjectID' => $this->data['student']->optionalsubjectID));
                if(count($optionalSubject)) {
                    $this->data['userSubjectPluck'][$optionalSubject->subjectID] = $optionalSubject->subjectID;
                }
            }
        }

        $this->data['usertypeID']   = $usertypeID;
        $this->data['online_exams'] = $this->online_exam_m->get_order_by_online_exam();
        $this->data["subview"] = "online_exam/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules($type = 2, $ispaid = 0) {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("online_exam_name"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("online_exam_description"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'classes',
                'label' => $this->lang->line("online_exam_class"),
                'rules' => 'trim|xss_clean|required|numeric'
            ),
            array(
                'field' => 'section',
                'label' => $this->lang->line("online_exam_section"),
                'rules' => 'trim|xss_clean|required|numeric'
            ),
            array(
                'field' => 'studentGroup',
                'label' => $this->lang->line("online_exam_studentGroup"),
                'rules' => 'trim|xss_clean|required|numeric'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("online_exam_subject"),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field' => 'instruction',
                'label' => $this->lang->line("online_exam_instruction"),
                'rules' => 'trim|xss_clean|required|numeric'
            ),
            array(
                'field' => 'examStatus',
                'label' => $this->lang->line("online_exam_exam_status"),
                'rules' => 'trim|xss_clean|required|numeric|callback_unique_data'
            ),
            array(
                'field' => 'type',
                'label' => $this->lang->line("online_exam_type"),
                'rules' => 'trim|xss_clean|required|numeric|callback_unique_type'
            ),
            array(
                'field' => 'duration',
                'label' => $this->lang->line("online_exam_duration"),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field' => 'markType',
                'label' => $this->lang->line("online_exam_markType"),
                'rules' => 'trim|required|xss_clean|numeric|callback_unique_markType'
            ),
            array(
                'field' => 'percentage',
                'label' => $this->lang->line("online_exam_percentage"),
                'rules' => 'trim|required|xss_clean|numeric'
            ),
            array(
                'field' => 'ispaid',
                'label' => $this->lang->line("online_exam_payment_status"),
                'rules' => 'trim|xss_clean|required|numeric|callback_ispaid_unique_data'
            ),
            array(
                'field' => 'published',
                'label' => $this->lang->line("online_exam_published"),
                'rules' => 'trim|xss_clean|required|numeric|callback_unique_data|callback_check_exam_question'
            ),

        );

        if($ispaid == 1) {
            $rules[] = array(
                'field' => 'cost',
                'label' => $this->lang->line("online_exam_cost"),
                'rules' => 'trim|xss_clean|required|numeric|callback_unique_cost'
            );
        }

        if($type == 4) {
            $rules[] = array(
                'field' => 'startdate',
                'label' => $this->lang->line("online_exam_startdatetime"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_date'
            );
            $rules[] = array(
                'field' => 'enddate',
                'label' => $this->lang->line("online_exam_enddatetime"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_date'
            );
        } elseif($type == 5) {
            $rules[] = array(
                'field' => 'startdatetime',
                'label' => $this->lang->line("online_exam_startdatetime"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_date_time'
            ); 
            $rules[] = array(
                'field' => 'enddatetime',
                'label' => $this->lang->line("online_exam_enddatetime"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_date_time'
            );
        }
        return $rules;
    }

    public function unique_data($data) {
        if($data != "") {
            if($data === "0") {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return FALSE;
            }
            return TRUE;
        } 
        return TRUE;
    }

    public function ispaid_unique_data($data) {
        if($data != "") {
            if($data === "5") {
                $this->form_validation->set_message('ispaid_unique_data', 'The %s field is required.');
                return FALSE;
            }
            return TRUE;
        } 
        return TRUE;
    }

    public function unique_cost($data) {
        if($data != '') {
            if($data <= 0) {
                $this->form_validation->set_message('unique_cost', 'The %s field does not take zero value');
                return FALSE;
            }
            return true;
        } 
        return true;
    }

    public function unique_date() {
        $startdate = $this->input->post('startdate');
        $enddate   = $this->input->post('enddate');
        if($startdate != '' && $enddate != '') {
            if(strtotime($startdate) > strtotime($enddate)) {
                $this->form_validation->set_message("unique_date", "The start date can not be upper than enddate.");
                return FALSE;
            }
        }
        return TRUE;
    }

    public function unique_date_time() {
        $startdatetime = $this->input->post('startdatetime');
        $enddatetime   = $this->input->post('enddatetime');
        if($startdatetime != '' && $enddatetime != '') {
            if(strtotime($startdatetime) > strtotime($enddatetime)) {
                $this->form_validation->set_message("unique_date_time", "The start date time can not be upper than end date time.");
                return FALSE;
            }
        }
        return TRUE;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datetimepicker/datetimepicker.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/datetimepicker/moment.js',
                'assets/datetimepicker/datetimepicker.js',
                'assets/select2/select2.js'
            )
        );

        $usertypeID = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        $schoolYearID = $this->data['siteinfos']->school_year;

        $this->data['classes'] = $this->classes_m->get_order_by_classes();
        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['instructions'] = $this->instruction_m->get_order_by_instruction();
        $this->data['types'] = $this->exam_type_m->get_order_by_exam_type(['status' => 1]);
        $this->data['groups'] = $this->studentgroup_m->get_order_by_studentgroup();
        $this->data['userTypeID'] = 3;
        $this->data['sections'] = array();
        $this->data['subjects'] = array();

        if($_POST) {
            $this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $this->input->post('classes')));
            $this->data['subjects'] = $this->subject_m->get_order_by_subject(array('classesID' => $this->input->post('classes')));
            $this->data['posttype'] = $this->input->post('type');
            $rules = $this->rules($this->data['posttype'], $this->input->post('ispaid'));
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "/online_exam/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $inputs = $this->input->post();
                $databasePair = [
                    'name' => 'name',
                    'description' => 'description',
                    'usertype' => 'userTypeID',
                    'classes' => 'classID',
                    'section' => 'sectionID',
                    'studentGroup' => 'studentGroupID',
                    'subject' => 'subjectID',
                    'instruction' => 'instructionID',
                    'duration' => 'duration',
                    'type' => 'examTypeNumber',
                    'markType' => 'markType',
                    'percentage' => 'percentage',
                    'ispaid' => 'paid',
                    'validDays' => 'validDays',
                    'cost' => 'cost',
                    'judge' => 'judge'
                ];
                if($inputs['type'] == 4) {
                    $databasePair ['startdate'] = 'startDateTime';
                    $databasePair ['enddate'] = 'endDateTime';
                } elseif ($inputs['type'] == 5) {
                    $databasePair ['startdatetime'] = 'startDateTime';
                    $databasePair ['enddatetime'] = 'endDateTime';
                }

                $array = [];
                foreach ($databasePair as $key => $database) {
                    if($inputs[$key] != "") {
                        if($database == 'startDateTime' || $database == 'endDateTime') {
                            $array[$database] = date('Y-m-d H:i:s', strtotime($inputs[$key]));
                        } else {
                            $array[$database] = $inputs[$key];
                        }
                    }
                }

                if($this->input->post('ispaid') == 0) {
                    $array['cost'] = 0;
                }

                $array['examStatus'] = $this->input->post('examStatus');
                $array['published']   = $this->input->post('published');
                $array['create_date'] = date("Y-m-d H:i:s");
                $array['modify_date'] = date("Y-m-d H:i:s");
                $array['create_userID'] =$usertypeID;
                $array['create_usertypeID'] = $loginuserID;
                $array['schoolYearID'] = $schoolYearID;
                $array['negativeMark'] = 0;
                $array['random'] = 0;
                $this->online_exam_m->insert_online_exam($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("online_exam/index"));
            }
        } else {
            $this->data['posttype'] = $this->input->post('type');
            $this->data["subview"] = "/online_exam/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datetimepicker/datetimepicker.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/datetimepicker/moment.js',
                'assets/datetimepicker/datetimepicker.js',
                'assets/select2/select2.js'
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['online_exam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $id));
            if($this->data['online_exam']) {
                $this->data['classes'] = $this->classes_m->get_join_classes();
                $this->data['usertypes'] = $this->usertype_m->get_usertype();
                $this->data['instructions'] = $this->instruction_m->get_order_by_instruction();
                $this->data['types'] = $this->exam_type_m->get_order_by_exam_type(['status' => 1]);
                $this->data['groups'] = $this->studentgroup_m->get_order_by_studentgroup();
                $this->data['sections'] = [];
                $this->data['subjects'] = [];
                if(isset($this->data['online_exam']->classID)) {
                    $this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $this->data['online_exam']->classID));
                    $this->data['subjects'] = $this->subject_m->get_order_by_subject(array('classesID' => $this->data['online_exam']->classID));
                }
                $this->data['userTypeID'] = 3;
                if($_POST) {
                    $this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $this->input->post('classes')));
                    $this->data['subjects'] = $this->subject_m->get_order_by_subject(array('classesID' => $this->input->post('classes')));
                    $this->data['posttype'] = $this->input->post('type');
                    $rules = $this->rules($this->data['posttype'], $this->input->post('ispaid'));
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/online_exam/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $inputs = $this->input->post();
                        $databasePair = [
                            'name' => 'name',
                            'description' => 'description',
                            'usertype' => 'userTypeID',
                            'classes' => 'classID',
                            'section' => 'sectionID',
                            'studentGroup' => 'studentGroupID',
                            'subject' => 'subjectID',
                            'instruction' => 'instructionID',
                            'duration' => 'duration',
                            'type' => 'examTypeNumber',
                            'markType' => 'markType',
                            'percentage' => 'percentage',
                            'ispaid' => 'paid',
                            'validDays' => 'validDays',
                            'cost' => 'cost',
                            'judge' => 'judge'
                        ];
                        if($inputs['type'] == 4) {
                            $databasePair ['startdate'] = 'startDateTime';
                            $databasePair ['enddate'] = 'endDateTime';
                        } elseif ($inputs['type'] == 5) {
                            $databasePair ['startdatetime'] = 'startDateTime';
                            $databasePair ['enddatetime'] = 'endDateTime';
                        }

                        $array = [];
                        $f = 1;
                        foreach ($databasePair as $key => $database) {
                            if($inputs[$key] != "") {
                                if($database == 'startDateTime' || $database == 'endDateTime') {
                                    $f = 0;
                                    $array[$database] = date('Y-m-d H:i:s', strtotime($inputs[$key]));
                                } else {
                                    $array[$database] = $inputs[$key];
                                }
                            }
                        }
                        if($f) {
                            $array['startDateTime'] = NULL;
                            $array['endDateTime'] = NULL;
                        }

                        if($this->input->post('ispaid') == 0) {
                            $array['cost'] = 0;
                        }

                        $array['examStatus'] = $this->input->post('examStatus');
                        $array['published']   = $this->input->post('published');
                        $array['modify_date'] = date("Y-m-d H:i:s");

                        $this->online_exam_m->update_online_exam($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("online_exam/index"));
                    }
                } else {
                    $this->data['posttype'] = $this->data['online_exam']->examTypeNumber;
                    $this->data["subview"] = "/online_exam/edit";
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
            $this->data['online_exam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $id));
            if($this->data['online_exam']) {
                $this->online_exam_m->delete_online_exam($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("online_exam/index"));
            } else {
                redirect(base_url("online_exam/index"));
            }
        } else {
            redirect(base_url("online_exam/index"));
        }
    }

    public function addquestion() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/checkbox/checkbox.css',
            )
        );
        $onlineExamID = htmlentities(escapeString($this->uri->segment(3)));

        $this->data['onlineExamID'] = $onlineExamID;
        $this->addQuestionDatabase(true, $onlineExamID);
        $this->data['levels'] = $this->question_level_m->get_order_by_question_level();
        $this->data['groups'] = $this->question_group_m->get_order_by_question_group();
        $onlineExam = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID]);
        if(!is_null($onlineExam)) {
            $this->data['class'] = $this->classes_m->get_classes($onlineExam->classID);
            $this->data['section'] = $this->section_m->get_section($onlineExam->sectionID);
            $this->data['studentGroup'] = $this->studentgroup_m->get_studentgroup($onlineExam->studentGroupID);
            $this->data['instruction'] = $this->instruction_m->get_instruction($onlineExam->instructionID);
            $this->data['examType'] = $this->exam_type_m->get_single_exam_type(['examTypeNumber' => $onlineExam->examTypeNumber]);
            $this->data['subject'] = $this->subject_m->get_subject($onlineExam->subjectID);
        }
        $this->data['onlineExam'] = $onlineExam;
        $this->data["subview"] = "/online_exam/addquestion";
        $this->load->view('_layout_main', $this->data);
    }

    public function showQuestions() {
        $inputs = $this->input->post();
        $where = [];
        if($inputs['levelID']) {
            $where['levelID'] = $inputs['levelID'];
        }
        if($inputs['groupID']) {
            $where['groupID'] = $inputs['groupID'];
        }
        $this->data['questions'] = $this->question_bank_m->get_order_by_question_bank($where);
        $this->data['types'] = pluck($this->question_type_m->get_order_by_question_type(), 'obj', 'typeNumber');

        echo $this->load->view('/online_exam/questionList', $this->data, true);
    }

    public function addQuestionDatabase($initial = false, $onlineExamID = 0, $questionID = 0) {
        if(!$initial) {
            $onlineExamID = $this->input->post('onlineExamID');
            $questionID = $this->input->post('questionID');
            $haveExamQuestion = $this->online_exam_question_m->get_order_by_online_exam_question([
                'onlineExamID' => $onlineExamID,
                'questionID' => $questionID
            ]);
            if(!count($haveExamQuestion)) {
                $this->online_exam_question_m->insert_online_exam_question([
                    'onlineExamID' => $onlineExamID,
                    'questionID' => $questionID
                ]);
            }
        }

        $this->data['onlineExamQuestions'] = $this->online_exam_question_m->get_order_by_online_exam_question([
            'onlineExamID' => $onlineExamID
        ]);
        $this->data['questions'] = pluck($this->question_bank_m->get_order_by_question_bank(), 'obj', 'questionBankID');
        $allOptions = $this->question_option_m->get_order_by_question_option();
        $options = [];
        foreach ($allOptions as $option) {
            if($option->name == "" && $option->img == "") continue;
            $options[$option->questionID][] = $option;
        }
        $this->data['options'] = $options;
        $allAnswers = $this->question_answer_m->get_order_by_question_answer();
        $answers = [];
        foreach ($allAnswers as $answer) {
            $answers[$answer->questionID][] = $answer;
        }
        $this->data['answers'] = $answers;
        $showArray['associateQuestionList'] = $this->load->view('/online_exam/associateQuestionList', $this->data, true);
        $showArray['questionSummary'] = $this->load->view('/online_exam/questionSummary', $this->data, true);
        $this->data['associateQuestionList'] = $showArray['associateQuestionList'];
        $this->data['questionSummary'] = $showArray['questionSummary'];
        $this->data['updateView'] = $showArray;
        if(!$initial) {
            echo json_encode($showArray);
        }
    }

    public function removeQuestionDatabase() {
        $onlineExamQuestionID = $this->input->post('onlineExamQuestionID');
        $onlineExamID = $this->input->post('onlineExamID');
        $this->online_exam_question_m->delete_online_exam_question($onlineExamQuestionID);
        $this->addQuestionDatabase(true, $onlineExamID);
        echo json_encode($this->data['updateView']);
    }

    public function getSection() {
        $id = $this->input->post('id');
        if((int)$id) {
            $allSection = $this->section_m->get_order_by_section(array('classesID' => $id));

            echo "<option value='0'>", $this->lang->line("online_exam_select"),"</option>";

            foreach ($allSection as $value) {
                echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
            }

        }
    }

    public function getSubject() {
        $classID = $this->input->post('classID');
        if((int)$classID) {
            $allSubject = $this->subject_m->get_order_by_subject(array('classesID' => $classID));

            echo "<option value=''>", $this->lang->line("online_exam_select"),"</option>";

            foreach ($allSubject as $value) {
                echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
            }

        }
    }

    public function unique_type() {
        if($this->input->post('type') == 0) {
            $this->form_validation->set_message("unique_type", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_markType() {
        if($this->input->post('markType') == 0) {
            $this->form_validation->set_message("unique_markType", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_section() {
        if($this->input->post('classes')) {
            if($this->input->post('section') == 0) {
                $this->form_validation->set_message("unique_section", "The %s field is required");
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }

    public function check_exam_question() {
        $onlineexamID = htmlentities(escapeString($this->uri->segment(3)));
        $published = $this->input->post('published');
        if((int)$onlineexamID) {
            $online_exam_questions = $this->online_exam_question_m->get_order_by_online_exam_question(array('onlineExamID'=>$onlineexamID));
            if((count($online_exam_questions) == 0) && ($published == 1)) {
                $this->form_validation->set_message("check_exam_question", "Please add some question and publish this exam.");
                return FALSE;
            }
            return TRUE;
        } else {
            if($published == 1) {
                $this->form_validation->set_message("check_exam_question", "Please add some question and publish this exam.");
                return FALSE;
            }
            return TRUE;
        }
    }

}
