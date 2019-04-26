<div id="printablediv">
    <div class="box">
        <div class="box-header bg-gray">
            <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('onlineexamreport_report_for')?> <?=$this->lang->line('onlineexamreport_onlineexam')?></h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="pull-left"><?=$this->lang->line('onlineexamreport_classes');?> : <?=count($classes) ? $classes->classes : $this->lang->line('onlineexamreport_select_all_classes') ?></h5>

                    <?php if($sectionID == 0) {?>
                        <h5 class="pull-right"><?=$this->lang->line('onlineexamreport_section')?> : <?=$this->lang->line('onlineexamreport_select_all_section');?></h5>
                    <?php } else { ?>
                        <h5 class="pull-right"><?=$this->lang->line('onlineexamreport_section')?> : <?=count($section) ? $section->section : '' ?></h5>
                    <?php } ?>
                    
                </div>
                <div class="col-sm-12">
                    <?php if(count($onlineexam_user_statuss)) { ?>
	                    <div id="hide-table">
	                        <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
	                            <thead>
	                                <tr>
	                                    <th>#</th>
	                                    <th><?=$this->lang->line('onlineexamreport_photo')?></th>
	                                    <th><?=$this->lang->line('onlineexamreport_name')?></th>
	                                    <?php  if($sectionID == 0 ) { ?>
	                                        <th><?=$this->lang->line('onlineexamreport_section')?></th>
	                                    <?php } ?>
	                                    <?php if($studentID > 0) { ?>
		                                    <th>
		                                    	<?=$this->lang->line('onlineexamreport_subject')?>
		                                    </td>
	                                    <?php } ?>
	                                    <th><?=$this->lang->line('onlineexamreport_datetime')?></th>
	                                    <th><?=$this->lang->line('onlineexamreport_obtained_mark')?></th>
	                                    <th><?=$this->lang->line('onlineexamreport_percentage')?></th>
	                                    <th><?=$this->lang->line('onlineexamreport_status')?></th>
	                                    <th><?=$this->lang->line('action')?></th>
	                                </tr>
	                            </thead>



	                            <?php $i = 1; foreach($onlineexam_user_statuss as $onlineexam_user_status)  { ?>
                                    <tr>
                                        <td data-title="#">
                                            <?php echo $i; ?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('onlineexamreport_photo')?>">
                                            <?php
                                            	if(isset($students[$onlineexam_user_status->userID])) {
	                                             	$array = array(
	                                                    "src" => base_url('uploads/images/'.$students[$onlineexam_user_status->userID]->photo),
	                                                    'width' => '35px',
	                                                    'height' => '35px',
	                                                    'class' => 'img-rounded'
	                                                );
                                            	} else {
                                            		$array = array(
	                                                    "src" => base_url('uploads/images/default.png'),
	                                                    'width' => '35px',
	                                                    'height' => '35px',
	                                                    'class' => 'img-rounded'
	                                                );
                                            	}
                                                echo img($array);
                                            ?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('onlineexamreport_name')?>">
                                            <?=isset($students[$onlineexam_user_status->userID]) ? $students[$onlineexam_user_status->userID]->name : '' ?>
                                        </td>

                                        <?php  if($sectionID == 0 ) { ?>
	                                        <td data-title="<?=$this->lang->line('onlineexamreport_section')?>">
	                                            <?php
	                                            	if(isset($students[$onlineexam_user_status->userID])) {
	                                            		if(isset($sections[$students[$onlineexam_user_status->userID]->sectionID])) {
	                                            			echo $sections[$students[$onlineexam_user_status->userID]->sectionID]->section;
	                                            		}
	                                            	}
	                                            ?>
	                                        </td>
	                                    <?php } ?>

	                                    <?php if($studentID > 0) { ?>
		                                    <td data-title="<?=$this->lang->line('onlineexamreport_subject')?>">
		                                    	<?php 
		                                    		if(isset($onlineexams[$onlineexam_user_status->onlineExamID])) {
		                                    			
		                                    			if(isset($subjects[$onlineexams[$onlineexam_user_status->onlineExamID]->subjectID])) {
		                                    				echo $subjects[$onlineexams[$onlineexam_user_status->onlineExamID]->subjectID]->subject;
		                                    			}
		                                    		}
		                                    	?>
		                                    </td>
	                                    <?php } ?>

	                                    <td data-title="<?=$this->lang->line('onlineexamreport_datetime')?>">
	                                    	<?=date('d M Y - h:i:s', strtotime($onlineexam_user_status->time))?>
	                                    </td>

	                                    <td data-title="<?=$this->lang->line('onlineexamreport_obtained_mark')?>">
	                                    	<?=$onlineexam_user_status->totalMark?>
	                                    </td>

	                                    <td data-title="<?=$this->lang->line('onlineexamreport_percentage')?>">
	                                    	<?=$onlineexam_user_status->totalPercentage?>%
	                                    </td> 

	                                    <td data-title="<?=$this->lang->line('onlineexamreport_status')?>">
	                                    	<?php 
	                                    		if($onlineexam_user_status->statusID == 5) {
	                                    			echo $this->lang->line('onlineexamreport_passed');
	                                    		} elseif($onlineexam_user_status->statusID == 10) {
	                                    			echo $this->lang->line('onlineexamreport_failed');
	                                    		}
	                                    	?>
	                                    </td>
	                                    <td data-title="<?=$this->lang->line('action')?>">
                                    		<a class="btn btn-success btn-sm" target="_blank" href="<?=base_url('onlineexamreport/result/'.$onlineexam_user_status->onlineExamUserStatus)?>"><?=$this->lang->line('view')?></a>
	                                    </td>

                                        
                                   </tr>
                                <?php $i++; } ?>
                                

	                        </table>
	                    </div>
                    <?php } else { ?>
	                    <div class="callout callout-danger">
	                        <p><b class="text-info"><?=$this->lang->line('onlineexamreport_data_not_found')?></b></p>
	                    </div>
	                <?php } ?>

                </div>

            </div>
        </div>


    </div>
</div>
