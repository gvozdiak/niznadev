<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property document_m $document_m
 * @property email_m $email_m
 * @property error_m $error_m
 */
class Admin_Controller extends MY_Controller {
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
	
	private $_backendTheme = '';
	private $_backendThemePath = '';

	function __construct () {
		parent::__construct();

		$this->load->model("signin_m");
		$this->load->model("permission_m");
		$this->load->model("site_m");
		$this->load->model("schoolyear_m");
		$this->data["siteinfos"] = $this->site_m->get_site(1);

		$this->data['backendTheme'] = strtolower($this->data["siteinfos"]->backend_theme);
		$this->data['backendThemePath'] = 'assets/inilabs/themes/'.strtolower($this->data["siteinfos"]->backend_theme);
		$this->_backendTheme = $this->data['backendTheme'];
		$this->_backendThemePath = $this->data['backendThemePath'];


		$this->data['topbarschoolyears'] = $this->schoolyear_m->get_order_by_schoolyear(array('schooltype' => $this->data["siteinfos"]->school_type));
		$this->load->library("session");
		$this->load->helper('language');
		$this->load->helper('date');
		$this->load->helper('form');
		$this->load->helper('traffic');
		$this->load->library('form_validation');
		$this->load->model('classes_m');
		$this->load->model("menu_m");

		$this->data["menuclasss"] = $this->classes_m->get_order_by_classes();

		$this->load->model("notice_m");
		$this->load->model("alert_m");
		$this->data['all'] = array();
		$this->data['alert'] = array('notice' => array(), 'event' => array(),'message'=>array());

		$this->data['alertfire'] = $this->getAllAlert();		

		$this->alertNoticeChecker();
		$this->alertEventChecker();
		$this->alertMessageChecker();

		$this->data['alert'] = $this->alertConvertor($this->data['alert']);


		$this->data['sitesettings'] = array(
			'schooltype' => 'classbase'
		);

		$this->data['allcountry'] = $this->getAllCountry();
		$this->data['allbloodgroup'] = $this->getAllBloodgroup();

		$language = $this->session->userdata('lang');
		$this->lang->load('topbar_menu', $language);

		$exception_uris = array(
			"signin/index",
			"signin/signout"
		);

		if(in_array(uri_string(), $exception_uris) == FALSE) {
			if($this->signin_m->loggedin() == FALSE) {
				redirect(base_url("signin/index"));
			}
		}



		$module = $this->uri->segment(1);
		$action = $this->uri->segment(2);
		$permission = '';

		if($action == 'index' || $action == false) {
			$permission = $module;
		} else {
			$permission = $module.'_'.$action;
		}

		$permissionset = array();
		$userdata = $this->session->userdata;

		if($this->session->userdata('usertypeID') == 1 && $this->session->userdata('loginuserID') == 1) {
			if(isset($userdata['loginuserID']) && !isset($userdata['get_permission'])) {

				$allmodules = $this->permission_m->get_permission();

				if(count($allmodules)) {
					foreach ($allmodules as $key => $allmodule) {
						$permissionset['master_permission_set'][trim($allmodule->name)] = $allmodule->active;
					}

					$data = ['get_permission' => TRUE];
					$this->session->set_userdata($data);
					$this->session->set_userdata($permissionset);
				}
			}
		} else {
			if(isset($userdata['loginuserID']) && !isset($userdata['get_permission'])) {
				if(!$this->session->userdata($permission)) {
					$user_permission = $this->permission_m->get_modules_with_permission($userdata['usertypeID']);
					foreach ($user_permission as $value) {
						$permissionset['master_permission_set'][$value->name] = $value->active;
					}

					if($userdata['usertypeID'] == 3) {
						$permissionset['master_permission_set']['take_exam'] = 'yes';
					}
					
					$data = ['get_permission' => TRUE];
					$this->session->set_userdata($data);
					$this->session->set_userdata($permissionset);
				}
			}
		}


		$sessionPermission = $this->session->userdata('master_permission_set');

		$dbMenus	= $this->menuTree(json_decode(json_encode(pluck($this->menu_m->get_order_by_menu(['status' => 1]), 'obj', 'menuID')), true) , $sessionPermission);
		$this->data["dbMenus"] = $dbMenus;

		if((isset($sessionPermission[$permission]) && $sessionPermission[$permission] == "no") ) {
			if($permission == 'dashboard' && $sessionPermission[$permission] == "no") {
				$url = 'exceptionpage/index';
				if(in_array('yes', $sessionPermission)) {
			    	if($sessionPermission["dashboard"] == 'no') {
			    		foreach ($sessionPermission as $key => $value) {
			    			if($value == 'yes') {
			    				$url = $key;
			    				break;
			    			}
			    		}
			    	}
		    	} else {
		    		redirect(base_url('exceptionpage/index'));
		    	}
		    	redirect(base_url($url));
			} else {
				redirect(base_url('exceptionpage/error'));
			}
		}
	}
	
