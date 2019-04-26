<?php if(count($profile)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?=btn_add_pdf('profile/print_preview', $this->lang->line('pdf_preview'))?>
                <?=btn_sm_edit('profile/edit', $this->lang->line('edit')); ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="active"><?=$this->lang->line('profile')?></li>
                </ol>
            </div>
        </div>
    </div>

    <div id="printablediv">
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?=imagelink($profile->photo)?>">
                        <h3 class="profile-username text-center"><?=$profile->name?></h3>
                        <?php if($profile->usertypeID == 2) { ?>
                            <p class="text-muted text-center"><?=$profile->designation?></p>
                        <?php } else { ?>
                            <p class="text-muted text-center"><?=isset($usertypes[$profile->usertypeID]) ? $usertypes[$profile->usertypeID] : ''?></p>
                        <?php } ?>
                        <ul class="list-group list-group-unbordered">
                            <?php if($profile->usertypeID == 4) { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_phone')?></b> <a class="pull-right"><?=$profile->phone?></a>
                                </li>
                            <?php } elseif($profile->usertypeID == 3) { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_registerNO')?></b> <a class="pull-right"><?=$profile->registerNO?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_roll')?></b> <a class="pull-right"><?=$profile->roll?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_classes')?></b> <a class="pull-right"><?=isset($classes[$profile->classesID]) ? $classes[$profile->classesID] : ''?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_section')?></b> <a class="pull-right"><?=isset($sections[$profile->sectionID]) ? $sections[$profile->sectionID] : ''?></a>
                                </li>
                            <?php } else { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_sex')?></b> <a class="pull-right"><?=$profile->sex?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_dob')?></b> <a class="pull-right"><?=isset($profile->dob) ? date('d M Y',strtotime($profile->dob)) : ''?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_phone')?></b> <a class="pull-right"><?=$profile->phone?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('profile_profile')?></a></li>
                        <?php 
                            if($profile->usertypeID == 3) { 
                                if(count($parents)) { ?> 
                                    <li><a href="#parents" data-toggle="tab"><?=$this->lang->line('profile_parents')?></a></li> 
                               <?php } 
                            } elseif($profile->usertypeID == 4) { ?>
                                <li><a href="#children" data-toggle="tab"><?=$this->lang->line('profile_children')?></a></li>
                        <?php } ?>
                        <?php if($this->session->userdata('usertypeID') == 3) { ?>
                            <li><a href="#exam" data-toggle="tab"><?=$this->lang->line('profile_exam')?></a></li>
                        <?php } ?>
                        <li><a href="#document" data-toggle="tab"><?=$this->lang->line('profile_document')?></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="active tab-pane" id="profile">
                            <div class="panel-body profile-view-dis">
                                <?php if($profile->usertypeID == 3) { ?>
                                    <div class="row">
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_studentgroup")?> </span>: <?=count($studentgroup) ? $studentgroup->group : '' ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_optionalsubject")?> </span>: <?=count($optionalsubject) ? $optionalsubject->subject : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_dob")?> </span>: 
                                            <?php if($profile->dob) { echo date("d M Y", strtotime($profile->dob)); } ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_sex")?> </span>: 
                                            <?=$profile->sex?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_bloodgroup")?> </span>: <?php if(isset($allbloodgroup[$profile->bloodgroup])) { echo $profile->bloodgroup; } ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_religion")?> </span>: <?=$profile->religion?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_email")?> </span>: <?=$profile->email?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_phone")?> </span>: <?=$profile->phone?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_state")?> </span>: <?=$profile->state?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_country")?> </span>: 
                                            <?php if(isset($allcountry[$profile->country])) { echo $allcountry[$profile->country]; } ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_remarks")?> </span>: <?=$profile->remarks?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_username")?> </span>: <?=$profile->username?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_extracurricularactivities")?> </span>: <?=$profile->extracurricularactivities?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_address")?> </span>: <?=$profile->address?></p>
                                        </div>
                                    </div>
                                <?php } elseif($profile->usertypeID == 4) { ?>
                                    <div class="row">
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_father_name")?> </span>: <?=$profile->father_name?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_father_profession")?> </span>: <?=$profile->father_profession?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_mother_name")?> </span>: <?=$profile->mother_name?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_mother_profession")?> </span>: <?=$profile->mother_profession?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_email")?> </span>: <?=$profile->email?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_address")?> </span>: <?=$profile->address?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_username")?> </span>: <?=$profile->username?></p>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="row">
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_jod")?> </span>: <?=date("d M Y", strtotime($profile->jod))?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_religion")?> </span>: <?=$profile->religion?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_email")?> </span>: <?=$profile->email?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_address")?> </span>: <?=$profile->address?></p>
                                        </div>

                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_username")?> </span>: <?=$profile->username?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if($profile->usertypeID == 3) { ?>
                            <?php if(count($parents)) { ?>
                                <div class="tab-pane" id="parents">
                                    <div class="panel-body profile-view-dis">
                                        <div class="row">
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_guargian_name")?> </span>: <?=$parents->name?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_father_name")?> </span>: <?=$parents->father_name?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_mother_name")?> </span>: <?=$parents->mother_name?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_father_profession")?> </span>: <?=$parents->father_profession?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_mother_profession")?> </span>: <?=$parents->mother_profession?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_email")?> </span>: <?=$parents->email?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_phone")?> </span>: <?=$parents->phone?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_username")?> </span>: <?=$parents->username?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_address")?> </span>: <?=$parents->address?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>

                        <?php if($profile->usertypeID == 4) { ?>
                            <div class="tab-pane" id="children">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_photo')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_name')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_roll')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_classes')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_section')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($childrens)) {$i = 1; foreach($childrens as $children) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?=$i?>  
                                                    </td>


                                                    <td data-title="<?=$this->lang->line('profile_photo')?>">
                                                        <?php $array = array(
                                                                "src" => base_url('uploads/images/'.$children->photo),
                                                                'width' => '35px',
                                                                'height' => '35px',
                                                                'class' => 'img-rounded'
                                                            );
                                                            echo img($array);
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_name')?>">
                                                        <?=$children->name?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_roll')?>">
                                                        <?=$children->roll?>
                                                    </td> 
                                                    <td data-title="<?=$this->lang->line('profile_classes')?>">
                                                        <?=isset($classes[$children->classesID]) ? $classes[$children->classesID] : ''?>
                                                    </td> 
                                                    <td data-title="<?=$this->lang->line('profile_section')?>">
                                                        <?=isset($sections[$children->sectionID]) ? $sections[$children->sectionID] : ''?>
                                                    </td>

                                                </tr>

                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php if($this->session->userdata('usertypeID') == 3) { ?>
                            <div class="tab-pane" id="exam">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('slno')?></th>
                                                <th><?=$this->lang->line('profile_exam')?></th>
                                                <th><?=$this->lang->line('profile_date')?></th>
                                                <th><?=$this->lang->line('profile_status')?></th>
                                                <th><?=$this->lang->line('action')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($examresults)) { $i = 1; foreach ($examresults as $examresult) {  ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?=$i; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_exam')?>">
                                                        <?=isset($onlineexams[$examresult->onlineExamID]) ? namesorting($onlineexams[$examresult->onlineExamID]->name,40) : ''?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_date')?>">
                                                        <?=date('d M Y h:i:s A', strtotime($examresult->time))?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_status')?>">
                                                        <?php 
                                                            if($examresult->statusID == 5) {
                                                                echo '<span class="text-green">'. $this->lang->line('profile_pass') . '</span>';
                                                            } elseif($examresult->statusID == 10) {
                                                                echo '<span class="text-red">'. $this->lang->line('profile_fail') . '</span>';
                                                            } 
                                                        ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('action')?>">
                                                        <button class="btn btn-info btn-xs mrg getMarkinfo" data-examstatusid="<?=$examresult->onlineExamUserStatus?>" data-toggle="modal" data-target="#examdetails"><span data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('view')?>"><i class="fa fa-check-square-o"></i></aspan></button>

                                                    </td>
                                                </tr>
                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="tab-pane" id="document">
                            <?php if(($this->session->userdata('usertypeID') == 1) && ($this->session->userdata('loginuserID') ==1)) { ?>
                                <button class="btn btn-success btn-sm" style="margin-bottom: 10px" type="button" data-toggle="modal" data-target="#documentupload"><?=$this->lang->line('profile_add_document')?></button>
                            <?php } ?>
                            <div id="hide-table">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?=$this->lang->line('slno')?></th>
                                            <th><?=$this->lang->line('profile_title')?></th>
                                            <th><?=$this->lang->line('profile_date')?></th>
                                            <th><?=$this->lang->line('action')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($documents)) { $i = 1; foreach ($documents as $document) {  ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('slno')?>">
                                                    <?php echo $i; ?>
                                                </td>

                                                <td data-title="<?=$this->lang->line('profile_title')?>">
                                                    <?=$document->title?>
                                                </td>

                                                <td data-title="<?=$this->lang->line('profile_date')?>">
                                                    <?=date('d M Y', strtotime($document->create_date))?>
                                                </td>

                                                <td data-title="<?=$this->lang->line('action')?>">
                                                    <?php    
                                                        echo btn_download('profile/download_document/'.$document->documentID, $this->lang->line('download'));

                                                        if(($this->session->userdata('usertypeID') == 1) && ($this->session->userdata('loginuserID')==1)) {
                                                            echo btn_delete_show('profile/delete_document/'.$document->documentID, $this->lang->line('delete'));
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php $i++; } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- email modal starts here -->
    <form class="form-horizontal" role="form" action="<?=base_url('profile/send_mail');?>" method="post">
        <div class="modal fade" id="mail">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
                </div>
                <div class="modal-body">

                    <?php
                        if(form_error('to'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="to" class="col-sm-2 control-label">
                            <?=$this->lang->line("to")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                        </div>
                        <span class="col-sm-4 control-label" id="to_error">
                        </span>
                    </div>

                    <?php
                        if(form_error('subject'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                        </div>
                        <span class="col-sm-4 control-label" id="subject_error">
                        </span>

                    </div>

                    <?php
                        if(form_error('message'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="message" class="col-sm-2 control-label">
                            <?=$this->lang->line("message")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                    <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>" />
                </div>
            </div>
          </div>
        </div>
    </form>
    <!-- email end here -->

    <?php if(($this->session->userdata('usertypeID') == 1) && ($this->session->userdata('loginuserID')==1)) { ?>
        <form id="documentUploadDataForm" class="form-horizontal" enctype="multipart/form-data" role="form" action="<?=base_url('profile/documentUpload');?>" method="post">
            <div class="modal fade" id="documentupload">
              <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title"><?=$this->lang->line('profile_document_upload')?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" >
                            <label for="title" class="col-sm-2 control-label">
                                <?=$this->lang->line("profile_title")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title')?>" >
                            </div>
                            <span class="col-sm-4 control-label" id="title_error">
                            </span>
                        </div>

                        <div class="form-group">
                           <label for="file" class="col-sm-2 control-label">
                                <?=$this->lang->line("profile_file")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <div class="input-group image-preview">
                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                            <span class="fa fa-remove"></span>
                                            <?=$this->lang->line('profile_clear')?>
                                        </button>
                                        <div class="btn btn-success image-preview-input">
                                            <span class="fa fa-repeat"></span>
                                            <span class="image-preview-input-title">
                                            <?=$this->lang->line('profile_file_browse')?></span>
                                            <input type="file" id="file" name="file"/>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <span class="col-sm-4 control-label" id="file_error">
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                        <input type="button" id="uploadfile" class="btn btn-success" value="<?=$this->lang->line("profile_upload")?>" />
                    </div>
                </div>
              </div>
            </div>
        </form>


        <script type="text/javascript">
            $(document).on('click', '#uploadfile', function() {
                var title = $('#title').val();
                var file = $('#file').val();
                var error = 0;

                if(title == '' || title == null) {
                    error++;
                    $('#title_error').html("<?=$this->lang->line('profile_title_required')?>");
                    $('#title_error').parent().addClass('has-error');
                } else {
                    $('#title_error').html('');
                    $('#title_error').parent().removeClass('has-error');
                }

                if(file == '' || file == null) {
                    error++;
                    $('#file_error').html("<?=$this->lang->line('profile_file_required')?>");
                    $('#file_error').parent().addClass('has-error');
                } else {
                    $('#file_error').html('');
                    $('#file_error').parent().removeClass('has-error');
                }

                if(error == 0) {
                    var systemadminID = "<?=$profile->systemadminID?>";
                    var formData = new FormData($('#documentUploadDataForm')[0]);
                    formData.append("systemadminID", systemadminID);
                    $.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: "<?=base_url('profile/documentUpload')?>",
                        data: formData,
                        async: false,
                        dataType: "html",
                        success: function(data) {
                            var response = jQuery.parseJSON(data);
                            if(response.status) {
                                $('#title_error').html();
                                $('#title_error').parent().removeClass('has-error');

                                $('#file_error').html();
                                $('#file_error').parent().removeClass('has-error');
                                location.reload();
                            } else {
                                if(response.errors['title']) {
                                    $('#title_error').html(response.errors['title']);
                                    $('#title_error').parent().addClass('has-error');
                                } else {
                                    $('#title_error').html();
                                    $('#title_error').parent().removeClass('has-error');
                                }
                                
                                if(response.errors['file']) {
                                    $('#file_error').html(response.errors['file']);
                                    $('#file_error').parent().addClass('has-error');
                                } else {
                                    $('#file_error').html();
                                    $('#file_error').parent().removeClass('has-error');
                                }
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }
            });     

            $(function() {
                var closebtn = $('<button/>', {
                    type:"button",
                    text: 'x',
                    id: 'close-preview',
                    style: 'font-size: initial;',
                });
                closebtn.attr("class","close pull-right");

                $('.image-preview').popover({
                    trigger:'manual',
                    html:true,
                    title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
                    content: "There's no image",
                    placement:'bottom'
                });

                $('.image-preview-clear').click(function(){
                    $('.image-preview').attr("data-content","").popover('hide');
                    $('.image-preview-filename').val("");
                    $('.image-preview-clear').hide();
                    $('.image-preview-input input:file').val("");
                    $(".image-preview-input-title").text("<?=$this->lang->line('profile_file_browse')?>");
                });

                $(".image-preview-input input:file").change(function () {
                    var img = $('<img/>', {
                        id: 'dynamic',
                        width:250,
                        height:200,
                        overflow:'hidden'
                    });

                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(".image-preview-input-title").text("<?=$this->lang->line('profile_file_browse')?>");
                        $(".image-preview-clear").show();
                        $(".image-preview-filename").val(file.name);
                    }
                    reader.readAsDataURL(file);
                });
            });
        </script>
    <?php } ?>

    <?php if($this->session->userdata('usertypeID') == 3) { ?>
        <div class="modal fade" id="examdetails">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title"><?=$this->lang->line('profile_onlineexamdetails')?></h4>
                    </div>
                    <div class="modal-body examdetails">
                        
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <script language="javascript" type="text/javascript">
        function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;
            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";
            //Print Page
            window.print();
            //Restore orignal HTML
            document.body.innerHTML = oldPage;
        }

        function check_email(email) {
            var status = false;
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if (email.search(emailRegEx) == -1) {
                $("#to_error").html('');
                $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
            } else {
                status = true;
            }
            return status;
        }

        $("#send_pdf").click(function(){
            var to = $('#to').val();
            var subject = $('#subject').val();
            var message = $('#message').val();
            var error = 0;

            $("#to_error").html("");
            if(to == "" || to == null) {
                error++;
                $("#to_error").html("");
                $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
            } else {
                if(check_email(to) == false) {
                    error++
                }
            }

            if(subject == "" || subject == null) {
                error++;
                $("#subject_error").html("");
                $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
            } else {
                $("#subject_error").html("");
            }

            if(error == 0) {
                $('#send_pdf').attr('disabled','disabled');
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('profile/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&message=" + message,
                    dataType: "html",
                    success: function(data) {
                        var response = JSON.parse(data);
                        if (response.status == false) {
                            $('#send_pdf').removeAttr('disabled');
                            $.each(response, function(index, value) {
                                if(index != 'status') {
                                    toastr["error"](value)
                                    toastr.options = {
                                      "closeButton": true,
                                      "debug": false,
                                      "newestOnTop": false,
                                      "progressBar": false,
                                      "positionClass": "toast-top-right",
                                      "preventDuplicates": false,
                                      "onclick": null,
                                      "showDuration": "500",
                                      "hideDuration": "500",
                                      "timeOut": "5000",
                                      "extendedTimeOut": "1000",
                                      "showEasing": "swing",
                                      "hideEasing": "linear",
                                      "showMethod": "fadeIn",
                                      "hideMethod": "fadeOut"
                                    }
                                }
                            });
                        } else {
                            location.reload();
                        }
                    }
                });
            }
        });

        <?php if($this->session->userdata('usertypeID') == 3) { ?>
            $('.getMarkinfo').click(function() {
                var examstatusid = $(this).data('examstatusid');
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('profile/get_user_exam_status')?>",
                    data: {'examstatusid':examstatusid},
                    dataType: "html",
                    success: function(data) {
                        var response = JSON.parse(data);
                        if(response.status) {
                            $('.examdetails').html(response.render);
                        } else {
                            $('.examdetails').html("<h2 class='text-red'>"+response.msg+"</h2");
                        }
                    }
                });
            });
        <?php } ?>

    </script>
<?php } ?>