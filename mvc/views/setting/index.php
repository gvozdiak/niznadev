


<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-gears"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_setting')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->

    <style type="text/css">
        .setting-fieldset {
            border: 1px solid #DBDEE0 !important;
            padding: 15px !important;
            margin: 0 0 25px 0 !important;
            box-shadow: 0px 0px 0px 0px #000;
        }

        .setting-legend {
            font-size: 1.1em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            color: #428BCA;
            padding: 5px 15px;
            border: 1px solid #DBDEE0 !important;
            margin: 0px;
        }
    </style>

    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
        <div class="box-body">
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line('setting_site_configaration')?></legend>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('sname') ? 'has-error' : ''?>" >
                            <div class="col-sm-12">
                                <label for="sname"><?=$this->lang->line("setting_name")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="set your site title here"></i>
                                </label>
                                <input type="text" class="form-control" id="sname" name="sname" value="<?=set_value('sname', $setting->sname)?>" />
                                <span class="control-label"><?=form_error('sname'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('phone') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="phone"><?=$this->lang->line("setting_phone")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set organization phone number here"></i>
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', $setting->phone)?>" >
                                <span class="control-label"><?=form_error('phone'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('email') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="email"><?=$this->lang->line("setting_email")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set organization email address here"></i>
                                </label>
                                <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $setting->email)?>" >
                                <span class="control-label"><?=form_error('email'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('address') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="address"><?=$this->lang->line("setting_address")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set organization address here"></i>
                                </label>
                                <textarea class="form-control" style="resize:none;" id="address" name="address"><?=set_value('address', $setting->address)?></textarea>
                                <span class="control-label">
                                    <?=form_error('address'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('footer') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="footer"><?=$this->lang->line("setting_footer")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set site footer text here"></i>
                                </label>
                                <input type="text" class="form-control" id="footer" name="footer" value="<?=set_value('footer', $setting->footer)?>" >
                                <span class="control-label">
                                    <?=form_error('footer'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('currency_code') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="currency_code">
                                    <?=$this->lang->line("setting_currency_code")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set organization currency code like USD or GBP"></i>
                                </label>
                                <input type="text" class="form-control" id="currency_code" name="currency_code" value="<?=set_value('currency_code', $setting->currency_code)?>" >
                                <span class="control-label">
                                    <?=form_error('currency_code'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('currency_symbol') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="currency_symbol"><?=$this->lang->line("setting_currency_symbol")?> &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set organization currency system here like $ or £"></i>
                                </label>
                                <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" value="<?=set_value('currency_symbol', $setting->currency_symbol)?>" >
                                <span class="control-label">
                                    <?=form_error('currency_symbol'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('language_status')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label><?=$this->lang->line("setting_language")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable language for top section"></i>
                                </label>
                                <?php
                                    $languageArray[0] = $this->lang->line('setting_enable');
                                    $languageArray[1] = $this->lang->line('setting_disable');
                                    echo form_dropdown("language_status", $languageArray, set_value("language_status",$setting->language_status), "id='language_status' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('language_status'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('lang') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="lang"><?=$this->lang->line("setting_lang")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Select organization default language here"></i>
                                </label>
                                <?php
                                    echo form_dropdown("language", array("english" => $this->lang->line("setting_english"),
                                    "bengali" => $this->lang->line("setting_bengali"),
                                    "arabic" => $this->lang->line("setting_arabic"),
                                    "chinese" => $this->lang->line("setting_chinese"),
                                    "french" => $this->lang->line("setting_french"),
                                    "german" => $this->lang->line("setting_german"),
                                    "hindi" => $this->lang->line("setting_hindi"),
                                    "indonesian" => $this->lang->line("setting_indonesian"),
                                    "italian" => $this->lang->line("setting_italian"),
                                    "portuguese" => $this->lang->line("setting_portuguese"),
                                    "romanian" => $this->lang->line("setting_romanian"),
                                    "russian" => $this->lang->line("setting_russian"),
                                    "spanish" => $this->lang->line("setting_spanish"),
                                    "thai" => $this->lang->line("setting_thai"),
                                    "turkish" => $this->lang->line("setting_turkish"),
                                    ),
                                    set_value("lang", $setting->language), "id='lang' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?=form_error('lang'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('note') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="note"><?=$this->lang->line("setting_note")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Enable/Disable module helper note"></i>
                                </label>
                                <?php
                                    $noteArray[1] = $this->lang->line('setting_enable');
                                    $noteArray[0] = $this->lang->line('setting_disable');
                                    echo form_dropdown("note", $noteArray, set_value("note",$setting->note), "id='note' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?=form_error('note'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('google_analytics') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="google_analytics"><?=$this->lang->line("setting_google_analytics")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set site google_analytics code"></i>
                                </label>
                                <input type="text" class="form-control" id="google_analytics" name="google_analytics" value="<?=set_value('google_analytics', $setting->google_analytics)?>" >
                                <span class="control-label">
                                    <?php echo form_error('google_analytics'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('profile_edit')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label><?=$this->lang->line("setting_profile_edit")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable for profile edit"></i>
                                </label>
                                <?php
                                    $profileEditArray[1] = $this->lang->line('setting_enable');
                                    $profileEditArray[0] = $this->lang->line('setting_disable');
                                    echo form_dropdown("profile_edit", $profileEditArray, set_value("profile_edit",$setting->profile_edit), "id='profile_edit' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('profile_edit'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('time_zone') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="time_zone"><?=$this->lang->line("setting_time_zone")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Select your region time zone. We define a time zone as a region where the same standard time is used"></i>
                                </label>
                                    <?php
                                        $path = APPPATH."config/timezones_class.php";
                                        if(@include($path)) {
                                            $timezones_cls = new Timezones();
                                            $timezones = $timezones_cls->get_timezones();
                                            unset($timezones['']);
                                            $selectTimeZone['none'] = $this->lang->line('setting_select_time_zone');
                                            $timeZones = array_merge($selectTimeZone, $timezones);

                                            echo form_dropdown("time_zone", $timeZones, set_value("time_zone", $setting->time_zone), "id='time_zone' class='form-control select2'");
                                        }
                                    ?>
                                <span class="control-label">
                                    <?=form_error('time_zone'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('photo') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="photo"><?=$this->lang->line("setting_photo")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set organization logo here"></i>
                                </label>
                                <div class="input-group image-preview">
                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                            <span class="fa fa-remove"></span>
                                            <?=$this->lang->line('setting_clear')?>
                                        </button>
                                        <div class="btn btn-success image-preview-input">
                                            <span class="fa fa-repeat"></span>
                                            <span class="image-preview-input-title">
                                            <?=$this->lang->line('setting_file_browse')?></span>
                                            <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                        </div>
                                    </span>
                                </div>
                                <span class="control-label">
                                    <?=form_error('photo'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line('setting_auto_update')?></legend>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('auto_update_notification')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label><?=$this->lang->line("setting_auto_update_notification")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable for auto update notification. only main system admin can see the update notification"></i>
                                </label>
                                <?php
                                    $autoupdateArray[1] = $this->lang->line('setting_enable');
                                    $autoupdateArray[0] = $this->lang->line('setting_disable');
                                    echo form_dropdown("auto_update_notification", $autoupdateArray, set_value("auto_update_notification",$setting->auto_update_notification), "id='auto_update_notification' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('auto_update_notification'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line('setting_captcha')?></legend>
                <div class="col-sm-4">
                    <div class="form-group <?php if(form_error('captcha_status')) { echo 'has-error'; } ?>">
                        <div class="col-sm-12">
                            <label><?=$this->lang->line("setting_captcha")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Check for disable captcha in login"></i>
                            </label>
                            <?php
                                $captchaArray[0] = $this->lang->line('setting_enable');
                                $captchaArray[1] = $this->lang->line('setting_disable');
                                echo form_dropdown("captcha_status", $captchaArray, set_value("captcha_status",$setting->captcha_status), "id='captcha_status' class='form-control select2'");
                            ?>

                            <span class="control-label">
                                <?php echo form_error('captcha_status'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group <?php if(form_error('recaptcha_site_key')) { echo 'has-error'; } ?>" id="recaptcha_site_key_id">
                        <div class="col-sm-12">
                            <label for="recaptcha_site_key">
                                <?=$this->lang->line("setting_recaptcha_site_key")?>
                                &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Set recaptcha site key. Becareful If it's invalid then you cann't login."></i>
                            </label>
                            <input type="text" class="form-control" id="recaptcha_site_key" name="recaptcha_site_key" value="<?=set_value('recaptcha_site_key', $setting->recaptcha_site_key)?>" >
                            <span class="control-label">
                                <?php echo form_error('recaptcha_site_key'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group <?php if(form_error('recaptcha_secret_key')) { echo 'has-error'; } ?>" id="recaptcha_secret_key_id" >
                        <div class="col-sm-12">
                            <label for="recaptcha_secret_key"><?=$this->lang->line("setting_recaptcha_secret_key")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Set recaptcha secret key. Becareful If it's invalid then you cann't login."></i>
                            </label>
                            <input type="text" class="form-control" id="recaptcha_secret_key" name="recaptcha_secret_key" value="<?=set_value('recaptcha_secret_key', $setting->recaptcha_secret_key)?>" >
                            <span class="control-label">
                                <?php echo form_error('recaptcha_secret_key'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="form-group">
                <div class="col-sm-8">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_setting")?>" >
                </div>
            </div>
        </div>
    </form>
</div>

<div class="box" style="margin-bottom: 40px" >
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-th-large"></i> <?=$this->lang->line('backend_theme_setting')?></h3>

    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                
                <ul class="list-unstyled clearfix">
                    <?php 
                        if(count($themes)) {
                            foreach ($themes as $theme) {
                    ?>
                    
                    <li class="backendThemeMainWidht" style="float:left; padding: 5px;">
                        <a id="<?=$theme->themesID?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$theme->themename?>"

                         data-skin="skin-green-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4); cursor: pointer;" class="clearfix full-opacity-hover backendThemeEvent">
                            <div>
                                <span class="backendThemeHeadHeight" style="display:block; width: 20%; float: left; background-color: <?=$theme->topcolor?>" >
                                    
                                </span>

                                <span class="backendThemeHeadHeight" style="display:block; width: 80%; float: left; background-color: <?=$theme->topcolor?>">
                                </span>
                            </div>

                            <div>
                                <span class="backendThemeBodyHeight" style="display:block; width: 20%; float: left; background-color: <?=$theme->leftcolor?>">
                                </span>
                                <span class="backendThemeBodyHeight" style="display:block; width: 80%; float: left; background: #f4f5f7" id="themeBodyContent-<?=strtolower(str_replace(' ', '', $theme->themename))?>">
                                <?php  ?>
                                        <?php if($setting->backend_theme == strtolower(str_replace(' ', '', $theme->themename)))  {?>
                                        <center class="backendThemeBodyMargin">
                                            <button type="button" class="btn btn-danger">
                                                <i  class="fa fa-check-circle"></i>
                                            </button>
                                        </center>
                                        <?php } ?>
                                </span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px">
                            <?=$theme->themename?>
                        </p>
                    </li>


                    <?php            
                            }
                        }
                    ?>
                </ul>

            </div>
        </div>
    </div>
</div>

<?php if(form_error('recaptcha_site_key') || form_error('recaptcha_secret_key')) { ?>
<script type="text/javascript">
    $('#recaptcha_site_key_id').show(); 
    $('#recaptcha_secret_key_id').show();  
</script>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.backendThemeEvent').click(function() {
            var id = $(this).attr('id');
            if(id) {
                $.ajax({
                    type: 'POST',
                    // dataType: "json",
                    url: "<?=base_url('setting/backendtheme')?>",
                    data: "id=" + id,
                    dataType: "html",
                    success: function(data) {
                        $('#headStyleCSSLink').attr('href', "<?=base_url('assets/inilabs/themes/')?>"+data+"/style.css");
                        $('#headInilabsCSSLink').attr('href', "<?=base_url('assets/inilabs/themes/')?>"+data+"/inilabs.css");
                        
                        $html = '<center class="backendThemeBodyMargin"><button type="button" class="btn btn-danger"><i  class="fa fa-check-circle"></i></button></center>';
                        $('.backendThemeBodyMargin').hide();
                        $('#themeBodyContent-'+data).html($html);
                        if(data) {
                            toastr["success"]("<?=$this->lang->line('menu_success');?>")
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
                    }
                });
            }
        });
    });

    // $(document).ready(function() {
    //     checkedStatus();
    // });

    $('#captcha_status').change(function() {
        var captcha_status = $(this).val();
        if(captcha_status == 0) {
            $('#recaptcha_site_key_id').show(300); 
            $('#recaptcha_secret_key_id').show(300);  
        } else {
            $('#recaptcha_site_key_id').hide(300); 
            $('#recaptcha_secret_key_id').hide(300); 
        }
    });

    <?php if($captcha_status == 0) { ?>
            $('#recaptcha_site_key_id').show(300); 
            $('#recaptcha_secret_key_id').show(300);
       <?php } else { ?>
            $('#recaptcha_site_key_id').hide(300); 
            $('#recaptcha_secret_key_id').hide(300); 
    <?php } ?>



    $(document).on('click', '#close-preview', function(){ 
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
               $('.image-preview').popover('show');
               $('.content').css('padding-bottom', '120px');
            }, 
             function () {
               $('.image-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );    
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?=$this->lang->line('setting_file_browse')?>"); 
        }); 
        // Create the preview image
        $(".image-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('setting_clear')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '120px');
            }        
            reader.readAsDataURL(file);
        });  
    });

    $( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );
</script>