	private function getAllAlert() {
		$retArray = [];
		$alerts = $this->alert_m->get_order_by_alert(array('userID' => $this->session->userdata('loginuserID'), 'usertypeID' => $this->session->userdata('usertypeID')));
		if(count($alerts)) {
			foreach ($alerts as $alert) {
				$retArray[$alert->itemID][$alert->itemname] = $alert;
			}
		}
		return $retArray;
	} 

	public function alertConvertor($arrays) {
		$alert = $this->data['alertfire'];
		$retArray = [];
		if(count($arrays)) {
			foreach ($arrays as $array) {
				foreach ($array as  $ar) {
					$retArray[] = $ar;
				}
			}
			
			$retArray = json_decode(json_encode($retArray), TRUE);
			array_multisort(array_column($retArray, "create_date"), SORT_DESC, $retArray);
			$retArray = json_decode(json_encode($retArray), FALSE);
		}

		return $retArray;
	}

	private function alertNoticeChecker() {
		$alert = $this->data['alertfire'];
		$notices = $this->notice_m->get_order_by_notice(array('schoolyearID' => $this->session->userdata('defaultschoolyearID')));
		if(count($notices)) {
			foreach ($notices as $notice) {
				if(!isset($alert[$notice->noticeID]['notice'])) {
					$this->data['alert']['notice'][] = $notice;
				}
			}
		}

		return $this->data['alert'];
	}

	private function alertEventChecker() {
		$alert = $this->data['alertfire'];
		$this->load->model('event_m');
		$events = $this->event_m->get_order_by_event(array('schoolyearID' => $this->session->userdata('defaultschoolyearID')));
		if(count($events)) {
			foreach ($events as $event) {
				if(!isset($alert[$event->eventID]['event'])) {
					$this->data['alert']['event'][] = $event;
				}
			}
		}

		return $this->data['alert'];
	}

	private function alertMessageChecker() {
		$allMessageSend = [];
		$this->load->model('conversation_m');
		$messageUsertypeID = $this->session->userdata("usertypeID");
		$messageUserID = $this->session->userdata("loginuserID");
		$this->data['messageConversations'] = $this->conversation_m->get_my_conversations(); 
		$this->data['messageMethodpass'] = $this->uri->segment(htmlentities(escapeString(2)));
		$messageBind = array();
		if ($this->data['messageConversations']) {
			foreach ($this->data['messageConversations'] as $key => $item) {
				if ($item['usertypeID']== 1) {
					$table = "systemadmin";
				} elseif($item['usertypeID']== 2) {
					$table = "teacher";
				} elseif($item['usertypeID']== 3) {
					$table = 'student';
				} elseif($item['usertypeID']== 4) {
					$table = 'parents';
				} else {
					$table = 'user';
				}

				$messageQuery = $this->db->get_where($table, array($table.'ID' => $item['user_id']));
				if(count($messageQuery->row())) {
					$this->data['messageConversations'][$key] = (object) array_merge((array)$item, array('sender' => $messageQuery->row()->name));
				}
			}

			foreach ($this->data['messageConversations'] as $key => $value) {
				$this->data['all']['message'][] = $messageGetMessages = $this->conversation_m->get_conversation_msg_by_id($value->conversation_id);
			}

			$alertAllMessage = $this->data['alertfire'];
			

			if(count($this->data['all']['message'])) {
				array_multisort($this->data['all']['message'], SORT_DESC);

				$flagArray = array();
				$flagSubject = array();
				foreach ($this->data['all']['message'] as $allMessage) {
					if(count($allMessage)) {
						foreach ($allMessage as $messageKey => $messageValue) {
							if(!array_key_exists($messageValue->conversation_id, $flagSubject)) {
								$flagSubject[$messageValue->conversation_id] = $messageValue->subject;
							}

							if(!isset($alertAllMessage[$messageValue->msg_id]['message'])) {
								if(!in_array($messageValue->conversation_id, $flagArray)) {
									$flagArray[] = $messageValue->conversation_id;
								}

								if(in_array($messageValue->conversation_id, $flagArray)) {
									$messageBind[$messageValue->conversation_id] = $messageValue;
								}
							}
						}
					}
				}

				if(count($messageBind)) {
					$messageBind = json_decode(json_encode($messageBind), true);
					foreach ($messageBind as $key => $mbind) {
						if(empty($mbind['subject'])) {
							if(isset($flagSubject[$mbind['conversation_id']])) {
								$messageBind[$mbind['conversation_id']]['subject'] = $flagSubject[$mbind['conversation_id']];
								$messageBind[$mbind['conversation_id']] = (object) $messageBind[$mbind['conversation_id']];
							}
						} else {
							$messageBind[$mbind['conversation_id']] = (object) $messageBind[$mbind['conversation_id']];
						}
					}
				}
			}
		}

		$this->data['alert']['message'] = $messageBind;
		return $this->data['alert'];
	}

