
<link rel='stylesheet' href='<?php echo base_url('assets/fullcalendar/lib/cupertino/jquery-ui.min.css'); ?>' />
<link rel='stylesheet' href='<?php echo base_url('assets/fullcalendar/fullcalendar.css'); ?>' />
<!-- <link rel='stylesheet' href='<?php echo base_url('assets/fullcalendar/fullcalendar.print.min.css'); ?>' /> -->
<script type="text/javascript" src='<?php echo base_url('assets/fullcalendar/lib/jquery-ui.min.js'); ?>'></script>
<script type="text/javascript" src='<?php echo base_url('assets/fullcalendar/lib/moment.min.js'); ?>'></script>
<script type="text/javascript" src="<?php echo base_url('assets/fullcalendar/fullcalendar.min.js'); ?>"></script>

<style type="text/css">
    /*.fc-today {
        background: black !important
    }*/
    /*.fc-sun { background-color:#dddddd !important; color: #A53F71 !important; }*/
    /*td.fc-sun, td.fc-sat { background-color:#dddddd; }*/
    /*.ui-widget-header {
        background: #5C6BC0 !important;
    }
    .fc-button-group {
        background: #5C6BC0 !important;
    }
    .ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited, a.ui-button, a.ui-button:link, a.ui-button:visited, .ui-button {
        background: #5C6BC0 !important;
    }*/
</style>

<script type="application/javascript">
    $(function() {
        $('#calendar').fullCalendar({
			theme: true,
            customButtons: {
                reload: {
                    text: 'Reload',
                    click: function() {
                       //you code
                    }
                }
            },
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listMonth'
			},
            // firstDay: 3,
			navLinks: true, // can click day/week names to navigate views
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: [
                <?php
                    if(count($events)) {   
                        foreach ($events as $event) {
                            echo '{';
                                echo "title: '".$event->title."', ";
                                echo "start: '".$event->fdate."T".$event->ftime."', ";
                                echo "end: '".$event->tdate."T".$event->ttime."', ";
                                echo "url:'".base_url('event/view/'.$event->eventID)."', ";
                                echo "color  : '#5C6BC0'";
                            echo '},';
                        }
                    }

                    if(count($notices)) {
                        foreach ($notices as $notice) {
                            echo '{';
                                echo "title: '".$notice->title."', ";
                                echo "start: '".$notice->date."', ";
                                echo "end: '".$notice->date."', ";
                                echo "url:'".base_url('notice/view/'.$notice->noticeID)."', ";
                                echo "color  : '#006400'";
                            echo '},';
                        }
                    }

                ?>
			]
		});
    });
</script>
