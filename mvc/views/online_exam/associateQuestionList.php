<?php
if(count($onlineExamQuestions)) {
    foreach ($onlineExamQuestions as $key => $onlineExamQuestion) {
        $question = isset($questions[$onlineExamQuestion->questionID]) ? $questions[$onlineExamQuestion->questionID] : '';
        $questionOptions = isset($options[$onlineExamQuestion->questionID]) ? $options[$onlineExamQuestion->questionID] : [];
        $questionAnswers = isset($answers[$onlineExamQuestion->questionID]) ? $answers[$onlineExamQuestion->questionID] : [];
        if($question != '') {
            if($question->typeNumber == 1 || $question->typeNumber == 2) {
                $questionAnswers = pluck($questionAnswers, 'optionID');
            }
            $optionCount = $question->totalOption;
            ?>
            <div class="clearfix">
                <div class="question-body">
                    <label class="lb-content question-color"><a href="<?=base_url('question_bank/edit/'.$question->questionBankID)?>" target="_blank"><span class="questionNumber<?=$question->questionBankID?>"> <?=$key+1?>  </span>. <?=($question->question)?></a></label>
                    <label class="lb-mark"> <?= $question->mark != "" ? $question->mark.' '.$this->lang->line('online_exam_question_mark') : ''?> </label>
                    <?php if($question->upload != '') { ?>
                        <div>
                            <img style="width:220px;height:120px;" src="<?=base_url('uploads/images/'.$question->upload)?>" alt="">
                        </div>
                    <?php } ?>
                </div>

                <div class="question-answer">
                    <table class="table">
                        <tr>
                        <?php
                            $tdCount = 0;
                            $oc = 1;
                            foreach ($questionOptions as $option) {
                                $checked = '';
                                if(in_array($option->optionID, $questionAnswers)) {
                                    $checked = 'checked';
                                }

                                if($optionCount >= $oc) { $oc++; ?>
                                <td>
                                    <input id="option<?=$option->optionID?>" value="1" name="Q_option_<?=$onlineExamQuestion->questionID?>" type="<?=$question->typeNumber == 1 ? 'radio' : 'checkbox'?>" <?=$checked?> disabled>
                                    <label for="option<?=$option->optionID?>">
                                        <span class="fa-stack <?=$question->typeNumber == 1 ? 'radio-button' : 'checkbox-button'?>">
                                            <i class="active fa fa-check">
                                            </i>
                                        </span>
                                        <span ><?=$option->name?></span>
                                        <?php
                                            if(!is_null($option->img) && $option->img != "") {
                                                ?>
                                                <img src="<?=base_url('uploads/images/'.$option->img)?>" style="width: 100px;height: 80px;display: block;"/>
                                                <?php
                                            }
                                        ?>

                                    </label>
                                </td>
                                <?php
                                }
                                $tdCount++;
                                if($tdCount == 2) {
                                    $tdCount = 0;
                                    echo "</tr><tr>";
                                }
                            }

                            if($question->typeNumber == 3) {
                                foreach ($questionAnswers as $answerKey => $answer) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="button" value="<?=$answerKey+1?>"> <input class="fillInTheBlank" id="answer<?=$answer->answerID?>" name="option" value="<?=$answer->text?>" type="text" disabled>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                        ?>
                        </tr>
                    </table>
                </div>
                <span class="pull-right"><button onclick="javascript:void(0);removeQuestion(<?=$onlineExamQuestion->onlineExamQuestionID?>)" class="btn btn-danger btn-xs mrg"><i class='fa fa-trash-o'></i> <?=$this->lang->line('online_exam_remove_question')?> <span class="questionNumber<?=$question->questionBankID?>"><?=$key+1?></span></button>
            </div>
            <br/>
            <?php
        }
    }
} else {
    echo "<p class='text-center'>".$this->lang->line('online_exam_no_question')."</p>";
}
?>