	public function usercreatemail($email=NULL, $username=NULL, $password=NULL) {
	    $this->load->model('emailsetting_m');
		$this->load->library('email');
		$this->email->set_mailtype("html");


		if(!empty($email)) {
			$emailsetting = $this->emailsetting_m->get_emailsetting();
			if($emailsetting->email_engine == 'smtp') {
				$config = array(
				    'protocol'  	=> 'smtp',
				    'smtp_host' 	=> $emailsetting->smtp_server,
				    'smtp_port' 	=> $emailsetting->smtp_port,
				    'smtp_user' 	=> $emailsetting->smtp_username,
				    'smtp_pass' 	=> $emailsetting->smtp_password,
				    'smtp_crypto' 	=> $emailsetting->smtp_security,
				    'mailtype'  	=> 'html',
				    'charset'   	=> 'utf-8'
				);
				$this->email->initialize($config);
				$this->email->set_newline("\r\n");
			}

			$fromEmail = $this->data["siteinfos"]->email;
			$this->email->from($fromEmail, $this->data['siteinfos']->sname);
			$this->email->to($email);
			$this->email->subject($this->data['siteinfos']->sname);
			$url = base_url();
	        $message = "<h2>Welcome to ".$this->data['siteinfos']->sname."</h2>
	        <p>Please log-in to this website and change the password as soon as possible </p>
	        <p>Website : ".$url."</p>
	        <p>Username: ".$username."</p>
	        <p>Password: ".$password."</p>
	        <br>
	        <p>Once again, thank you for choosing ".$this->data['siteinfos']->sname."</p>
	        <p>Best Wishes,</p>
	        <p>The ".$this->data['siteinfos']->sname." Team</p>";
	        $this->email->message($message);
	        $this->email->send();	
		}
	}

	public function reportPDF($stylesheet=NULL, $data=NULL, $viewpath= NULL, $mode = 'view', $pagesize = 'a4', $pagetype='portrait') {
		$designType = 'LTR';
		$this->data['panel_title'] = $this->lang->line('panel_title');
		$html = $this->load->view($viewpath, $this->data, true);

		$this->load->library('mhtml2pdf');

		$this->mhtml2pdf->folder('uploads/report/');
		$this->mhtml2pdf->filename('Report');
		$this->mhtml2pdf->paper($pagesize, $pagetype);
		$this->mhtml2pdf->html($html);

		if(!empty($stylesheet)) {
			$stylesheet = file_get_contents(base_url('assets/pdf/'.$designType.'/'.$stylesheet));
			return $this->mhtml2pdf->create($mode, $this->data['panel_title'], $stylesheet);
		} else { 
			return $this->mhtml2pdf->create($mode, $this->data['panel_title'], '');
		}
	}

