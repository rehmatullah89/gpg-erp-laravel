@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<head>
  <style>
#no-more-tables td{
  padding-left: 1%;
}

  </style>

</head>
              <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                  TIMESHEET MANAGEMENT <sub>(Fixing Details)</sub>
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <script type="text/javascript"> var pre_count = '<?php echo count($timesheet)-1; ?>';</script>
              <?php $att_index = '';
                    $name = ''; 
                    $date = ''; 
                    $emp_id = '';
                    $JobNumber = '';
                    $GPG_timetype_id = '';
                    $gpg_task_type = '';
                    $gpg_activity_id = '';
                    $workdone = '';
                    $recommendations = '';
                    $mileage = '';
                    $time_in = '';
                    $time_out = '';
                    $time_diff_dec = '';
                    $complete_flag = '';
                    $GPG_timesheet_id = '';

                    foreach ($timesheet as $key2 => $value2) {
                      foreach ($value2 as $key => $value) {
                        if($key == 'name')
                            $name = $value;
                        else if($key == 'date')      
                            $date = $value;
                        else if($key == 'GPG_employee_Id')      
                            $emp_id = $value;   
                        else if($key == 'GPG_timesheet_id')      
                            $GPG_timesheet_id = $value;   
                      }
                    }

              ?>
                <div class="container-fluid">
                  <b>Time sheet for:{{$name}}</b><br/>  
                  <b>Date: <?php echo date("F d, Y l", strtotime($date));?></b>  
                </div>
                 <div class="panel-body">
                              <section id="unseen">
                                {{ Form::open(array('before' => 'csrf' ,'url'=>route('timesheet.updateTimesheet'), 'files'=>true, 'method' => 'post')) }}
                                {{ Form::hidden('date', $date) }}
                                {{ Form::hidden('emp_id', $emp_id) }}
                                {{ Form::hidden('GPG_timesheet_id', $GPG_timesheet_id) }}
                                 <section id="no-more-tables" >
                                <table class="table table-bordered table-striped table-condensed cf" id="mytable" style="text-align: center;">
                                  <thead>
                                  <tr>
                                      <th>Time Type</th>
                                      <th width="240px">Job Number</th>
                                      <th class="numeric">Time In </th>
                                      <th class="numeric">Time Out </th>
                                      <th class="numeric">Hour(s) - Minute(s)</th>
                                      <th class="numeric">Job Completed</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($timesheet as $key1=>$value1) 
                                    @foreach($value1 as $key=>$value) 
                                     <?php  
                                          if ($key1 == '0'){
                                            $att_index = "";
                                            $att_id = "";
                                          }
                                          else{
                                            $att_index = "_".$key1;
                                            $att_id = $key1;
                                          }
                                           
                                          if($key == 'job_num')      
                                             $JobNumber = $value;                     
                                          else if($key == 'GPG_timetype_id')      
                                             $GPG_timetype_id = $value;
                                          else if($key == 'gpg_task_type')      
                                             $gpg_task_type = $value;
                                          else if($key == 'gpg_activity_id')      
                                             $gpg_activity_id = $value;  
                                          else if($key == 'workdone')      
                                             $workdone = $value;  
                                          else if($key == 'recommendations')      
                                             $recommendations = $value;  
                                          else if($key == 'mileage')      
                                             $mileage = $value;
                                          else if($key == 'time_in')      
                                             $time_in = $value;             
                                          else if($key == 'time_out')      
                                             $time_out = $value;
                                          else if($key == 'time_diff_dec')      
                                             $time_diff_dec = $value; 
                                          else if($key == 'complete_flag')      
                                             $complete_flag = $value;
                                     ?>
                                    @endforeach
                                  <tr id="main_tr">
                                      <td style="padding-left:5px" data-title="Time Type:"><select id="time_type" name="time_type<?php echo $att_index;?>" class='form-control m-bot15'>
                                        @foreach($time_type as $id=>$value)
                                        <?php 
                                        if ($GPG_timetype_id == $id) {
                                            echo "<option selected='selected' value='$id' id='$id'>$value</option>" ;
                                        }
                                        else{
                                              echo "<option value='$id' id='$id'>$value</option>" ;
                                        } ?>
                                        @endforeach
                                      </select>
                                      </td>
                                      <td style="padding-left:5px" data-title="Job Number:">
                                      {{ Form::text('JobNumber'.$att_index.'',$JobNumber, array('class' => 'form-control','id' => 'JobNumber'.$att_index.'','required')) }}
                                      <br/>
                                      <div id="type_task_div<?php echo $att_index;?>"></div>
                                      <div id="project_activity_div<?php echo $att_index;?>"></div>
                                      </td>
                                      <td  style="padding-left:5px" class="numeric" align="center" data-title="Time In:">
                                         <div class="col-md-10">
                                          <div class="input-group bootstrap-timepicker">
                                             {{ Form::text('time_in'.$att_index.'','', array('class' => 'form-control timepicker-default','id' => 'time_in'.$att_index.'','required')) }}
                                                <span class="input-group-btn">
                                             {{Form::button('<i class="fa fa-clock-o"></i>', array('class' => 'btn btn-default'))}}
                                                </span>
                                          </div>
                                         </div>
                                      </td>
                                      <td  style="padding-left:5px" class="numeric" align="center" data-title="Time Out:">
                                         <div class="col-md-10">
                                          <div class="input-group bootstrap-timepicker">
                                                {{ Form::text('time_out'.$att_index.'','', array('class' => 'form-control timepicker-default','id' => 'time_out'.$att_index.'','required')) }}
                                                <span class="input-group-btn">
                                                {{Form::button('<i class="fa fa-clock-o"></i>', array('class' => 'btn btn-default'))}}
                                                </span>
                                          </div>
                                         </div>
                                      </td>
                                      <td  style="padding-left:5px" class="numeric" data-title="Time Diff.:">
                                      {{ Form::text('time_differnce'.$att_index.'','', array('class' => 'form-control','id' => 'time_differnce'.$att_index.'','style'=>'text-align:center; font-weight: bold;','readonly')) }}
                                      </td>
                                      <td  style="padding-left:5px" class="numeric" align="center" data-title="Job Comp.:">
                                      <input type="checkbox" id="JobCheck<?php echo $att_index;?>" name="JobCheck<?php echo $att_index;?>" <?php if($complete_flag == '1') echo 'checked'; ?> class='form-control'>
                                      &nbsp;&nbsp; <i id="show_hide_div<?php echo $att_index;?>" name="show_hide_div<?php echo $att_index;?>" class="fa fa-plus"></i></td>
                                    </tr>
                                    <tr>
                                      <td colspan="6">
                                        <div id="hide_div<?php echo $att_index; ?>"></div>
                                      </td> 
                                    </tr>
                                    <script type="text/javascript">
                                    $(document).ready(function(){
                                        $('.timepicker-default').timepicker();

                                        $('#time_in'+'<?php echo $att_index;?>').change(function() {
                                          var splits0 = $(this).attr("id").split('time_in'); 
                                          if (splits0 != ','){
                                              var start = $( "#time_in"+ splits0[1]).val();
                                              if ($("#time_out"+splits0[1]).val() != "") {
                                              var end = $( "#time_out"+splits0[1]).val();
                                              document.getElementById("time_differnce"+splits0[1]).value = diff(start,end);
                                              }
                                          }
                                          else{
                                              var start = $("#time_in").val();
                                              if ($("#time_out").val() != "") {
                                              var end = $( "#time_out" ).val();
                                              document.getElementById("time_differnce").value = diff(start,end);
                                              }
                                          }
                                        });  
                                          
                                          $('#time_out'+'<?php echo $att_index;?>').change(function() {  
                                          var splits0 = $(this).attr("id").split('time_out'); 
                                          if (splits0 != ','){
                                            var end = $( "#time_out"+splits0[1]).val();
                                            if ($("#time_in"+splits0[1]).val() != "") {
                                              var start = $( "#time_in"+splits0[1]).val();
                                              document.getElementById("time_differnce"+splits0[1]).value = diff(start,end);
                                            }
                                          }else{
                                            var end = $( "#time_out" ).val();
                                            if ($("#time_in").val() != "") {
                                              var start = $( "#time_in" ).val();
                                              document.getElementById("time_differnce").value = diff(start,end);
                                            }
                                          }
                                        });  

                                        $("#hide_div"+'<?php echo $att_index; ?>').hide();
                                        $("#show_hide_div"+'<?php echo $att_index; ?>').click(function(){
                                            var splits1 = $(this).attr("id").split('show_hide_div');
                                            $("#hide_div"+splits1[1]).toggle("slow");
                                         if ($(this).attr("class") == "fa fa-plus")
                                             $(this).removeClass('fa fa-plus').addClass('fa fa-minus');
                                         else 
                                             $(this).removeClass('fa fa-minus').addClass('fa fa-plus');
                                        });
                                       if ($('#JobNumber'+'<?php echo $att_index; ?>').val()) {
                                          var type_task_id = "<?php echo $gpg_task_type; ?>";
                                          if (type_task_id == '') 
                                              type_task_id = 0;
                                          var proj_actv_id = "<?php echo $gpg_activity_id; ?>";
                                          if (proj_actv_id == '')
                                              proj_actv_id = 0;
                                          
                                          $.ajax({
                                                  url: "{{URL('ajax/setProjectTaskArrays')}}",
                                                   data: {
                                                      'gpg_job_num': $('#JobNumber'+'<?php echo $att_index; ?>').val(),
                                                      'emp_id' :  "<?PHP echo Input::get('emp_id'); ?>",
                                                      'type_task_id' : type_task_id,
                                                      'proj_actv_id' : proj_actv_id 
                                                  },
                                                  success: function (data) {
                                                      $("#type_task_div"+'<?php echo $att_index; ?>').html(data.task_type_options);
                                                      $("#project_activity_div"+'<?php echo $att_index; ?>').html(data.project_activity_options);
                                                  },
                                                });
                                           
                                           switch($('select[name=time_type<?php echo $att_index; ?>]').val()){
                                               case '1':
                                               case '2':
                                               /*    case 1-2     */
                                               $("div#hide_div"+'<?php echo $att_index; ?>').html("<table class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Daily Log:</b></td><td><textarea id='daily_log_text"+'<?php echo $att_id; ?>'+"' class='form-control' rows='2' cols='18' name='daily_log_text"+'<?php echo $att_id; ?>'+"' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Recommendations:</b></td><td><textarea class='form-control' rows='2' cols='18' name='recomend_text"+'<?php echo $att_id; ?>'+"' id='recomend_text"+'<?php echo $att_id; ?>'+"' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Attachments:</b></td><td><input class='form-control' type='file' id='add_file<?php echo $att_index;?>' name='add_file<?php echo $att_index;?>[]' style='margin:2px;'/><a class='btn' id='add_another_file<?php echo $att_index;?>'>Add Another</a></td></tr></table>");
                                               $('#daily_log_text'+'<?php echo $att_id; ?>').val("<?php echo $workdone; ?>");
                                               $('#recomend_text'+'<?php echo $att_id; ?>').val("<?php echo $recommendations ; ?>");
                                               break; 
                                               case '3':
                                               /*    case - 3     */
                                               $("div#hide_div"+'<?php echo $att_index; ?>').html("<table  class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Miles Driven:</b></td><td><input type='text' class='form-control' name='miles_driven"+'<?php echo $att_id; ?>'+"' id='miles_driven"+'<?php echo $att_id; ?>'+"'></td></tr></table>");
                                               $('#miles_driven'+'<?php echo $att_id; ?>').val('<?php echo $mileage ; ?>');
                                               break;
                                               case '5':
                                               /*    case - 5     */
                                               $("div#hide_div"+'<?php echo $att_index; ?>').html("<table  class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Daily Log:</b></td><td><textarea class='form-control' rows='2' cols='18' name='daily_log_text"+'<?php echo $att_id; ?>'+"' id='daily_log_text"+'<?php echo $att_id; ?>'+"'></textarea></td></tr></table>");
                                               $('#daily_log_text'+'<?php echo $att_id; ?>').val("<?php echo $workdone ; ?>");
                                               break;

                                               default:
                                               $("div#hide_div"+'<?php echo $att_index; ?>').html("");
                                              }
                                             
                                               var arr = '<?php print_r($file_attachments); ?>'; 
                                               var obj = JSON.parse(arr);
                                               for(var i=0; i < Object.keys(obj).length; i++){
                                                  //var link = '{{ HTML::linkAsset("gpg/public/img/'+obj.files[i].flink+'", "Download file")}}';
                                                  var link = '<a href="{{ URL::asset("gpg/public/img/'+obj.files[i].flink+'", "Download file")}}" "download"> Download </a>'; 
                                                  $("#add_file"+'<?php echo $att_index;?>').before(link);
                                               }

                                              var t_in = "<?php echo $time_in ; ?>";
                                              t_in = t_in.split(':');
                                              var t_ot = "<?php echo $time_out ; ?>";
                                              t_ot = t_ot.split(':');
                                              var t_df = "<?php echo $time_diff_dec ; ?>";
                                              
                                              $("#time_in"+'<?php echo $att_index; ?>').val(t_in[0]+":"+t_in[1]+" AM");
                                              $("#time_out"+'<?php echo $att_index; ?>').val(t_ot[0]+":"+t_ot[1]+" AM");
                                              
                                              $("#time_differnce"+'<?php echo $att_index; ?>').val(diff(t_in[0]+":"+t_in[1]+" AM",t_ot[0]+":"+t_ot[1]+" AM"));
                                              //$("#time_differnce"+'<?php echo $att_index; ?>').val(parseFloat(Math.round(t_df * 100) / 100).toFixed(2)+" hours");

                                              $('#JobNumber'+'<?php echo $att_index; ?>').focus(function() {  
                                              $(this).autocomplete({
                                                source: function (request, response) {
                                                  $("span.ui-helper-hidden-accessible").before("<br/>");  
                                                    $.ajax({
                                                        url: "{{URL('ajax/getJobNumberAutocomplete')}}",
                                                        data: {
                                                            JobNumber: this.term
                                                        },
                                                        success: function (data) {
                                                            // data must be an array containing 0 or more items
                                                            //console.log("[SUCCESS] " + data.length + " item(s)");
                                                            //response(data);
                                                            response( $.map( data, function( item ) {
                                                                return {
                                                                    label: item.name,
                                                                    value: item.id
                                                                  };
                                                              }));
                                                          },
                                                      });
                                                  },
                                              });
                                            });
                                              
                                            $('#JobNumber'+'<?php echo $att_index; ?>').keypress( function(event) {  
                                              if (event.which == 13) {
                                                  event.preventDefault();
                                                  var job_num = document.getElementById("JobNumber"+'<?php echo $att_index; ?>').value;
                                                  if (job_num.length>6) {
                                                    $("div#daily_log").css("margin-top","3.5cm");
                                                    var empId = "<?PHP echo Input::get('emp_id'); ?>";
                                                    if (empId == "")
                                                          empId =0;
                                                      $.ajax({
                                                        url: "{{URL('ajax/setProjectTaskArrays')}}",
                                                         data: {
                                                            'gpg_job_num': job_num,
                                                            'emp_id' : empId 
                                                        },
                                                        success: function (data) {
                                                            $("#type_task_div"+'<?php echo $att_index; ?>').html(data.task_type_options);
                                                            $("#project_activity_div"+'<?php echo $att_index; ?>').html(data.project_activity_options);
                                                        },
                                                      });
                                                  }
                                              }
                                            });

                                            //$("div#hide_div").html("<table class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Daily Log:</b></td><td><textarea class='form-control' rows='2' cols='18' name='daily_log_text' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Recommendations:</b></td><td><textarea class='form-control' rows='2' cols='18' name='recomend_text' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Attachments:</b></td><td><input class='form-control' type='file' id='add_file' name='add_file[]' style='margin:2px;'/><a class='btn' id='add_another_file'>Add Another</a></td></tr></table>");
                                            $("select#time_type").on('change',function() { 
                                              var time_type_str = $(this).attr("name");
                                              if (time_type_str.indexOf('time_type') > -1)
                                              {   
                                                  var number_found = time_type_str.match(/[0-9]+/g);
                                                  var attach = '';
                                                      
                                                  if (number_found)   
                                                     attach = "_"+number_found;
                                                   else
                                                     number_found ='';
                                                  var val = $(this).children(":selected").attr("id");
                                                  switch(val){
                                                     case '1':
                                                     case '2':
                                                     /*    case 1-2     */
                                                     $("div#hide_div"+attach).html("<table class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Daily Log:</b></td><td><textarea class='form-control' rows='2' cols='18' name='daily_log_text"+number_found+"' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Recommendations:</b></td><td><textarea class='form-control' rows='2' cols='18' name='recomend_text"+number_found+"' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Attachments:</b></td><td><input class='form-control' type='file' id='add_file"+attach+"' name='add_file"+attach+"[]' style='margin:2px;'/><a class='btn' id='add_another_file"+attach+"'>Add Another</a></td></tr></table>");
                                                     break; 
                                                     case '3':
                                                     /*    case - 3     */
                                                     $("div#hide_div"+attach).html("<table  class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Miles Driven:</b></td><td><input type='text' class='form-control' name='miles_driven"+number_found+"'></td></tr></table>");
                                                     break;
                                                     case '5':
                                                     /*    case - 5     */
                                                     $("div#hide_div"+attach).html("<table  class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Daily Log:</b></td><td><textarea class='form-control' rows='2' cols='18' name='daily_log_text"+number_found+"'></textarea></td></tr></table>");
                                                     break;

                                                     default:
                                                     $("div#hide_div"+attach).html("");
                                                    }
                                              }
                                            });
                                            $('#add_another_file'+'<?php echo $att_index; ?>').click( function() {
                                              $("#add_file"+'<?php echo $att_index; ?>').after("<input type='file' class='form-control' id='add_file' name='add_file[]' style='margin-top:4px;'>");
                                            });
                                       }
                                         });
                                    </script>
                                  @endforeach
                                  </tbody>
                              </table>
                              </section>
                              </section>
                              <a class="btn btn-lg btn-success"  id='add_another_row'>Add New Line </a>
                              <a class="btn btn-lg btn-danger" id='remove_row'>Remove Line</a>
                              <input type="hidden" id="count_records" name="count_records" value="">
                              <input class="btn btn-lg btn-info" type="submit" id='save_time_sheet' value="Update Time Sheet" />
                              {{ Form::close() }}
                          </div>
               </div>
              </div>
              </section>
              </div>
              </div>
              
              <!-- page end-->
      <script>
      $(document).ready(function(){

           

            $("#nav-accordion ul li").click(function ( e ) {
              $("#nav-accordion ul li a.active").removeClass("active"); //Remove any "active" class  
              $("a", this).addClass("active"); //Add "active" class to selected tab  
              // $(activeTab).show(); //Fade in the active content  
            });



            
          
var count=pre_count;
     $('input#count_records').val(count);
            $('#add_another_row').click( function() {
              count = parseInt(count) + parseInt("1");
               //alert(count);
             $('input#count_records').val(count);

              var str = "<tr><td style='padding-left:5px'><select class='form-control m-bot15' id='time_type' name='time_type_"+count+"'>"+document.getElementById('time_type').innerHTML+"</select></td>";
              str += "<td style='padding-left:5px'><input type='text' class='form-control' id='JobNumber_"+count+"' name='JobNumber_"+count+"' required><br/><div id='type_task_div_"+count+"'></div><div id='project_activity_div_"+count+"'></div></td>";
              str += "<td style='padding-left:5px' class='numeric' align='center'><div class='col-md-10'><div class='input-group bootstrap-timepicker'><input type='text' class='form-control timepicker-default' name='time_in_"+count+"' id='time_in_"+count+"' required><span class='input-group-btn'><button class='btn btn-default' type='button'><i class='fa fa-clock-o'></i></button></span></div></div></td>";
              str += "<td style='padding-left:5px' class='numeric' align='center'><div class='col-md-10'><div class='input-group bootstrap-timepicker'><input type='text' class='form-control timepicker-default' name='time_out_"+count+"' id='time_out_"+count+"' required><span class='input-group-btn'><button class='btn btn-default' type='button'><i class='fa fa-clock-o'></i></button></span></div></div></td>";
              str += "<td style='padding-left:5px' class='numeric'><input style='text-align:center; font-weight: bold;' class='form-control' type='text' id='time_differnce_"+count+"' name='time_differnce_"+count+"' readonly></td>";
              str += "<td style='padding-left:5px' class='numeric' align='center'><input type='checkbox' class='form-control' id='JobCheck_"+count+"' name='JobCheck_"+count+"'>&nbsp;&nbsp; <i id='show_hide_div_"+count+"' name='show_hide_div_"+count+"' class='fa fa-plus'></i></td>";
              str += "</tr>";
              str += "<tr><td colspan='6'><div id='hide_div_"+count+"'></div></td></tr>";
              
              $('#mytable > tbody:last').append(str);

                              $('#JobNumber_'+count).focus( function() {  
                $(this).autocomplete({
                      source: function (request, response) {
                        $("span.ui-helper-hidden-accessible").before("<br/>");
                          $.ajax({
                              url: "{{URL('ajax/getJobNumberAutocomplete')}}",
                              data: {
                                  JobNumber: this.term
                              },
                              success: function (data) {
                                  // data must be an array containing 0 or more items
                                  //console.log("[SUCCESS] " + data.length + " item(s)");
                                  //response(data);
                                  response( $.map( data, function( item ) {
                                      return {
                                          label: item.name,
                                          value: item.id
                                      };
                                  }));
                              },
                          });
                      },
                  });
                });
                $('#JobNumber_'+count).keypress(function(event) {  
                if (event.which == 13) {
                    event.preventDefault();
                    var job_num = document.getElementById("JobNumber_"+count).value;
                    if (job_num.length>6) {
                      $("div#daily_log_"+count).css("margin-top","3.5cm");
                       var empId = "<?PHP echo Input::get('emp_id'); ?>";
                      if (empId == "")
                            empId =0;
                        $.ajax({
                          url: "{{URL('ajax/setProjectTaskArrays')}}",
                           data: {
                              'count':count,
                              'gpg_job_num': job_num,
                              'emp_id' : empId
                          },
                          success: function (data) {
                              $("#type_task_div_"+count).html(data.task_type_options);
                              $("#project_activity_div_"+count).html(data.project_activity_options);
                          },
                        });
                    }
                }
              });
              //document.getElementById('hide_n_show').style.display = 'none';
              $("select#time_type").on('change',function() { 
              var time_type_str = $(this).attr("name");
              if (time_type_str.indexOf('time_type') > -1)
              {   
                  var number_found = time_type_str.match(/[0-9]+/g);
                  var attach = '';
                      
                  if (number_found)   
                     attach = "_"+number_found;
                   else
                     number_found ='';
                  var val = $(this).children(":selected").attr("id");
                  switch(val){
                     case '1':
                     case '2':
                     /*    case 1-2     */
                     $("div#hide_div"+attach).html("<table class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Daily Log:</b></td><td><textarea class='form-control' rows='2' cols='18' name='daily_log_text"+number_found+"' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Recommendations:</b></td><td><textarea class='form-control' rows='2' cols='18' name='recomend_text"+number_found+"' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Attachments:</b></td><td><input class='form-control' type='file' id='add_file"+attach+"' name='add_file"+attach+"[]' style='margin:2px;'/><a class='btn' id='add_another_file"+attach+"'>Add Another</a></td></tr></table>");
                     break; 
                     case '3':
                     /*    case - 3     */
                     $("div#hide_div"+attach).html("<table  class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Miles Driven:</b></td><td><input type='text' class='form-control' name='miles_driven"+number_found+"'></td></tr></table>");
                     break;
                     case '5':
                     /*    case - 5     */
                     $("div#hide_div"+attach).html("<table  class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Daily Log:</b></td><td><textarea class='form-control' rows='2' cols='18' name='daily_log_text"+number_found+"'></textarea></td></tr></table>");
                     break;

                     default:
                     $("div#hide_div"+attach).html("");
                    }
              }
            });
               $("#hide_div_"+count).hide();
               $("#show_hide_div_"+count).click(function(){
                var splits = $(this).attr("id").split('show_hide_div_');
                  $("#hide_div_"+splits[1]).toggle("slow");
               if ($(this).attr("class") == "fa fa-plus")
                   $(this).removeClass('fa fa-plus').addClass('fa fa-minus');
               else 
                   $(this).removeClass('fa fa-minus').addClass('fa fa-plus');
              });

              $("div#hide_div_"+count).html("<table class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Daily Log:</b></td><td><textarea class='form-control' rows='2' cols='18' name='daily_log_text"+count+"' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Recommendations:</b></td><td><textarea class='form-control' rows='2' cols='18' name='recomend_text"+count+"' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Attachments:</b></td><td><input class='form-control' type='file' id='add_file_"+count+"' name='add_file_"+count+"[]' style='margin:2px;'/><a class='btn' id='add_another_file_"+count+"'>Add Another</a></td></tr></table>");
              $('#add_another_file_'+count).click( function() {
                  var other_file_str = $(this).attr("id");
                  var number_found = other_file_str.match(/[0-9]+/g);
              $("#add_file_"+number_found).after("<input type='file' class='form-control' id='add_file_"+number_found+"' name='add_file_"+number_found+"[]' style='margin-top:4px;'>");
              });     
              //$("#main_tr").after("<tr>"+document.getElementById('main_tr').innerHTML+"<tr/>");
              
              $('#time_in_'+count).change( function() { 
               var splits = $(this).attr("id").split('time_in_'); 
              var interval = 0;
              var start = $( "#time_in_"+splits[1] ).val();
              if ($("#time_out_"+splits[1]).val() != "") {
                var end = $( "#time_out_"+splits[1] ).val();
                document.getElementById("time_differnce_"+splits[1]).value = diff(start,end);
              }});  
              
              $('#time_out_'+count).change( function() {  
                var splits = $(this).attr("id").split('time_out_'); 
              var interval = 0;
              var end = $( "#time_out_"+splits[1] ).val();
              if ($("#time_in_"+splits[1]).val() != "") {
                var start = $( "#time_in_"+splits[1] ).val();
                document.getElementById("time_differnce_"+splits[1]).value = diff(start,end);
              }});  

               $('.timepicker-default').timepicker();
              });

            $('#remove_row').click( function() {
                if (count>0){
                    $('#mytable > tbody > tr:last').remove();
                    $('#mytable > tbody > tr:last').remove();
                    count=count-1;
                    $('input#count_records').val(count);
                }
            });  
      });  

      function diff(t1,t2) {
            var time = t1;
            var hrs = Number(time.match(/^(\d+)/)[1]);
            var mnts = Number(time.match(/:(\d+)/)[1]);
            var format = time.match(/\s(.*)$/)[1];
          if (format == "PM" && hrs < 12) hrs = hrs + 12;
          if (format == "AM" && hrs == 12) hrs = hrs - 12;
            var hours =hrs.toString();
            var minutes = mnts.toString();
          if (hrs < 10) hours = "0" + hours;
          if (mnts < 10) minutes = "0" + minutes;
            var date1 = new Date();
            date1.setHours(hours );
            date1.setMinutes(minutes);
            var time = t2;
            var hrs = Number(time.match(/^(\d+)/)[1]);
            var mnts = Number(time.match(/:(\d+)/)[1]);
            var format = time.match(/\s(.*)$/)[1];
          if (format == "PM" && hrs < 12) hrs = hrs + 12;
          if (format == "AM" && hrs == 12) hrs = hrs - 12;
            var hours = hrs.toString();
            var minutes = mnts.toString();
          if (hrs < 10) hours = "0" + hours;
          if (mnts < 10) minutes = "0" + minutes;
            var date2 = new Date();
            date2.setHours(hours );
            date2.setMinutes(minutes);
            var diff = date2.getTime() - date1.getTime();
            var hours = Math.floor(diff / (1000 * 60 * 60));
            diff -= hours * (1000 * 60 * 60);
            var mins = Math.floor(diff / (1000 * 60));
            diff -= mins * (1000 * 60);
          return ( hours + " hours : " + mins + " minutes " );
      }

    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop