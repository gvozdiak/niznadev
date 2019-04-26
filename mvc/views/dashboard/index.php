<div class="row">
    <?php if(config_item('demo')) { ?>
        <div class="col-sm-12" id="resetDummyData">
            <div class="callout callout-danger">
                <h4>Reminder!</h4>
                <p>Dummy data will be reset in every <code>30</code> minutes</p>
            </div>
        </div>

        <script type="text/javascript"> 
            $(document).ready(function() {
                var count = 7;
                var countdown = setInterval(function() {
                    $("p.countdown").html(count + " seconds remaining!");
                    if (count == 0) {
                        clearInterval(countdown);
                        $('#resetDummyData').hide();
                    }
                    count--;
                }, 1000);
            });
        </script>
    <?php } ?>

    <?php if((config_item('demo') === FALSE) && ($siteinfos->auto_update_notification == 1) && ($versionChecking != 'none')) { ?>
        <?php if($this->session->userdata('updatestatus') === null) { ?>
            <div class="col-sm-12" id="updatenotify">
                <div class="callout callout-success">
                    <h4>Dear Admin</h4>
                    <p>iTest - Complete Online Examination System has released a new update.</p>
                    <p>Do you want to update it now <?=config_item('ini_version')?> to <?=$versionChecking?> ?</p>
                    <a href="<?=base_url('dashboard/remind')?>" class="btn btn-danger">Remind me</a>
                    <a href="<?=base_url('dashboard/update')?>" class="btn btn-success">Update</a>
                </div>
            </div>
        <?php } ?> 
    <?php } ?>

    <?php
        $arrayColor = array(
            'bg-orange-dark',
            'bg-teal-light',
            'bg-pink-light',
            'bg-purple-light'
        );

        function allModuleArray($usertypeID='1', $dashboardWidget) {
            $userAllModuleArray = array(
                $usertypeID => array(
                    'student'           => $dashboardWidget['students'],
                    'classes'           => $dashboardWidget['classes'],
                    'teacher'           => $dashboardWidget['teachers'],
                    'parents'           => $dashboardWidget['parents'],
                    'subject'           => $dashboardWidget['subjects'],
                    'event'             => $dashboardWidget['events'],
                    'online_exam'       => $dashboardWidget['onlineexam'],
                    'question_group'    => $dashboardWidget['questiongroup'],
                    'question_level'    => $dashboardWidget['questionlevel'],
                    'question_bank'     => $dashboardWidget['questionbank'],
                    'notice'            => $dashboardWidget['notice'],
                    'studentgroup'      => $dashboardWidget['studentgroup']
                )
            );
            return $userAllModuleArray;
        }

        $userArray = array(
            '1' => array(
              'student'     => $dashboardWidget['students'],
              'teacher'     => $dashboardWidget['teachers'],
              'parents'     => $dashboardWidget['parents'],
              'online_exam'  => $dashboardWidget['onlineexam'],
            ), '2' => array(
              'student'   => $dashboardWidget['students'],
              'teacher'   => $dashboardWidget['teachers'],
              'classes'   => $dashboardWidget['classes'],
              'subject'   => $dashboardWidget['subjects'],
            ), '3' => array(
              'teacher'   => $dashboardWidget['teachers'],
              'subjects'   => $dashboardWidget['subjects'],
            ), '4' => array(
              'teacher'   => $dashboardWidget['teachers'],
              'event'     => $dashboardWidget['events'],
            )
        );

        $generateBoxArray = [];
        $counter = 0;
        $getActiveUserID = $this->session->userdata('usertypeID');

        $getAllSessionDatas = $this->session->userdata('master_permission_set');
        foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
            if($getAllSessionData == 'yes') {
                if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
                    if($counter == 4) {
                      break;
                    }

                    $generateBoxArray[$getAllSessionDataKey] = array(
                        'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                        'color' => $arrayColor[$counter],
                        'link' => $getAllSessionDataKey,
                        'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                        'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey],
                    );
                    $counter++;
                }
            }
        }


        $icon = '';
        $menu = '';
        if($counter < 4) {
            $userArray = allModuleArray($getActiveUserID, $dashboardWidget);
            foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
                if($getAllSessionData == 'yes') {
                    if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
                        if($counter == 4) {
                            break;
                        }

                        if(!isset($generateBoxArray[$getAllSessionDataKey])) {
                            $generateBoxArray[$getAllSessionDataKey] = array(
                                'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                                'color' => $arrayColor[$counter],
                                'link' => $getAllSessionDataKey,
                                'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                                'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey]
                            );
                            $counter++;
                        }
                    }
                }
            }
        }

        if(count($generateBoxArray)) {
            foreach ($generateBoxArray as $generateBoxArrayKey => $generateBoxValue) {
    ?>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box ">
            <a class="small-box-footer <?=$generateBoxValue['color']?>" href="<?=base_url($generateBoxValue['link'])?>">
                <div class="icon <?=$generateBoxValue['color']?>" style="padding: 9.5px 18px 8px 18px;">
                    <i class="fa <?=$generateBoxValue['icon']?>"></i>
                </div>
                <div class="inner ">
                    <h3 class="text-white">
                        <?=$generateBoxValue['count']?>
                    </h3 class="text-white">
                    <p class="text-white">
                        <?=$this->lang->line('menu_'.$generateBoxValue['menu'])?>
                    </p>
                </div>
            </a>
        </div>
    </div>
    <?php
            }
        }
    ?>
</div>

<?php if($getActiveUserID == 1) { ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <div id="visitor"></div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-sm-4">
        <?php $this->load->view('dashboard/ProfileBox'); ?>
    </div>
    <?php if(permissionChecker('notice')) { ?>
        <div class="col-sm-8">
            <div class="box">
                <div class="box-body" style="padding: 0px;height: 320px">
                    <?php $this->load->view('dashboard/NoticeBoard', array('val' => 5, 'length' => 20, 'maxlength' => 70)); ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("dashboard/CalenderJavascript"); ?>
<?php $this->load->view("dashboard/VisitorHighChartJavascript"); ?>

