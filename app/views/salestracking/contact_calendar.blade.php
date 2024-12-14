<link href="{{asset('assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css')}}" rel="stylesheet">
@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
              <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                  SALES TRACKING CONTACT CALENDAR
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                  <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                    <b><i>View Employee Activity Data. </i></b>
                  </header>
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <?php $uriSegment = Request::segment(2);?> 
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('salestracking/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
               <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <tbody>
                      <tr>
                        <td>{{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}</td>
                        <td>{{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}</td>
                        <td>{{Form::select('employeeFilter',array(''=>'Contact Person Filter')+$gpg_employee, null, ['id' => 'employeeFilter', 'class'=>'form-control m-bot15'])}}</td>
                        <td><select name="m" class='form-control m-bot15'>
                        <?php
                          $year_display = 20;
                          if(!isset($slm)){
                            $slm = "";
                          }
                          if(!isset($sly)){
                            $sly = "";
                          }
                          if(!isset($group_view)){
                            $group_view = "";
                          }
                          $fg = "";
                          $fg1 = "";
                          $fg2 = "";
                          if(isset($_REQUEST["m"]))
                            $m = $_REQUEST["m"];
                          else
                            $m = ($slm) ? $slm : date('m');
                          if(isset($_REQUEST["y"]))
                            $y = $_REQUEST["y"];
                          else
                            $y = ($sly) ? $sly : date('Y');
                          for($f=1; $f<=12; $f++){
                          $selected = ($f == $m) ? " selected" : "";      
                          echo("<option value=\"$f\"$selected>".date('F', mktime(0,0,0,$f,1,2000))."</option>");
                          }
                        ?>
                        </select></td>
                        <td>
                        <select name="y"  class='form-control m-bot15'>
                        <?php
                          $thisyear = date('Y');
                          for($year = $thisyear - $year_display; $year <= $thisyear + $year_display; $year++){
                          $selected = ($year == $y) ? " selected" : "";
                          echo("<option value=\"$year\"$selected>".date('Y', mktime(0,0,0,1,1,$year))."</option>");
                          }
                          ?>
                        </select>
                        </td>
                        <td>{{Form::submit('Submit', ['id' => 'submit', 'class'=>'btn btn-danger'])}}</td>
                      </tr>
                    </tbody>
                  </table>
                </section>
                {{Form::close()}}
               <div id="calendar" class="has-toolbar"></div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->  
<script src="{{asset('assets/fullcalendar/fullcalendar/fullcalendar.min.js')}}"></script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script> 
<script type="text/javascript">
  $('#group_view').change(function(){
    var vl = $(this).val();
    if (vl != '') {
      location.href="{{URL::to('job/job_calendar_view')}}";
    }
  });
  var Script = function () {
    /* initialize the external events*/
    $('#external-events div.external-event').each(function() {
        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };
        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);
        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });
    });
    /*--- initialize the calendar---*/
    var date = '{{date("Y-m-d",strtotime($y-$m-01))}}';
    var d = '{{date("d",strtotime($y-$m-01))}}';
    var m = "{{$m}}";
    var y = "{{$y}}";
    $('#calendar').fullCalendar({
        editable: false,
        droppable: false, // this allows things to be dropped onto the calendar !!!
        drop: function(date, allDay) { // this function is called when something is dropped
            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');
            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);
            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;
            // render the event on the calendar
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                $(this).remove();
            }
        },
        events: [<?php
            foreach ($events as $key => $value) {
                 echo '{title:"'.$value['title'].'",start: "'.date('Y-m-d',strtotime($value['start'])).'"},';
            }        
        ?>]
    });
}();
$('.default-date-picker').datepicker({
    format: 'yyyy-mm-dd'
});
$('#calendar').fullCalendar('gotoDate','{{$y}}','{{$m-1}}');
</script>
@stop