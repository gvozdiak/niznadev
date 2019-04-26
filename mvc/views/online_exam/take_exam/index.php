<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-user-secret"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr>
                            <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                            <th class="col-sm-3"><?=$this->lang->line('take_exam_name')?></th>
                            <th class="col-sm-2"><?=$this->lang->line('take_exam_status')?></th>
                            <th class="col-sm-1"><?=$this->lang->line('take_exam_duration')?></th>
                            <th class="col-sm-1"><?=$this->lang->line('take_exam_payment')?></th>
                            <th class="col-sm-2"><?=$this->lang->line('take_exam_cost')?></th>
                            <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(count($onlineExams)) { $i = 0; foreach($onlineExams as $onlineExam) {
                            if($usertypeID == '3') {
                                if((($student->classesID == $onlineExam->classID) || ($onlineExam->classID == '0')) && (($student->sectionID == $onlineExam->sectionID) || ($onlineExam->sectionID == '0')) && (($student->studentgroupID == $onlineExam->studentGroupID) || ($onlineExam->studentGroupID == '0')) && (($onlineExam->subjectID == '0') || (in_array($onlineExam->subjectID, $userSubjectPluck)))) { $i++;

                                    $currentdate = 0;
                                    if($onlineExam->examTypeNumber == '4') {
                                        $presentDate = strtotime(date('Y-m-d'));
                                        $examStartDate = strtotime($onlineExam->startDateTime);
                                        $examEndDate = strtotime($onlineExam->endDateTime);
                                    } elseif($onlineExam->examTypeNumber == '5') {
                                        $presentDate = strtotime(date('Y-m-d H:i:s'));
                                        $examStartDate = strtotime($onlineExam->startDateTime);
                                        $examEndDate = strtotime($onlineExam->endDateTime);
                                    }

                                    $lStatusRunning = FALSE;
                                    $lStatusExpire = FALSE;
                                    $lStatusTaken = FALSE;
                                    $lStatusTodayOnly = FALSE;
                                    $paymentExpireStatus = FALSE;

                                    $examLabel = $this->lang->line('take_exam_anytime');
                                    if($onlineExam->examTypeNumber == '4' || $onlineExam->examTypeNumber == '5') {
                                        if($presentDate < $examStartDate) {
                                            $examLabel = $this->lang->line('take_exam_upcoming');
                                        } elseif($presentDate > $examStartDate && $presentDate < $examEndDate) {
                                            $examLabel = $this->lang->line('take_exam_running');
                                            $lStatusRunning = TRUE;
                                        } elseif($presentDate == $examStartDate && $presentDate == $examEndDate) {
                                            $examLabel = $this->lang->line('take_exam_today_only');
                                            $lStatusTodayOnly = TRUE;
                                        } elseif($presentDate > $examStartDate && $presentDate > $examEndDate) {
                                            $examLabel = $this->lang->line('take_exam_expired');
                                            $lStatusExpire = TRUE;
                                        }

                                        if($presentDate > $examStartDate && $presentDate > $examEndDate) {
                                            $paymentExpireStatus = TRUE;
                                        }
                                    } else {
                                        $lStatusRunning = TRUE;
                                    }

                                    if($lStatusRunning) {
                                        if(isset($examStatus[$onlineExam->onlineExamID])) {
                                            $examLabel = $this->lang->line('take_exam_taken');
                                            $lStatusTaken = TRUE;
                                        }
                                    } elseif($lStatusExpire) {
                                        if(isset($examStatus[$onlineExam->onlineExamID])) {
                                            $examLabel = $this->lang->line('take_exam_taken');
                                            $lStatusTaken = TRUE;
                                        }
                                    } elseif($lStatusTodayOnly) {
                                        if(isset($examStatus[$onlineExam->onlineExamID])) {
                                            $examLabel = $this->lang->line('take_exam_taken');
                                            $lStatusTaken = TRUE;
                                        }
                                    }

                                    if($lStatusExpire) {
                                        $examLabel = $this->lang->line('take_exam_expired');
                                    } else {
                                        if($lStatusTaken) {
                                            if($onlineExam->examStatus == 2) {
                                                $examLabel = $this->lang->line('take_exam_retaken');
                                            }
                                        }
                                    }
                                ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('take_exam_name')?>">
                                        <?php if(strlen($onlineExam->name) > 50) {
                                            echo strip_tags(substr($onlineExam->name, 0, 50)."...");
                                        } else {
                                            echo strip_tags(substr($onlineExam->name, 0, 50));
                                        } ?>
                                        -
                                        <?php 
                                            echo $examLabel;                                           
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('take_exam_status')?>">
                                        <?php 
                                            if($onlineExam->examStatus == 1) {
                                                echo $this->lang->line('take_exam_one_time');
                                            } elseif($onlineExam->examStatus == 2) {
                                                echo $this->lang->line('take_exam_multiple_time');
                                            }
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('take_exam_duration')?>">
                                        <?php echo $onlineExam->duration; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('take_exam_payment')?>">
                                        <?=($onlineExam->paid == 1) ? $this->lang->line('take_exam_paid') : $this->lang->line('take_exam_free') ;?>
                                    </td> 
                                    <td data-title="<?=$this->lang->line('take_exam_cost')?>">
                                        <?=($onlineExam->paid == 1) ? number_format($onlineExam->cost, '2') : number_format($onlineExam->cost, '2');?> <?=$siteinfos->currency_code?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php
                                            $paidStatus = 0;
                                            if($onlineExam->paid == 1) {
                                                if(isset($paindingpayments[$onlineExam->onlineExamID])) {
                                                    $paidStatus = 1;
                                                } else {
                                                    if($paymentExpireStatus) {
                                                        $paidStatus = 1;
                                                    } else {
                                                        if($onlineExam->examStatus == 1) {
                                                            if(isset($examStatus[$onlineExam->onlineExamID])) {
                                                                $paidStatus = 1;
                                                            } else {
                                                                $paidStatus = 0;
                                                            }
                                                        } else {
                                                            if(isset($paindingpayments[$onlineExam->onlineExamID])) {
                                                                $paidStatus = 1;
                                                            } else {
                                                                $paidStatus = 0;
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {
                                                $paidStatus = 1;
                                            }
                                        ?>
                                        <button class="btn btn-success btn-xs mrg" onclick="newPopup('<?=base_url('take_exam/instruction/'.$onlineExam->onlineExamID)?>', '<?=$paidStatus?>', '<?=$onlineExam->onlineExamID?>')" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?=$this->lang->line('panel_title')?>"><i class="fa fa-columns"></i></button>

                                        <?php
                                            if($onlineExam->paid && ($onlineExam->examStatus == 2) && !($paymentExpireStatus))  {
                                                echo '<a href="#addpayment" id="'.$onlineExam->onlineExamID.'" class="btn btn-primary btn-xs mrg getpaymentinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('take_exam_add_payment').'"></i></a>';
                                            } elseif($onlineExam->paid && !($lStatusTaken) && !isset($payments[$onlineExam->onlineExamID]) && !($paymentExpireStatus)) {
                                                echo '<a href="#addpayment" id="'.$onlineExam->onlineExamID.'" class="btn btn-primary btn-xs mrg getpaymentinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('take_exam_add_payment').'"></i></a>';
                                            }

                                            if($onlineExam->paid) {
                                                echo '<a href="#paymentlist" id="'.$onlineExam->onlineExamID.'" class="btn btn-info btn-xs mrg getpaymentlistinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-list-ul" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('take_exam_view_payments').'"></i></a>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php } } } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<form class="form-horizontal" role="form" method="post" id="paymentAddDataForm" enctype="multipart/form-data" action="<?=base_url('take_exam/index')?>">
    <div class="modal fade" id="addpayment">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?=$this->lang->line('take_exam_add_payment')?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="text" name="onlineExamID" id="onlineExamID" style="display:none">
                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <div class="form-group <?=form_error('paymentAmount') ? 'has-error' : ''; ?>" id="paymentAmountErrorDiv">
                                    <label for="paymentAmount"><?=$this->lang->line('take_exam_payment_amount')?> <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" id="paymentAmount" name="paymentAmount" readonly="readonly">
                                    <span id="paymentAmountError"><?=form_error('paymentAmount')?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <div class="form-group <?=form_error('paymentMethod') ? 'has-error' : ''; ?>" id="paymentMethodErrorDiv">
                                    <label for="paymentMethod"><?=$this->lang->line('take_exam_payment_method')?> <span class="text-red">*</span></label>
                                    <?php
                                        $paymentmethodArray['Select'] = $this->lang->line('take_exam_select_payment_method');
                                        if($paymentsetting->paypal_status == true) {
                                            $paymentmethodArray['Paypal'] = 'Paypal';
                                        }

                                        if($paymentsetting->stripe_status == true) {
                                            $paymentmethodArray['Stripe'] = 'Stripe';
                                        }

                                        if($paymentsetting->payumoney_status == true) {
                                            $paymentmethodArray['Payumoney'] = 'PayUMoney';
                                        }

                                        if($paymentsetting->voguepay_status == true) {
                                            $paymentmethodArray['Voguepay'] = 'Voguepay';
                                        }

                                        echo form_dropdown("paymentMethod", $paymentmethodArray, set_value("paymentMethod"), "id='paymentMethod' class='form-control select2'");
                                    ?>
                                    <span id="paymentMethodError"><?=form_error('paymentMethod')?></span>
                                </div>
                            </div>
                        </div>

                        <div id="stripeDiv" style="display: none;">
                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <div class="form-group <?=form_error('stripe_card_number') ? 'has-error' : ''; ?>" >
                                        <label for="amount"><?=$this->lang->line("take_exam_card_number")?> <span class="text-red">*</span></label>
                                        <input type="text" class="form-control" id="stripe_card_number" name="stripe_card_number" value="<?=set_value('stripe_card_number', null)?>" placeholder="4242 4242 4242 4242">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group <?=(form_error('stripe_expire_month')) ? 'has-error' : ''; ?>" >
                                            <label for="amount"><?=$this->lang->line("take_exam_month")?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="stripe_expire_month" name="stripe_expire_month" value="<?=set_value('stripe_expire_month', null)?>" placeholder="mm">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group <?=(form_error('stripe_expire_year')) ? 'has-error' : ''; ?>" >
                                            <label for="amount"><?=$this->lang->line("take_exam_year")?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="stripe_expire_year" name="stripe_expire_year" value="<?=set_value('stripe_expire_year', null)?>" placeholder="yyyy">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <div class="form-group <?=form_error('stripe_cvv') ? 'has-error' : ''; ?>" >
                                        <label for="amount"><?=$this->lang->line("take_exam_cvv")?> <span class="text-red">*</span></label>
                                        <input type="text" class="form-control" id="stripe_cvv" name="stripe_cvv" value="<?=set_value('stripe_cvv', null)?>" placeholder="123">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="payumoneyDiv" style="display: none;">
                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <div class="form-group <?=form_error('payumoney_first_name') ? 'has-error' : ''; ?>" >
                                        <label for="payumoney_first_name"><?=$this->lang->line("take_exam_first_name")?></label> <span class="text-red">*</span>
                                        <input type="text" class="form-control" id="payumoney_first_name" name="payumoney_first_name" value="<?=set_value('payumoney_first_name', null)?>" >
                                        <span class="text-red">
                                            <?php echo form_error('payumoney_first_name'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <div class="form-group <?=form_error('payumoney_email') ? 'has-error' : ''; ?>" >
                                        <label for="payumoney_email"><?=$this->lang->line("take_exam_email")?></label> <span class="text-red">*</span>
                                        <input type="text" class="form-control" id="payumoney_email" name="payumoney_email" value="<?=set_value('payumoney_email', null)?>" >
                                        <span class="text-red"><?php echo form_error('payumoney_email'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <div class="form-group <?=form_error('payumoney_phone') ? 'has-error' : ''; ?>" >
                                        <label for="payumoney_phone" ><?=$this->lang->line("take_exam_phone")?></label> <span class="text-red">*</span>
                                        <input type="text" class="form-control" id="payumoney_phone" name="payumoney_phone" value="<?=set_value('payumoney_phone', null)?>" >
                                        <span class="text-red">
                                            <?php echo form_error('payumoney_phone'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                    <input type="submit" id="add_payment_button" class="btn btn-success" value="<?=$this->lang->line("take_exam_add_payment")?>" />
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="paymentlist">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('take_exam_view_payments')?></h4>
            </div>
            <div class="modal-body">
                <div id="hide-table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('take_exam_payment_date')?></th>
                                <th><?=$this->lang->line('take_exam_payment_method')?></th>
                                <th><?=$this->lang->line('take_exam_exam_status')?></th>
                            </tr>
                        </thead>
                        <tbody id="payment-list-body">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    function newPopup(url, paidStatus, onlineExamID) {
        var myWindowStatus = false;
        if(paidStatus == 1) {
            myWindowStatus = true;
            myWindow = window.open(url,'_blank',"width=1000,height=650,toolbar=0,location=0,scrollbars=yes");
            runner();
        } else {
            var onlineExamID =  onlineExamID;
            if(onlineExamID > 0) {
                $('#onlineExamID').val(onlineExamID);
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('take_exam/getpaymentinfo')?>",
                    data: {'onlineExamID' : onlineExamID},
                    dataType: "html",
                    success: function(data) {
                        $('#paymentAmount').val('');
                        var response = JSON.parse(data);
                        if(response.status == true) {
                            $('#paymentAmount').val(response.payableamount);
                        } else {
                            $('#paymentAmount').val('0.00');
                        }
                    }
                });
            }
            $('#addpayment').modal('show');
        }

        $.ajax({
            type: 'POST',
            url: "<?=base_url('take_exam/paymentChecking')?>",
            data: {'onlineExamID' : onlineExamID},
            dataType: "html",
            success: function(data) {
                if(data == 'TRUE' && myWindowStatus == true) {
                    myWindow.close();

                    if(onlineExamID > 0) {
                        $('#onlineExamID').val(onlineExamID);
                        $.ajax({
                            type: 'POST',
                            url: "<?=base_url('take_exam/getpaymentinfo')?>",
                            data: {'onlineExamID' : onlineExamID},
                            dataType: "html",
                            success: function(data) {
                                $('#paymentAmount').val('');
                                var response = JSON.parse(data);
                                if(response.status == true) {
                                    $('#paymentAmount').val(response.payableamount);
                                } else {
                                    $('#paymentAmount').val('0.00');
                                }
                            }
                        });
                    }
                    $('#addpayment').modal('show');
                }
            }
        });
    }

    function runner() {
        url = localStorage.getItem('redirect_url');
        if(url) {
            localStorage.clear();
            window.location = url;
        }
        setTimeout(function() {
            runner();
        }, 500);
    }

    $(document).change(function() {
        var paymentMethod = $('#paymentMethod').val();
        if (paymentMethod == "Stripe") {
            $('#stripeDiv').show();
            $('#payumoneyDiv').hide();
        } else if (paymentMethod == "Payumoney") {
            $('#payumoneyDiv').show();
            $('#stripeDiv').hide();
        } else {
            $('#stripeDiv').hide();
            $('#payumoneyDiv').hide();
        }
    });

    $('.getpaymentinfobtn').click(function() {
        var onlineExamID =  $(this).attr('id');
        if(onlineExamID > 0) {
            $('#onlineExamID').val(onlineExamID);
            $.ajax({
                type: 'POST',
                url: "<?=base_url('take_exam/getpaymentinfo')?>",
                data: {'onlineExamID' : onlineExamID},
                dataType: "html",
                success: function(data) {
                    $('#paymentAmount').val('');
                    var response = JSON.parse(data);
                    if(response.status == true) {
                        $('#paymentAmount').val(response.payableamount);
                    } else {
                        $('#paymentAmount').val('0.00');
                    }
                }
            });
        }   
    });

    $('.getpaymentlistinfobtn').click(function() {
        var onlineExamID =  $(this).attr('id');
        if(onlineExamID > 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('take_exam/paymentlist')?>",
                data: {'onlineExamID' : onlineExamID},
                dataType: "html",
                success: function(data) {
                    $('#payment-list-body').children().remove();
                    $('#payment-list-body').append(data);
                }
            });
        }   
    });
</script>

<?php if(count($validationErrors)) { ?>
    <script type="application/javascript">
        $(window).load(function() {
            var onlineExamID =  "<?=$validationOnlineExamID?>";
            if(onlineExamID > 0) {
                $('#onlineExamID').val(onlineExamID);
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('take_exam/getpaymentinfo')?>",
                    data: {'onlineExamID' : onlineExamID},
                    dataType: "html",
                    success: function(data) {
                        $('#paymentAmount').val('');
                        var response = JSON.parse(data);
                        if(response.status == true) {
                            $('#paymentAmount').val(response.payableamount);
                        } else {
                            $('#paymentAmount').val('0.00');
                        }
                    }
                });
            }
            $('#addpayment').modal('show');
            if($('#paymentMethod').val() == 'Stripe') {
                $('#stripeDiv').show();
                $('#payumoneyDiv').hide();
            } else if($('#paymentMethod').val() == "Payumoney") {
                $('#payumoneyDiv').show();
                $('#stripeDiv').hide();
            } else {
                $('#stripeDiv').hide();
                $('#payumoneyDiv').hide();
            }
        });
    </script>
<?php } ?>