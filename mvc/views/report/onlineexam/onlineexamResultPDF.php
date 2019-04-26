<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="mainpdf">
        <div class="header_info" style="margin-bottom: 25px;">
            <?=reportheader($siteinfos,true)?>
        </div>
        <h3><?=$this->lang->line('onlineexamreport_report_for')?> - <?=$this->lang->line('onlineexamreport_onlineexam')?></h3>
        <div class="exam_info">
            <div>
                <h3><?=$this->lang->line("onlineexamreport_examinformation")?></h3>
            </div>
            <div>               
                <table>
                    <tbody>
                        <tr>
                            <td colspan="2"><span class='text-blue'><?=$this->lang->line('onlineexamreport_exam')?> : <?=$onlineexam->name?> </span></td>
                        </tr>
                        <tr>
                            <td>
                                <span class='text-blue'>

                                    <?php
                                        echo $this->lang->line('onlineexamreport_status'). ' : ';  
                                        if($onlineExamUserStatus->statusID == 5) {
                                            echo $this->lang->line('onlineexamreport_passed');
                                        } else {
                                            echo $this->lang->line('onlineexamreport_failed');
                                        }
                                    ?>
                                </span>
                            </td>
                            <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_rank')?> : <?=$rank?></span></td>
                        <tr>

                        <tr>
                            <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_question')?> : <?=$onlineExamUserStatus->totalQuestion?></span></td>
                            <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_answer')?> : <?=$onlineExamUserStatus->totalAnswer?></span></td>
                        <tr>
                        <tr>
                            <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_current_answer')?> : <?=$onlineExamUserStatus->totalCurrectAnswer?></span></td>  
                            <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_mark')?> : <?=$onlineExamUserStatus->totalMark?></span></td> 
                        </tr>
                        <tr>
                            <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_totle_obtained_mark')?> : <?=$onlineExamUserStatus->totalObtainedMark?></span></td>
                            <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_total_percentage')?> : <?=$onlineExamUserStatus->totalPercentage?>%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="student_info">
            <div>
                <h3><?=$this->lang->line("onlineexamreport_studentinformation")?></h3>
            </div>
            <div>
                <?php if(count($user)) { ?>
                    <section class="panel">
                        <div class="profile-db-head bg-maroon-light">
                            <div class="border_image">
                                <?php 
                                    if(file_exists(FCPATH."uploads/images/".$user->photo)) { ?>
                                    <img class="profile-image" src="<?=base_url("uploads/images/".$user->photo)?>" alt="">
                                <?php } else { ?>
                                    <img class="profile-image" src="<?=base_url('uploads/images/default.png')?>" alt="">
                                <?php } ?>
                            </div>
                            <h1><?=$user->name?></h1>
                        </div>
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fa fa-sitemap text-maroon-light"></i>
                                    </td>
                                    <td><?=$this->lang->line('onlineexamreport_classes')?></td>
                                    <td><?=count($classes) ? $classes->classes : ''?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-sitemap text-maroon-light"></i>
                                    </td>
                                    <td><?=$this->lang->line('onlineexamreport_section')?></td>
                                    <td><?=count($section) ? $section->section : ''?></td>
                                </tr>
                                <?php if($onlineexam->subjectID > 0) { ?>
                                    <tr>
                                        <td>
                                            <i class="fa fa-sitemap text-maroon-light"></i>
                                        </td>
                                        <td><?=$this->lang->line('onlineexamreport_subject')?></td>
                                        <td><?=count($subject) ? $subject->subject : ''?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>
                                        <i class="fa fa-phone text-maroon-light" ></i>
                                    </td>
                                    <td><?=$this->lang->line('onlineexamreport_phone')?></td>
                                    <td><?=$user->phone?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-envelope text-maroon-light"></i>
                                    </td>
                                    <td><?=$this->lang->line('onlineexamreport_email')?></td>
                                    <td><?=$user->email?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-globe text-maroon-light"></i>
                                    </td>
                                    <td><?=$this->lang->line('onlineexamreport_address')?></td>
                                    <td><?=$user->address?></td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                <?php } ?>
            </div>
        </div>
        <div class="footer_info text-center footerAll" style="margin-bottom: 25px;">
            <?=reportfooter($siteinfos)?>
        </div>
    </div><!-- row -->
</body>
</html>