	public function reportSendToMail($stylesheet=NULL, $data=NULL, $viewpath=NULL, $email=NULL, $subject=NULL, $message=NULL, $pagesize = 'a4', $pagetype='portrait') {
		$this->load->model('emailsetting_m');

		$designType = 'LTR';
		$this->load->library('email');
		$this->load->library('mhtml2pdf');
	    $this->mhtml2pdf->folder('uploads/report/');
	    $rand = random19() . date('y-m-d h:i:s');
	    $sharand = hash('sha512', $rand);

	    $this->mhtml2pdf->filename($sharand);
	    $this->mhtml2pdf->paper($pagesize, $pagetype);
	    $this->data['panel_title'] = $this->lang->line('panel_title');
		$html = $this->load->view($viewpath, $this->data, true);
		$this->mhtml2pdf->html($html);


		if(!empty($stylesheet)) {
			$stylesheet = file_get_contents(base_url('assets/pdf/'.$designType.'/'.$stylesheet));
		}

		$emailsetting = $this->emailsetting_m->get_emailsetting();
		$this->email->set_mailtype("html");

		if(count($emailsetting)) {
			if($path = @$this->mhtml2pdf->create('save',$this->data['panel_title'], $stylesheet)) {
				if($emailsetting->email_engine == 'smtp') {
					$config = array(
					    'protocol'  	=> 'smtp',
					    'smtp_host' 	=> $emailsetting->smtp_server,
					    'smtp_port' 	=> $emailsetting->smtp_port,
					    'smtp_user' 	=> $emailsetting->smtp_username,
					    'smtp_pass' 	=> $emailsetting->smtp_password,
					    'smtp_crypto' 	=> $emailsetting->smtp_security,
					    'mailtype'  	=> 'html',
					    'charset'   	=> 'utf-8'
					);

					try {
						$this->email->initialize($config);
						$this->email->set_newline("\r\n");
					} catch (Exception $e) {
						$this->session->set_flashdata('error', $this->lang->line('mail_error'));
					}
				}

				$fromEmail = $this->data["siteinfos"]->email;
				if($this->session->userdata('email') != '') {
					$fromEmail = $this->session->userdata('email');
				}
				
				try {
					$this->email->from($fromEmail, $this->data['siteinfos']->sname);
					$this->email->to($email);
					$this->email->subject($subject);
					$this->email->message($message);
					$this->email->attach($path);

					if($this->email->send()) {
						$this->session->set_flashdata('success', $this->lang->line('mail_success'));
					} else {
						$this->session->set_flashdata('error', $this->lang->line('mail_error'));
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('error', $this->lang->line('mail_error'));
				}

			}
		} else {
			$this->session->set_flashdata('error', $this->lang->line('mail_error'));
		}
	}
	
	public function getAllCountry() {
		$country = array(
			"AF" => "Afghanistan",
			"AL" => "Albania",
			"DZ" => "Algeria",
			"AS" => "American Samoa",
			"AD" => "Andorra",
			"AO" => "Angola",
			"AI" => "Anguilla",
			"AQ" => "Antarctica",
			"AG" => "Antigua and Barbuda",
			"AR" => "Argentina",
			"AM" => "Armenia",
			"AW" => "Aruba",
			"AU" => "Australia",
			"AT" => "Austria",
			"AZ" => "Azerbaijan",
			"BS" => "Bahamas",
			"BH" => "Bahrain",
			"BD" => "Bangladesh",
			"BB" => "Barbados",
			"BY" => "Belarus",
			"BE" => "Belgium",
			"BZ" => "Belize",
			"BJ" => "Benin",
			"BM" => "Bermuda",
			"BT" => "Bhutan",
			"BO" => "Bolivia",
			"BA" => "Bosnia and Herzegovina",
			"BW" => "Botswana",
			"BV" => "Bouvet Island",
			"BR" => "Brazil",
			"BQ" => "British Antarctic Territory",
			"IO" => "British Indian Ocean Territory",
			"VG" => "British Virgin Islands",
			"BN" => "Brunei",
			"BG" => "Bulgaria",
			"BF" => "Burkina Faso",
			"BI" => "Burundi",
			"KH" => "Cambodia",
			"CM" => "Cameroon",
			"CA" => "Canada",
			"CT" => "Canton and Enderbury Islands",
			"CV" => "Cape Verde",
			"KY" => "Cayman Islands",
			"CF" => "Central African Republic",
			"TD" => "Chad",
			"CL" => "Chile",
			"CN" => "China",
			"CX" => "Christmas Island",
			"CC" => "Cocos [Keeling] Islands",
			"CO" => "Colombia",
			"KM" => "Comoros",
			"CG" => "Congo - Brazzaville",
			"CD" => "Congo - Kinshasa",
			"CK" => "Cook Islands",
			"CR" => "Costa Rica",
			"HR" => "Croatia",
			"CU" => "Cuba",
			"CY" => "Cyprus",
			"CZ" => "Czech Republic",
			"CI" => "Côte d’Ivoire",
			"DK" => "Denmark",
			"DJ" => "Djibouti",
			"DM" => "Dominica",
			"DO" => "Dominican Republic",
			"NQ" => "Dronning Maud Land",
			"DD" => "East Germany",
			"EC" => "Ecuador",
			"EG" => "Egypt",
			"SV" => "El Salvador",
			"GQ" => "Equatorial Guinea",
			"ER" => "Eritrea",
			"EE" => "Estonia",
			"ET" => "Ethiopia",
			"FK" => "Falkland Islands",
			"FO" => "Faroe Islands",
			"FJ" => "Fiji",
			"FI" => "Finland",
			"FR" => "France",
			"GF" => "French Guiana",
			"PF" => "French Polynesia",
			"TF" => "French Southern Territories",
			"FQ" => "French Southern and Antarctic Territories",
			"GA" => "Gabon",
			"GM" => "Gambia",
			"GE" => "Georgia",
			"DE" => "Germany",
			"GH" => "Ghana",
			"GI" => "Gibraltar",
			"GR" => "Greece",
			"GL" => "Greenland",
			"GD" => "Grenada",
			"GP" => "Guadeloupe",
			"GU" => "Guam",
			"GT" => "Guatemala",
			"GG" => "Guernsey",
			"GN" => "Guinea",
			"GW" => "Guinea-Bissau",
			"GY" => "Guyana",
			"HT" => "Haiti",
			"HM" => "Heard Island and McDonald Islands",
			"HN" => "Honduras",
			"HK" => "Hong Kong SAR China",
			"HU" => "Hungary",
			"IS" => "Iceland",
			"IN" => "India",
			"ID" => "Indonesia",
			"IR" => "Iran",
			"IQ" => "Iraq",
			"IE" => "Ireland",
			"IM" => "Isle of Man",
			"IL" => "Israel",
			"IT" => "Italy",
			"JM" => "Jamaica",
			"JP" => "Japan",
			"JE" => "Jersey",
			"JT" => "Johnston Island",
			"JO" => "Jordan",
			"KZ" => "Kazakhstan",
			"KE" => "Kenya",
			"KI" => "Kiribati",
			"KW" => "Kuwait",
			"KG" => "Kyrgyzstan",
			"LA" => "Laos",
			"LV" => "Latvia",
			"LB" => "Lebanon",
			"LS" => "Lesotho",
			"LR" => "Liberia",
			"LY" => "Libya",
			"LI" => "Liechtenstein",
			"LT" => "Lithuania",
			"LU" => "Luxembourg",
			"MO" => "Macau SAR China",
			"MK" => "Macedonia",
			"MG" => "Madagascar",
			"MW" => "Malawi",
			"MY" => "Malaysia",
			"MV" => "Maldives",
			"ML" => "Mali",
			"MT" => "Malta",
			"MH" => "Marshall Islands",
			"MQ" => "Martinique",
			"MR" => "Mauritania",
			"MU" => "Mauritius",
			"YT" => "Mayotte",
			"FX" => "Metropolitan France",
			"MX" => "Mexico",
			"FM" => "Micronesia",
			"MI" => "Midway Islands",
			"MD" => "Moldova",
			"MC" => "Monaco",
			"MN" => "Mongolia",
			"ME" => "Montenegro",
			"MS" => "Montserrat",
			"MA" => "Morocco",
			"MZ" => "Mozambique",
			"MM" => "Myanmar [Burma]",
			"NA" => "Namibia",
			"NR" => "Nauru",
			"NP" => "Nepal",
			"NL" => "Netherlands",
			"AN" => "Netherlands Antilles",
			"NT" => "Neutral Zone",
			"NC" => "New Caledonia",
			"NZ" => "New Zealand",
			"NI" => "Nicaragua",
			"NE" => "Niger",
			"NG" => "Nigeria",
			"NU" => "Niue",
			"NF" => "Norfolk Island",
			"KP" => "North Korea",
			"VD" => "North Vietnam",
			"MP" => "Northern Mariana Islands",
			"NO" => "Norway",
			"OM" => "Oman",
			"PC" => "Pacific Islands Trust Territory",
			"PK" => "Pakistan",
			"PW" => "Palau",
			"PS" => "Palestinian Territories",
			"PA" => "Panama",
			"PZ" => "Panama Canal Zone",
			"PG" => "Papua New Guinea",
			"PY" => "Paraguay",
			"YD" => "People's Democratic Republic of Yemen",
			"PE" => "Peru",
			"PH" => "Philippines",
			"PN" => "Pitcairn Islands",
			"PL" => "Poland",
			"PT" => "Portugal",
			"PR" => "Puerto Rico",
			"QA" => "Qatar",
			"RO" => "Romania",
			"RU" => "Russia",
			"RW" => "Rwanda",
			"RE" => "Réunion",
			"BL" => "Saint Barthélemy",
			"SH" => "Saint Helena",
			"KN" => "Saint Kitts and Nevis",
			"LC" => "Saint Lucia",
			"MF" => "Saint Martin",
			"PM" => "Saint Pierre and Miquelon",
			"VC" => "Saint Vincent and the Grenadines",
			"WS" => "Samoa",
			"SM" => "San Marino",
			"SA" => "Saudi Arabia",
			"SN" => "Senegal",
			"RS" => "Serbia",
			"CS" => "Serbia and Montenegro",
			"SC" => "Seychelles",
			"SL" => "Sierra Leone",
			"SG" => "Singapore",
			"SK" => "Slovakia",
			"SI" => "Slovenia",
			"SB" => "Solomon Islands",
			"SO" => "Somalia",
			"ZA" => "South Africa",
			"GS" => "South Georgia and the South Sandwich Islands",
			"KR" => "South Korea",
			"ES" => "Spain",
			"LK" => "Sri Lanka",
			"SD" => "Sudan",
			"SR" => "Suriname",
			"SJ" => "Svalbard and Jan Mayen",
			"SZ" => "Swaziland",
			"SE" => "Sweden",
			"CH" => "Switzerland",
			"SY" => "Syria",
			"ST" => "São Tomé and Príncipe",
			"TW" => "Taiwan",
			"TJ" => "Tajikistan",
			"TZ" => "Tanzania",
			"TH" => "Thailand",
			"TL" => "Timor-Leste",
			"TG" => "Togo",
			"TK" => "Tokelau",
			"TO" => "Tonga",
			"TT" => "Trinidad and Tobago",
			"TN" => "Tunisia",
			"TR" => "Turkey",
			"TM" => "Turkmenistan",
			"TC" => "Turks and Caicos Islands",
			"TV" => "Tuvalu",
			"UM" => "U.S. Minor Outlying Islands",
			"PU" => "U.S. Miscellaneous Pacific Islands",
			"VI" => "U.S. Virgin Islands",
			"UG" => "Uganda",
			"UA" => "Ukraine",
			"SU" => "Union of Soviet Socialist Republics",
			"AE" => "United Arab Emirates",
			"GB" => "United Kingdom",
			"US" => "United States",
			"ZZ" => "Unknown or Invalid Region",
			"UY" => "Uruguay",
			"UZ" => "Uzbekistan",
			"VU" => "Vanuatu",
			"VA" => "Vatican City",
			"VE" => "Venezuela",
			"VN" => "Vietnam",
			"WK" => "Wake Island",
			"WF" => "Wallis and Futuna",
			"EH" => "Western Sahara",
			"YE" => "Yemen",
			"ZM" => "Zambia",
			"ZW" => "Zimbabwe",
			"AX" => "Åland Islands",
			);
		return $country;
	}

	public function getAllBloodgroup() {
		$bloodgroup = array(
			'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'O+' => 'O+',
            'O-' => 'O-',
            'AB+' => 'AB+',
            'AB-' => 'AB-'
        );
        return $bloodgroup;
	}

	public function menuTree($dataset, $sessionPermission) {
    	$tree = array();
    	foreach ($dataset as $id=>&$node) {
			if($node['link'] == '#' || (isset($sessionPermission[$node['link']]) && $sessionPermission[$node['link']] != "no") ) {
	    		if ($node['parentID'] == 0) {
	    			$tree[$id]=&$node;
	    		} else {
					if (!isset($dataset[$node['parentID']]['child']))
						$dataset[$node['parentID']]['child'] = array();

					$dataset[$node['parentID']]['child'][$id] = &$node;
	    		}
			}
    	}
    	return $tree;
    }
}

