<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Omnipay\Omnipay;
Class Take_exam extends Admin_Controller {
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
        $this->load->model('online_exam_m');
        $this->load->model('online_exam_payment_m');
        $this->load->model('online_exam_question_m');
        $this->load->model('instruction_m');
        $this->load->model('question_bank_m');
        $this->load->model('question_option_m');
        $this->load->model('question_answer_m');
        $this->load->model('online_exam_user_answer_m');
        $this->load->model('online_exam_user_status_m');
        $this->load->model('online_exam_user_answer_option_m');
        $this->load->model('student_m');
        $this->load->model('classes_m');
        $this->load->model('student_m');
        $this->load->model('section_m');
        $this->load->model('paymentsettings_m');
        $this->load->model('subject_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('take_exam', $language);
        require_once(APPPATH."libraries/Omnipay/vendor/autoload.php");
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

        $this->data['payments'] = pluck_multi_array($this->online_exam_payment_m->get_order_by_online_exam_payment(array('usertypeID' => $this->session->userdata('usertypeID'), 'userID' => $this->session->userdata('loginuserID'))), 'obj', 'online_examID');

        $this->data['paindingpayments'] = pluck($this->online_exam_payment_m->get_order_by_online_exam_payment(array('usertypeID' => $this->session->userdata('usertypeID'), 'userID' => $this->session->userdata('loginuserID'), 'status' => 0)), 'obj', 'online_examID');

        $this->data['paymentsetting'] = $this->paymentsettings_m->get_paymentsetting();
        $this->data['examStatus'] = pluck($this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('userID'=>$loginuserID)),'obj','onlineExamID');


        $this->data['usertypeID']   = $usertypeID;
        $this->data['onlineExams'] = $this->online_exam_m->get_order_by_online_exam(array('usertypeID'=>$usertypeID, 'published'=>1));

        $this->data['validationErrors'] = [];
        $this->data['validationOnlineExamID'] = 0;
        if($_POST) {
            $rules = $this->payment_rules($this->input->post('paymentMethod'));
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE) {
                $this->data['validationOnlineExamID'] = $this->input->post('onlineExamID');
                $this->data['validationErrors'] = $this->form_validation->error_array();
                $this->data["subview"] = "online_exam/take_exam/index";
                $this->load->view('_layout_main', $this->data);
            } else {
                $this->post_data = $this->input->post();
                $this->invoice_data = [];
                if(isset($this->post_data['onlineExamID'])) {
                    $this->invoice_data = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $this->post_data['onlineExamID']));

                    if(($this->invoice_data->paid == 1) && ((float)$this->invoice_data->cost == 0)) {
                        $this->session->set_flashdata('error', 'Exam amount can not be zero');
                        redirect(base_url('take_exam/index'));
                    }

                    if(($this->invoice_data->examStatus == 1) && ($this->invoice_data->paid == 1) && isset($this->data['paindingpayments'][$this->invoice_data->onlineExamID])) {
                        $this->session->set_flashdata('error', 'This exam price already paid');
                        redirect(base_url('take_exam/index'));
                    }


                    if($this->input->post('paymentMethod') == 'Paypal') {
                        $this->paypal();
                    } elseif($this->input->post('paymentMethod') == 'Stripe') {
                        $this->stripe();
                    } elseif($this->input->post('paymentMethod') == 'Payumoney') {
                        $this->payumoney();
                    } elseif($this->input->post('paymentMethod') == 'Voguepay') {
                        $this->voguepay();
                    }
                } else {
                    $this->session->set_flashdata('error', 'Exam does not found');
                    redirect(base_url('take_exam/index'));
                }
            }
        } else {
            $this->data["subview"] = "online_exam/take_exam/index";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function show() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/checkbox/checkbox.css',
                'assets/inilabs/form/fuelux.min.css'
            )
        );
        $this->data['footerassets'] = array(
            'js' => array(
                'assets/inilabs/form/fuelux.min.js'
            )
        );


        $userID = $this->session->userdata("loginuserID");
        $onlineExamID = htmlentities(escapeString($this->uri->segment(3)));

        $examGivenStatus     = FALSE;
        $examGivenDataStatus = FALSE;
        $examExpireStatus    = FALSE;
        $examSubjectStatus   = FALSE;

        if((int) $onlineExamID) {
            $this->data['student'] = $this->student_m->get_student($userID);
            if(count($this->data['student'])) {
                $array['classesID'] = $this->data['student']->classesID;
                $array['sectionID'] = $this->data['student']->sectionID;
                $array['studentgroupID'] = $this->data['student']->studentgroupID;
                $array['onlineExamID'] = $onlineExamID;
                $online_exam = $this->online_exam_m->get_online_exam_by_student($array);


                $userExamCheck = $this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('userID'=>$userID,'classesID'=>$array['classesID'],'sectionID'=>$array['sectionID'],'onlineExamID'=> $onlineExamID));
                if(count($online_exam)) {
                    $DDonlineExam = $online_exam;
                    $DDexamStatus = $userExamCheck;

                    $currentdate = 0;
                    if($DDonlineExam->examTypeNumber == '4') {
                        $presentDate = strtotime(date('Y-m-d'));
                        $examStartDate = strtotime($DDonlineExam->startDateTime);
                        $examEndDate = strtotime($DDonlineExam->endDateTime);
                    } elseif($DDonlineExam->examTypeNumber == '5') {
                        $presentDate = strtotime(date('Y-m-d H:i:s'));
                        $examStartDate = strtotime($DDonlineExam->startDateTime);
                        $examEndDate = strtotime($DDonlineExam->endDateTime);
                    }

                    if($DDonlineExam->examTypeNumber == '4' || $DDonlineExam->examTypeNumber == '5') {
                        if($presentDate >= $examStartDate && $presentDate <= $examEndDate) {
                            $examGivenStatus = TRUE;
                        } elseif($presentDate > $examStartDate && $presentDate > $examEndDate) {
                            $examExpireStatus = TRUE;
                        }
                    } else {
                        $examGivenStatus = TRUE;
                    }

                    if($examGivenStatus) {
                        $examGivenStatus = FALSE;
                        if($DDonlineExam->examStatus == 2) {
                            $examGivenStatus = TRUE;
                        } else {
                            $userExamCheck = pluck($userExamCheck,'obj','onlineExamID');
                            if(isset($userExamCheck[$DDonlineExam->onlineExamID])) {
                                $examGivenDataStatus = TRUE;
                            } else {
                                $examGivenStatus = TRUE;
                            }
                        }
                    }

                    if($examGivenStatus) {
                        if((int)$DDonlineExam->subjectID && (int)$DDonlineExam->classID) {
                            $examGivenStatus = FALSE;
                            $userSubjectPluck = pluck($this->subject_m->get_order_by_subject(array('type' => 1)), 'subjectID', 'subjectID');
                            $optionalSubject = $this->subject_m->get_single_subject(array('type' => 0, 'subjectID' => $this->data['student']->optionalsubjectID));
                            if(count($optionalSubject)) {
                                $userSubjectPluck[$optionalSubject->subjectID] = $optionalSubject->subjectID;
                            }

                            if(in_array($DDonlineExam->subjectID, $userSubjectPluck)) {
                                $examGivenStatus = TRUE;
                            } else {
                                $examSubjectStatus = FALSE;
                            }
                        } else {
                            $examSubjectStatus = TRUE;
                        }
                    } else {
                        $examSubjectStatus = TRUE;
                    }
                }
                $this->data['class'] = $this->classes_m->get_classes($this->data['student']->classesID);
            } else {
                $this->data['class'] = array();
            }

            if(count($this->data['student'])) {
                $this->data['section'] = $this->section_m->get_section($this->data['student']->sectionID);
            } else {
                $this->data['section'] = array();
            }

            $this->data['onlineExam'] = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID]);
            if(count($online_exam)) {
                $onlineExamQuestions = $this->online_exam_question_m->get_order_by_online_exam_question(['onlineExamID' => $onlineExamID]);

                $allOnlineExamQuestions = $onlineExamQuestions;

                if($this->data['onlineExam']->random != 0) {
                    $onlineExamQuestions = $this->randAssociativeArray($onlineExamQuestions, $this->data['onlineExam']->random);
                }

                $this->data['onlineExamQuestions'] = $onlineExamQuestions;
                $onlineExamQuestions = pluck($onlineExamQuestions, 'obj', 'questionID');
                $questionsBank = pluck($this->question_bank_m->get_order_by_question_bank(), 'obj', 'questionBankID');
                $this->data['questions'] = $questionsBank;


                $options = [];
                $answers = [];
                $allOptions = [];
                $allAnswers = [];
                if(count($allOnlineExamQuestions)) {
                    $pluckOnlineExamQuestions = pluck($allOnlineExamQuestions, 'questionID');
                    $allOptions = $this->question_option_m->get_where_in_question_option($pluckOnlineExamQuestions, 'questionID');
                    foreach ($allOptions as $option) {
                        if($option->name == "" && $option->img == "") continue;
                        $options[$option->questionID][] = $option;
                    }
                    $this->data['options'] = $options;
                    
                    $allAnswers = $this->question_answer_m->get_where_in_question_answer($pluckOnlineExamQuestions, 'questionID');
                    foreach ($allAnswers as $answer) {
                        $answers[$answer->questionID][] = $answer;
                    }
                    $this->data['answers'] = $answers;
                } else {
                    $this->data['options'] = $options;
                    $this->data['answers'] = $answers;
                }



                if($_POST) {
                    $time = date("Y-m-d h:i:s");
                    $mainQuestionAnswer = [];
                    $userAnswer = $this->input->post('answer');

                    foreach ($allAnswers as $answer) {
                        if($answer->typeNumber == 3) {
                            $mainQuestionAnswer[$answer->typeNumber][$answer->questionID][$answer->answerID] = $answer->text;
                        } else {
                            $mainQuestionAnswer[$answer->typeNumber][$answer->questionID][] = $answer->optionID;
                        }
                    }

                    $questionStatus = [];
                    $correctAnswer = 0;
                    $totalQuestionMark = 0;
                    $totalCorrectMark = 0;
                    $visited = [];
                    
                    $totalAnswer = 0;
                    if(count($userAnswer)) {
                        foreach ($userAnswer as $userAnswerKey => $uA) {
                            $totalAnswer += count($uA);
                        }
                    }

                    if(count($allOnlineExamQuestions)) {
                        foreach ($allOnlineExamQuestions as $aoeq) {    
                            if(isset($questionsBank[$aoeq->questionID])) {
                                $totalQuestionMark += $questionsBank[$aoeq->questionID]->mark; 
                            }
                        }
                    }

                    $f = 0;
                    foreach ($mainQuestionAnswer as $typeID => $questions) {
                        if(!isset($userAnswer[$typeID])) continue;
                        foreach ($questions as $questionID => $options) {
                            if(isset($onlineExamQuestions[$questionID])) {
                                $onlineExamQuestionID = $onlineExamQuestions[$questionID]->onlineExamQuestionID;
                                $onlineExamUserAnswerID = $this->online_exam_user_answer_m->insert([
                                    'onlineExamQuestionID' => $onlineExamQuestionID,
                                    'userID' => $userID
                                ]);
                            }

                            if(isset($userAnswer[$typeID][$questionID])) {
                                $totalCorrectMark += isset($questionsBank[$questionID]) ? $questionsBank[$questionID]->mark : 0;

                                $questionStatus[$questionID] = 1;
                                $correctAnswer++;
                                $f = 1;
                                if($typeID == 3) {
                                    foreach ($options as $answerID => $answer) {
                                        $takeAnswer = strtolower($answer);
                                        $getAnswer = isset($userAnswer[$typeID][$questionID][$answerID]) ? strtolower($userAnswer[$typeID][$questionID][$answerID]) : '';
                                        $this->online_exam_user_answer_option_m->insert([
                                            'questionID' => $questionID,
                                            'typeID' => $typeID,
                                            'text' => $getAnswer,
                                            'time' => $time
                                        ]);
                                        if($getAnswer != $takeAnswer) {
                                            $f = 0;
                                        }
                                    }
                                } elseif($typeID == 1 || $typeID == 2) {
                                    if(count($options) != count($userAnswer[$typeID][$questionID])) {
                                        $f = 0;
                                    } else {
                                        if(!isset($visited[$typeID][$questionID])) {
                                            foreach ($userAnswer[$typeID][$questionID] as $userOption) {
                                                $this->online_exam_user_answer_option_m->insert([
                                                    'questionID' => $questionID,
                                                    'optionID' => $userOption,
                                                    'typeID' => $typeID,
                                                    'time' => $time
                                                ]);
                                            }
                                            $visited[$typeID][$questionID] = 1;
                                        }
                                        foreach ($options as $answerID => $answer) {
                                            if(!in_array($answer, $userAnswer[$typeID][$questionID])) {
                                                $f = 0;
                                                break;
                                            }
                                        }
                                    }
                                }

                                if(!$f) {
                                    $questionStatus[$questionID] = 0;
                                    $correctAnswer--;
                                    $totalCorrectMark -= $questionsBank[$questionID]->mark;
                                }
                            }
                        }
                    }


                    $examtime = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('userID' => $userID, 'onlineExamID' => $onlineExamID));

                    $examTimeCounter = 1;
                    if(count($examtime)) {
                        $examTimeCounter = $examtime->examtimeID;
                        $examTimeCounter++;
                    }


                    $statusID = 10;
                    if(count($this->data['onlineExam'])) {
                        if($this->data['onlineExam']->markType == 5) {

                            $percentage = 0;
                            if($totalCorrectMark > 0 && $totalQuestionMark > 0) {
                                $percentage = (($totalCorrectMark/$totalQuestionMark)*100);
                            } 

                            if($percentage >= $this->data['onlineExam']->percentage) {
                                $statusID = 5;
                            } else {
                                $statusID = 10;
                            }
                        } elseif($this->data['onlineExam']->markType == 10) {
                            if($totalCorrectMark >= $this->data['onlineExam']->percentage) {
                                $statusID = 5;
                            } else {
                                $statusID = 10;
                            }
                        }
                    }

                    $this->online_exam_user_status_m->insert([
                        'onlineExamID' => $this->data['onlineExam']->onlineExamID,
                        'time' => $time,
                        'totalQuestion' => count($onlineExamQuestions),
                        'totalAnswer' => $totalAnswer,
                        'nagetiveMark' => $this->data['onlineExam']->negativeMark,
                        'duration' => $this->data['onlineExam']->duration,
                        'score' => $correctAnswer,
                        'userID' => $userID,
                        'classesID' => count($this->data['class']) ? $this->data['class']->classesID : 0,
                        'sectionID' => count($this->data['section']) ? $this->data['section']->sectionID : 0,
                        'examtimeID' => $examTimeCounter,

                        'totalCurrectAnswer' => $correctAnswer,
                        'totalMark' => $totalQuestionMark,
                        'totalObtainedMark' => $totalCorrectMark,
                        'totalPercentage' => (($totalCorrectMark > 0 && $totalQuestionMark > 0) ? (($totalCorrectMark/$totalQuestionMark)*100) : 0),
                        'statusID' => $statusID,
                    ]);

                    if($this->data['onlineExam']->paid) {
                        $onlineExamPayments = $this->online_exam_payment_m->get_single_online_exam_payment_only_first_row(array('online_examID' => $this->data['onlineExam']->onlineExamID, 'status' => 0, 'usertypeID' => $this->session->userdata('usertypeID'), 'userID' => $this->session->userdata('loginuserID')));

                        if($onlineExamPayments->online_exam_paymentID != NULL) {
                            $onlineExamPaymentArray = [
                                'status' => 1
                            ];
                            $this->online_exam_payment_m->update_online_exam_payment($onlineExamPaymentArray, $onlineExamPayments->online_exam_paymentID);
                        }
                    }
                    

                    $this->data['fail'] = $f;
                    $this->data['questionStatus'] = $questionStatus;
                    $this->data['totalAnswer'] = $totalAnswer;
                    $this->data['correctAnswer'] = $correctAnswer;
                    $this->data['totalCorrectMark'] = $totalCorrectMark;
                    $this->data['totalQuestionMark'] = $totalQuestionMark;
                    $this->data['userExamCheck'] = $userExamCheck;
                    $this->data["subview"] = "online_exam/take_exam/result";
                    return $this->load->view('_layout_main', $this->data);
                }

                if($examGivenStatus) {
                    $this->data["subview"] = "online_exam/take_exam/question";
                    return $this->load->view('_layout_main', $this->data);
                } else {
                    if($examGivenDataStatus) {
                        $this->data['online_exam'] = $online_exam;
                        $userExamCheck = pluck($userExamCheck,'obj','onlineExamID');
                        $this->data['userExamCheck'] = isset($userExamCheck[$onlineExamID]) ? $userExamCheck[$onlineExamID] : [];
                        $this->data["subview"] = "online_exam/take_exam/checkexam";
                        return $this->load->view('_layout_main', $this->data);
                    } else {
                        if($examExpireStatus) {
                            $this->data['examsubjectstatus'] = $examSubjectStatus;
                            $this->data['expirestatus'] = $examExpireStatus;
                            $this->data['upcomingstatus'] = FALSE;
                            $this->data['online_exam'] = $online_exam;
                            $this->data["subview"] = "online_exam/take_exam/expireandupcoming";
                            return $this->load->view('_layout_main', $this->data);
                        } else {
                            $this->data['examsubjectstatus'] = $examSubjectStatus;
                            $this->data['expirestatus'] = $examExpireStatus;
                            $this->data['upcomingstatus'] = TRUE;
                            $this->data['online_exam'] = $online_exam;
                            $this->data["subview"] = "online_exam/take_exam/expireandupcoming";
                            return $this->load->view('_layout_main', $this->data);
                        }
                    }
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

    public function instruction() 
    {
        $onlineExamID = htmlentities(escapeString($this->uri->segment(3)));
        if((int) $onlineExamID) {
            $instructions = pluck($this->instruction_m->get_order_by_instruction(), 'obj', 'instructionID');
            $onlineExam = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID]);
            $this->data['onlineExam'] = $onlineExam;
            if(!isset($instructions[$onlineExam->instructionID])) {
                redirect(base_url('take_exam/show/'.$onlineExamID));
            }
            $this->data['instruction'] = $instructions[$onlineExam->instructionID];
            $this->data["subview"] = "online_exam/take_exam/instruction";
            return $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function randAssociativeArray($array, $number = 0) 
    {
        $returnArray = [];
        $countArray = count($array);
        if($number > $countArray || $number == 0) {
            $number = $countArray;
        }

        if($countArray == 1) {
            $randomKey[] = 0;
        } else {
            if(count($array)) {
                $randomKey = array_rand($array, $number);
            } else {
                $randomKey = [];
            }
        }

        if(is_array($randomKey)) {
            shuffle($randomKey);
        }

        if(count($randomKey)) {
            foreach ($randomKey as $key) {
                $returnArray[] = $array[$key];
            }
            return $returnArray;
        } else {
            return $array;
        }
    }

    public function getpaymentinfo()
    {
        $onlineExamID = $this->input->post('onlineExamID');
        
        $retArray['status'] = FALSE;
        $retArray['payableamount'] = 0.00; 
        if(permissionChecker('take_exam')) {
            if(!empty($onlineExamID) && (int)$onlineExamID && $onlineExamID > 0) {
                $onlineExam = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $onlineExamID));
                if(count($onlineExam)) {
                    $retArray['status'] = TRUE;
                    $retArray['payableamount'] = sprintf ("%.2f", $onlineExam->cost);
                }
            }
        }   

        echo json_encode($retArray);
        exit;
    }

    public function paymentlist()
    {
        $onlineExamID = $this->input->post('onlineExamID');
        if(!empty($onlineExamID) && (int)$onlineExamID && $onlineExamID > 0) {
            $onlineExam = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $onlineExamID));
            if(count($onlineExam)) {
                $onlineExamPayments = $this->online_exam_payment_m->get_order_by_online_exam_payment(array('online_examID' => $onlineExamID, 'usertypeID' => $this->session->userdata('usertypeID'), 'userID' => $this->session->userdata('loginuserID')));
                if(count($onlineExamPayments)) {
                    $i = 1; 
                    foreach ($onlineExamPayments as $onlineExamPayment) {
                        echo '<tr>';
                            echo '<td data-title="'.$this->lang->line('slno').'">';
                                echo $i;
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('take_exam_payment_date').'">';
                                echo date('d M Y', strtotime($onlineExamPayment->paymentdate));
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('take_exam_payment_method').'">';
                                echo $onlineExamPayment->paymentmethod;
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('take_exam_exam_status').'">';
                                if($onlineExamPayment->status) {
                                    echo $this->lang->line('take_exam_complete');
                                } else {
                                    echo $this->lang->line('take_exam_pending');
                                }
                            echo '</td>';
                        echo '</tr>';
                    } 
                }
            }
        }
    }

    protected function payment_rules($type = NULL) 
    {
        $rules = array(
            array(
                'field' => 'paymentMethod',
                'label' => $this->lang->line("take_exam_payment_method"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_paymentmethod'
            ),
            array(
                'field' => 'paymentAmount',
                'label' => $this->lang->line("take_exam_payment_amount"),
                'rules' => 'trim|required|xss_clean|max_length[16]'
            )
        );

        if($type == 'Stripe') {
            $rules[] = array(
                'field' => 'stripe_card_number',
                'label' => $this->lang->line("take_exam_card_number"),
                'rules' => 'trim|required|xss_clean|min_length[16]|max_length[16]'
            );
            $rules[] = array(
                'field' => 'stripe_expire_month',
                'label' => $this->lang->line("take_exam_month"),
                'rules' => 'trim|required|xss_clean|min_length[2]|max_length[2]'
            );
            $rules[] = array(
                'field' => 'stripe_expire_year',
                'label' => $this->lang->line("take_exam_year"),
                'rules' => 'trim|required|xss_clean|min_length[4]|max_length[4]'
            );
            $rules[] = array(
                'field' => 'stripe_cvv',
                'label' => $this->lang->line("take_exam_cvv"),
                'rules' => 'trim|required|xss_clean|max_length[40]'
            );
        } elseif($type == 'Payumoney') {
            $rules[] = array(
                'field' => 'payumoney_first_name',
                'label' => $this->lang->line("take_exam_first_name"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            );
            $rules[] = array(
                'field' => 'payumoney_email',
                'label' => $this->lang->line("take_exam_email"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            );
            $rules[] = array(
                'field' => 'payumoney_phone',
                'label' => $this->lang->line("take_exam_phone"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            );
        }

        return $rules;
    }

    public function unique_paymentmethod() 
    {
        if($this->input->post('paymentMethod') === 'Select') {
            $this->form_validation->set_message("unique_paymentmethod", "Payment method is required");
            return FALSE;
        } else {
            $api_config = [];
            $get_configs = $this->paymentsettings_m->get_order_by_config();
            foreach ($get_configs as $key => $get_key) {
                $api_config[$get_key->fieldoption] = $get_key->value;
            }

            if($this->input->post('paymentMethod') == 'Paypal' && $api_config['paypal_status'] == 1) {
                if($api_config['paypal_api_username'] =="" || $api_config['paypal_api_password'] =="" || $api_config['paypal_api_signature']==""){
                    $this->form_validation->set_message("unique_paymentmethod", "Paypal settings required");
                    return FALSE;
                }
                return TRUE;
            } elseif($this->input->post('paymentMethod') == 'Stripe' && $api_config['stripe_status'] == 1) {
                if($api_config['stripe_secret'] ==""){
                    $this->form_validation->set_message("unique_paymentmethod", "Stripe settings required");
                    return FALSE;
                }
                return TRUE;
            } elseif($this->input->post('paymentMethod') == 'Payumoney' && $api_config['payumoney_status'] == 1) {
                if($api_config['payumoney_key'] =="" || $api_config['payumoney_salt'] == "") {
                    $this->form_validation->set_message("unique_paymentmethod", "Payumoney settings required");
                    return FALSE;
                }
                return TRUE;
            } elseif ($this->input->post('paymentMethod') == 'Voguepay' && $api_config['voguepay_status'] == 1) {
                if($api_config['voguepay_merchant_id'] =="" || $api_config['voguepay_merchant_ref'] == "" || $api_config['voguepay_developer_code'] == "") {
                    $this->form_validation->set_message("unique_paymentmethod", "Voguepay settings required");
                    return FALSE;
                }
                return TRUE;
            } else {
                $this->form_validation->set_message("unique_paymentmethod", "Payment settings required");
                return FALSE;
            }
        }
    }

    public function paymentChecking()
    {
        $onlineExamID = $this->input->post('onlineExamID');
        $status = 'FALSE';
        $paymentExpireStatus = TRUE;
        if($onlineExamID > 0) {
            $onlineExam = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $onlineExamID));
            if(count($onlineExam)) {
                if(($onlineExam->examStatus == 2) && ($onlineExam->paid == 1)) {

                    if($onlineExam->examTypeNumber == '4') {
                        $presentDate = strtotime(date('Y-m-d'));
                        $examStartDate = strtotime($onlineExam->startDateTime);
                        $examEndDate = strtotime($onlineExam->endDateTime);
                    } elseif($onlineExam->examTypeNumber == '5') {
                        $presentDate = strtotime(date('Y-m-d H:i:s'));
                        $examStartDate = strtotime($onlineExam->startDateTime);
                        $examEndDate = strtotime($onlineExam->endDateTime);
                    }

                    if($onlineExam->examTypeNumber == '4' || $onlineExam->examTypeNumber == '5') {        
                        if($presentDate > $examStartDate && $presentDate > $examEndDate) {
                            $paymentExpireStatus = FALSE;
                        }
                    }

                    if($paymentExpireStatus) {
                        $onlineExamPayments = $this->online_exam_payment_m->get_single_online_exam_payment_only_first_row(array('online_examID' => $onlineExamID, 'status' => 0, 'usertypeID' => $this->session->userdata('usertypeID'), 'userID' => $this->session->userdata('loginuserID')));
                        if($onlineExamPayments->online_exam_paymentID == NULL) {
                            $status = 'TRUE';
                        }
                    }

                }
            }
        }

        echo $status;
    }

    private function paypal()
    {
        $api_config = [];
        $get_configs = $this->paymentsettings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->fieldoption] = $get_key->value;
        }

        if($api_config['paypal_api_username'] =="" || $api_config['paypal_api_password'] =="" || $api_config['paypal_api_signature']==""){
            $this->session->set_flashdata('error', 'PayPal settings not available');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->item_data = $this->post_data;
            $this->invoice_info = $this->invoice_data;

            (float) $this->item_data['paymentAmount'];
            $params = array(
                'cancelUrl'     => base_url('take_exam/getCancelPayment'),
                'returnUrl'     => base_url('take_exam/getSuccessPayment'),
                'onlineExamID'  => $this->item_data['onlineExamID'],
                'description'   => $this->invoice_info->name,
                'amount'        => floatval($this->item_data['paymentAmount']),
                'currency'      => $this->data["siteinfos"]->currency_code,
                'allpost'       => $this->item_data,
            );

            $this->session->set_userdata("params", $params);

            $paypalMode = (($api_config['paypal_demo'] === 'TRUE') ? (bool) true  : (bool) false) ; 
            $gateway = Omnipay::create('PayPal_Express');
            $gateway->setUsername($api_config['paypal_api_username']);
            $gateway->setPassword($api_config['paypal_api_password']);
            $gateway->setSignature($api_config['paypal_api_signature']);
            $gateway->setTestMode($paypalMode);
            $response = $gateway->purchase($params)->send();
            if ($response->isSuccessful()) {
                // payment was successful: update database
            } elseif ($response->isRedirect()) {
                $response->redirect();
            } else {
              echo $response->getMessage();
            }
        }
    }

    public function getCancelPayment() 
    {
        $params = $this->session->userdata('params');
        redirect(base_url('take_exam/index'));
    }

    public function getSuccessPayment() 
    {
        $api_config = array();
        $get_configs = $this->paymentsettings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->fieldoption] = $get_key->value;
        }
        
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername($api_config['paypal_api_username']);
        $gateway->setPassword($api_config['paypal_api_password']);
        $gateway->setSignature($api_config['paypal_api_signature']);

        $gateway->setTestMode($api_config['paypal_demo']);

        $params = $this->session->userdata('params');
        $response = $gateway->completePurchase($params)->send();
        $paypalResponse = $response->getData(); // this is the raw response object
        $purchaseId = $_GET['PayerID'];
        if(isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {
            if($purchaseId) {
                $paypalTransactionID = $paypalResponse['PAYMENTINFO_0_TRANSACTIONID'];
                $dbTransactionID = $this->online_exam_payment_m->get_single_online_exam_payment(array('transactionID' => $paypalTransactionID));
                if(!count($dbTransactionID)) {
                    $onlineExamPayment = array(
                        'online_examID'     => $params['onlineExamID'],
                        'usertypeID'        => $this->session->userdata('usertypeID'),
                        'userID'            => $this->session->userdata('loginuserID'),
                        'paymentamount'     => $params['amount'],
                        'paymentmethod'     => $params['allpost']['paymentMethod'],
                        'paymentdate'       => date('Y-m-d'),
                        'paymentday'        => date('d'),
                        'paymentmonth'      => date('m'),
                        'paymentyear'       => date('Y'),
                        'transactionID'     => $paypalTransactionID,
                        'status'            => 0,
                    );

                    $this->online_exam_payment_m->insert_online_exam_payment($onlineExamPayment);
                    $this->session->set_flashdata('success', 'Payment successful');
                    redirect(base_url('take_exam/index'));
                } else {
                    $this->session->set_flashdata('error', 'Transaction ID already exist!');
                    redirect(base_url('take_exam/index'));
                }
            } else {
                $this->session->set_flashdata('error', 'Payer id not found!');
                redirect(base_url("take_exam/index"));
            }
        } else {
            $this->session->set_flashdata('error', 'Payment not success!');
            redirect(base_url("take_exam/index"));
        }
    }

    public function stripe() 
    {
        $api_config = [];
        $get_configs = $this->paymentsettings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->fieldoption] = $get_key->value;
        }

        if($api_config['stripe_secret'] =="") {
            $this->session->set_flashdata('error', 'Stripe settings not available');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->item_data    = $this->post_data;
            $this->invoice_info = $this->invoice_data;

            (float) $this->item_data['paymentAmount'];
            $params = array(
                'onlineExamID'  => $this->item_data['onlineExamID'],
                'description'   => $this->invoice_info->name,
                'amount'        => floatval($this->item_data['paymentAmount']),
                'currency'      => $this->data["siteinfos"]->currency_code,
                'allpost'       => $this->item_data,
            );

            $this->session->set_userdata("params", $params);

            try {
                $gateway = Omnipay::create('Stripe');
                $gateway->setApiKey($api_config['stripe_secret']);
                $gateway->setTestMode($api_config['stripe_demo']);

                $formData = array('number' => $this->item_data['stripe_card_number'], 'expiryMonth' => $this->item_data['stripe_expire_month'], 'expiryYear' => $this->item_data['stripe_expire_year'], 'cvv' => $this->item_data['stripe_cvv']);
                $paidAmount = number_format((float)($params['amount']), 2, '.', '');

                $response = $gateway->purchase(array(
                    'amount'   => $paidAmount,
                    'onlineExamID'  => $params['onlineExamID'],
                    'currency' => $this->data["siteinfos"]->currency_code,
                    'card'     => $formData)
                )->send();

                if ($response->isSuccessful()) {
                    if ($response->getData()['status'] === "succeeded") {
                        $stripTransactionID = $response->getData()['id'];
                        $dbTransactionID = $this->online_exam_payment_m->get_single_online_exam_payment(array('transactionID' => $stripTransactionID));
                        if(!count($dbTransactionID)) {
                            $onlineExamPayment = array(
                                'online_examID'     => $params['onlineExamID'],
                                'usertypeID'        => $this->session->userdata('usertypeID'),
                                'userID'            => $this->session->userdata('loginuserID'),
                                'paymentamount'     => $params['amount'],
                                'paymentmethod'     => $params['allpost']['paymentMethod'],
                                'paymentdate'       => date('Y-m-d'),
                                'paymentday'        => date('d'),
                                'paymentmonth'      => date('m'),
                                'paymentyear'       => date('Y'),
                                'transactionID'     => $stripTransactionID,
                                'status'            => 0,
                            );

                            $this->online_exam_payment_m->insert_online_exam_payment($onlineExamPayment);
                            $this->session->set_flashdata('success', 'Payment successful');
                            redirect(base_url('take_exam/index'));
                        } else {
                            $this->session->set_flashdata('error', 'Transaction ID already exist!');
                            redirect(base_url('take_exam/index'));
                        }
                    }
                    redirect(base_url('take_exam/index'));
                } elseif ($response->isRedirect()) {
                    $response->redirect();
                } else {
                    $this->session->set_flashdata('error', "Something went wrong!");
                    redirect(base_url('take_exam/index'));
                }
            } catch (\Exception $ex) {
                $this->session->set_flashdata('error', $ex->getMessage());
                redirect(base_url('take_exam/index'));
            }
        }
    }

    public function payumoney() 
    {
        $api_config = [];
        $get_configs = $this->paymentsettings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->fieldoption] = $get_key->value;
        }

        if($api_config['payumoney_key'] =="" || $api_config['payumoney_salt'] ==""){
            $this->session->set_flashdata('error', 'PayUMoney settings not available');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->item_data    = $this->post_data;
            $this->invoice_info = $this->invoice_data;
            $params = array(
                'cancelUrl'     => base_url('take_exam/payumoney_canceled'),
                'failedUrl'     => base_url('take_exam/payumoney_failed'),
                'returnUrl'     => base_url('take_exam/payumoney_successful'),
                'onlineExamID'  => $this->item_data['onlineExamID'],
                'description'   => $this->invoice_info->name,
                'amount'        => floatval($this->item_data['paymentAmount']),
                'currency'      => $this->data["siteinfos"]->currency_code,
                'allpost'       => $this->item_data,
            );

            $this->session->set_userdata("params", $params);
            if ($api_config['payumoney_demo'] == TRUE) {
                $api_link = "https://test.payu.in/_payment";
            } else {
                $api_link = "https://secure.payu.in/_payment";
            }
            $this->array['invoice'] = $this->invoice_info;
            $this->array['key'] = $api_config['payumoney_key'];
            $this->array['salt'] = $api_config['payumoney_salt'];
            $this->array['payu_base_url'] = $api_link;
            $this->array['surl'] = base_url('take_exam/payumoney_success/'.$this->item_data['onlineExamID']);
            $this->array['furl'] = base_url('take_exam/payumoney_failed/'.$this->item_data['onlineExamID']);
            $this->array['txnid'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            $this->array['action'] = $api_link;
            $this->array['amount'] = number_format($params['amount'], 2, '.', '');
            $this->array['firstname'] = $this->item_data['payumoney_first_name'];
            $this->array['email'] = $this->item_data['payumoney_email'];
            $this->array['phone'] = $this->item_data['payumoney_phone'];
            $this->array['productinfo'] = $this->invoice_info->name;
            $this->array['curl'] = base_url('take_exam/payumoney_canceled/'.$this->item_data['onlineExamID']);
            $this->array['service_provider'] = 'payu_paisa';
            $this->array['hash'] = $this->generateHash($this->array);

            $this->load->view('online_exam/take_exam/payumoney', $this->array);
        }
    }

    public function generateHash($array) 
    {
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        if(
            empty($array['key'])
            || empty($array['txnid'])
            || empty($array['amount'])
            || empty($array['firstname'])
            || empty($array['email'])
            || empty($array['phone'])
            || empty($array['productinfo'])
            || empty($array['surl'])
            || empty($array['furl'])
            || empty($array['service_provider'])
        ) {
            return false;
        } else {
            $hash         = '';
            $salt = $array['salt'];
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';
            foreach($hashVarsSeq as $hash_var) {
                $hash_string .= isset($array[$hash_var]) ? $array[$hash_var] : '';
                $hash_string .= '|';
            }
            $hash_string .= $salt;
            $hash = strtolower(hash('sha512', $hash_string));
            return $hash;
        }
    }

    public function payumoney_failed() 
    {
        $this->session->set_flashdata('error', "Payment failed!");
        redirect(base_url("take_exam/index"));
    }

    public function payumoney_success() 
    {
        $invoice = $this->uri->segment(3);
        $api_config = [];
        $get_configs = $this->paymentsettings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->fieldoption] = $get_key->value;
        }

        if($_POST) {
            $status      = $_POST["status"];
            $firstname   = $_POST["firstname"];
            $amount      = $_POST["amount"];
            $txnid       = $_POST["txnid"];
            $posted_hash = $_POST["hash"];
            $key         = $_POST["key"];
            $productinfo = $_POST["productinfo"];
            $email       = $_POST["email"];
            $salt        = $api_config['payumoney_salt'];

            if(isset($_POST["additionalCharges"])) {
                $additionalCharges = $_POST["additionalCharges"];
                $retHashSeq        = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
            } else {
                $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
            }

            $hash = strtolower(hash("sha512", $retHashSeq));
            if ($hash != $posted_hash) {
                $this->session->set_flashdata('error', "Invalid Transaction. Please try again");
                redirect(base_url("take_exam/index"));
            } else {
                if ($status==="success") {
                    $params = $this->session->userdata('params');
                    
                    $dbTransactionID = $this->online_exam_payment_m->get_single_online_exam_payment(array('transactionID' => $txnid));
                    if(!count($dbTransactionID)) {
                        $onlineExamPayment = array(
                            'online_examID'     => $params['onlineExamID'],
                            'usertypeID'        => $this->session->userdata('usertypeID'),
                            'userID'            => $this->session->userdata('loginuserID'),
                            'paymentamount'     => $params['amount'],
                            'paymentmethod'     => $params['allpost']['paymentMethod'],
                            'paymentdate'       => date('Y-m-d'),
                            'paymentday'        => date('d'),
                            'paymentmonth'      => date('m'),
                            'paymentyear'       => date('Y'),
                            'transactionID'     => $txnid,
                            'status'            => 0,
                        );

                        $this->online_exam_payment_m->insert_online_exam_payment($onlineExamPayment);
                        $this->session->set_flashdata('success', 'Payment successful');
                        redirect(base_url('take_exam/index'));
                    } else {
                        $this->session->set_flashdata('error', 'Transaction ID already exist!');
                        redirect(base_url('take_exam/index'));
                    }
                } else {
                    $this->session->set_flashdata('error', 'Transaction failed');
                    redirect(base_url('take_exam/index'));
                }
            }
        } else {
            $this->session->set_flashdata('error', "Invalid post. Please try again");
            redirect(base_url('take_exam/index'));
        }
    }

    public function voguepay() 
    {
        $api_config = [];
        $get_configs = $this->paymentsettings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->fieldoption] = $get_key->value;
        }

        if($api_config['voguepay_merchant_id'] =="" || $api_config['voguepay_merchant_ref'] =="" || $api_config['voguepay_developer_code'] =="" || $api_config['voguepay_status'] == 0){
            $this->session->set_flashdata('error', 'VoguePay configuration is missing');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->item_data    = $this->post_data;
            $this->invoice_info = $this->invoice_data;
            $params = array(
                'fail_url'      => base_url('take_exam/voguepay_failed/' . $this->item_data['onlineExamID']),
                'notify_url'    => base_url('take_exam/voguepay_notify/' . $this->item_data['onlineExamID']),
                'success_url'   => base_url('take_exam/voguepay_success/' . $this->item_data['onlineExamID']),
                'onlineExamID'  => $this->item_data['onlineExamID'],
                'description'   => $this->invoice_info->name,
                'amount'        => floatval($this->item_data['paymentAmount']),
                'currency'      => $this->data["siteinfos"]->currency_code,
                'allpost'       => $this->item_data,
            );

            $this->session->set_userdata("params", $params);

            $api_link = "https://voguepay.com/pay/";

            $this->array['invoice']        = $this->invoice_info;
            $this->array['v_merchant_id']  = $api_config['voguepay_merchant_id'];
            $this->array['success_url']    = base_url('take_exam/voguepay_success/' . $this->item_data['onlineExamID']);
            $this->array['notify_url']     = base_url('take_exam/voguepay_notify/' . $this->item_data['onlineExamID']);
            $this->array['fail_url']       = base_url('take_exam/voguepay_failed/' . $this->item_data['onlineExamID']);
            $this->array['action']         = $api_link;
            $this->array['total']          = number_format($this->item_data['paymentAmount'], 2, '.', '');
            $this->array['merchant_ref']   = $api_config['voguepay_merchant_ref'];
            $this->array['developer_code'] = $api_config['voguepay_developer_code'];
            $this->array['store_id']       = rand(1, 9999999999);
            $this->array['memo']           = $this->invoice_info->name;
            $this->array['cur']            = $this->data["siteinfos"]->currency_code;
            $this->load->view('online_exam/take_exam/voguepay', $this->array);
        }
    }

    public function voguepay_success() 
    {
        $txnid = $_POST['transaction_id'];
        if(isset($_POST["transaction_id"])) {
            $result = json_decode($this->verifyVoguePayPayment($_POST['transaction_id']));

            if ($result->state=="success") {
                $params = $this->session->userdata('params');
                $dbTransactionID = $this->online_exam_payment_m->get_single_online_exam_payment(array('transactionID' => $txnid));
                if(!count($dbTransactionID)) {
                    $onlineExamPayment = array(
                        'online_examID'     => $params['onlineExamID'],
                        'usertypeID'        => $this->session->userdata('usertypeID'),
                        'userID'            => $this->session->userdata('loginuserID'),
                        'paymentamount'     => $params['amount'],
                        'paymentmethod'     => $params['allpost']['paymentMethod'],
                        'paymentdate'       => date('Y-m-d'),
                        'paymentday'        => date('d'),
                        'paymentmonth'      => date('m'),
                        'paymentyear'       => date('Y'),
                        'transactionID'     => $txnid,
                        'status'            => 0,
                    );

                    $this->online_exam_payment_m->insert_online_exam_payment($onlineExamPayment);
                    $this->session->set_flashdata('success', 'Payment successful');
                    redirect(base_url('take_exam/index'));
                } else {
                    $this->session->set_flashdata('error', 'Transaction ID already exist');
                    redirect(base_url("take_exam/index"));
                }
            } else {
                redirect(base_url("take_exam/index"));
            }
        } else {
            $this->session->set_flashdata('error', "Invalid Transaction. Please try again");
            redirect(base_url("take_exam/index"));
        }
    }

    public function voguepay_notify() 
    {
        $txnid = $_POST['transaction_id'];
        if(isset($_POST["transaction_id"])) {
            $result = json_decode($this->verifyVoguePayPayment($_POST['transaction_id']));

            if ($result->state=="success") {
                $params = $this->session->userdata('params');
                $dbTransactionID = $this->online_exam_payment_m->get_single_online_exam_payment(array('transactionID' => $txnid));
                if(!count($dbTransactionID)) {
                    $onlineExamPayment = array(
                        'online_examID'     => $params['onlineExamID'],
                        'usertypeID'        => $this->session->userdata('usertypeID'),
                        'userID'            => $this->session->userdata('loginuserID'),
                        'paymentamount'     => $params['amount'],
                        'paymentmethod'     => $params['allpost']['paymentMethod'],
                        'paymentdate'       => date('Y-m-d'),
                        'paymentday'        => date('d'),
                        'paymentmonth'      => date('m'),
                        'paymentyear'       => date('Y'),
                        'transactionID'     => $txnid,
                        'status'            => 0,
                    );

                    $this->online_exam_payment_m->insert_online_exam_payment($onlineExamPayment);
                    $this->session->set_flashdata('success', 'Payment successful');
                    redirect(base_url('take_exam/index'));
                } else {
                    $this->session->set_flashdata('error', 'Transaction ID already exist!');
                    redirect(base_url("take_exam/index"));
                }
            } else {
                $this->session->set_flashdata('error', 'Transaction failed');
                redirect(base_url("take_exam/index"));
            }
        } else {
            $this->session->set_flashdata('error', "Invalid Transaction. Please try again");
            redirect(base_url("take_exam/index"));
        }
    }

    public function voguepay_failed() 
    {
        $this->session->set_flashdata('error', "Payment failed!");
        redirect(base_url("take_exam/index"));
    }

    private $debug = true;
    private $debug_msg = [];

    public function verifyVoguePayPayment($transaction_id) 
    {
        $details = json_decode($this->getVoguePayPaymentDetails($transaction_id,"json"));
        if(!$details && $this->debug==true){ $this->debug_msg[] = "Failed Getting Transaction Details - [Called In verifyPayment()]";}
        if($details->total < 1) return json_encode(array("state"=>"error","msg"=>"Invalid Transaction"));
        if($details->status != 'Approved') return json_encode(array("state"=>"error","msg"=>"Transaction {$details->status}"));
        return json_encode(array("state"=>"success","msg"=>"Transaction Approved", "details"=>$details));
    }

    public function getVoguePayPaymentDetails($transaction_id,$type="json") 
    {
        $api_config = [];
        $get_configs = $this->paymentsettings_m->get_order_by_config();
        foreach ($get_configs as $key => $get_key) {
            $api_config[$get_key->fieldoption] = $get_key->value;
        }

        if($api_config['voguepay_demo']==TRUE) {
            $url = "https://voguepay.com/?v_transaction_id={$transaction_id}&type={$type}&demo=true";
        } else {
            $url = "https://voguepay.com/?v_transaction_id={$transaction_id}&type={$type}";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windowos NT 5.1; en-NG; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13 Vyren Media-VoguePay API Ver 1.0");
        if(curl_errno($ch) && $this->debug==true){ $this->debug_msg[] = curl_error($ch)." - [Called In getPaymentDetails() CURL]"; }
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
