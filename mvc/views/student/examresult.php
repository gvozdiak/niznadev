<h2 style="margin-top: -5px;">
    <?php 
    if($examresult->statusID == 5) { ?>
        <span class="text-green"><?=$this->lang->line('student_pass')?></span>
    <?php } elseif($examresult->statusID == 10) { ?>
        <span class="text-red"><?=$this->lang->line('student_fail')?></span>
    <?php } ?>
</h2>
<table class="table table-bordered">
    <tbody>
        <tr>
            <td><?=$this->lang->line('student_total_question')?> : <?=$examresult->totalQuestion?></td>
            <td><?=$this->lang->line('student_total_answer')?> : <?=$examresult->totalAnswer?></td>
        </tr>
        <tr>
            <td><?=$this->lang->line('student_total_correctanswer')?> : <?=$examresult->totalCurrectAnswer?></td>
            <td><?=$this->lang->line('student_total_mark')?> : <?=$examresult->totalMark?></td>
        </tr> 
        <tr>
            <td><?=$this->lang->line('student_total_obtainedmark')?> : <?=$examresult->totalObtainedMark?></td>
            <td><?=$this->lang->line('student_total_percentage')?> : <?=$examresult->totalPercentage?> %</td>
        </tr>
    </tbody>
</table>