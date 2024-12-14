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
                  Edit Employee Details
            
              </header>
              <div class="panel-body">
              <div class="adv-table">
              <?php $name = ''; 
                    $date = ''; 
                    $emp_id = '';
              ?>
              @foreach($timesheet as $key=>$value) 
                 <?php  
                  if($key == 'name')
                     $name = $value;
                  else if($key == 'id')      
                     $emp_id = $value;                     
                 ?>
              @endforeach
                <?php $date= Input::get('select_date'); ?>
                <div class="container-fluid">
                  <b>Time sheet for:{{$name}}</b><br/>  
                  <b>Date:{{$date}}</b>  
                </div>
                 <div class="panel-body">
                              <section id="unseen">
                                {{ Form::open(array('before' => 'csrf' ,'url'=>route('timesheet.store'), 'files'=>true, 'method' => 'post')) }}
                                {{ Form::hidden('date', $date) }}
                                {{ Form::hidden('emp_id', $emp_id) }}
                                 <section id="no-more-tables" >
                                <table class="table table-bordered table-striped table-condensed cf" id="mytable" style="text-align: center;">
                                  <thead>
                                  <tr>
                                      <th>Time Type</th>
                                      <th width="250px">Job Number</th>
                                      <th class="numeric">Time In </th>
                                      <th class="numeric">Time Out </th>
                                      <th class="numeric">Hour(s) - Minute(s)</th>
                                      <th class="numeric">Job Completed</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                    <tr id="main_tr">
                                      <td style="padding-left:5px" data-title="Time Type:"><select id="time_type" name="time_type" class='form-control m-bot15'>
                                        @foreach($time_type as $id=>$value)
                                          <option value="{{$id}}" id="{{$id}}">{{$value}}</option>
                                        @endforeach
                                      </select>
                                      </td>
                                      <td style="padding-left:5px" data-title="Job Number:">
                                      {{ Form::text('JobNumber','', array('class' => 'form-control','id' => 'JobNumber','required')) }}
                                      <br/>
                                      <div id="type_task_div"></div>
                                      <div id="project_activity_div"></div>
                                      </td>
                                      <td  style="padding-left:5px" class="numeric" align="center" data-title="Time In:">
                                         <div class="col-md-10">
                                          <div class="input-group bootstrap-timepicker">
                                              {{ Form::text('time_in','', array('class' => 'form-control timepicker-default','id' => 'time_in','required')) }}
                                                <span class="input-group-btn">
                                                {{Form::button('<i class="fa fa-clock-o"></i>', array('class' => 'btn btn-default'))}}
                                                </span>
                                          </div>
                                         </div>
                                      </td>
                                      <td  style="padding-left:5px" class="numeric" align="center" data-title="Time Out:">
                                         <div class="col-md-10">
                                          <div class="input-group bootstrap-timepicker">
                                              {{ Form::text('time_out','', array('class' => 'form-control timepicker-default','id' => 'time_out','required')) }}
                                                <span class="input-group-btn">
                                                {{Form::button('<i class="fa fa-clock-o"></i>', array('class' => 'btn btn-default'))}}
                                                </span>
                                          </div>
                                         </div>
                                      </td>
                                      <td  style="padding-left:5px" class="numeric" data-title="Time Diff.:">
                                      {{ Form::text('time_differnce','', array('class' => 'form-control','style'=>'text-align:center; font-weight: bold;','id' => 'time_differnce','readonly')) }}
                                      </td>
                                      <td  style="padding-left:5px" class="numeric" align="center">
                                      {{ Form::checkbox('JobCheck', '', null, ['class' => 'form-control', 'id'=>'JobCheck']) }}
                                      &nbsp;&nbsp; <i id="show_hide_div" name="show_hide_div" class="fa fa-plus"></i></td>
                                    </tr>
                                    <tr>
                                      <td colspan="6">
                                        <div id="hide_div"></div>
                                      </td> 
                                    </tr>
                                  </tbody>
                              </table>
                              </section>
                              </section>
                              <div class="btn-group">
                                  <a class="btn btn-success"  id='add_another_row'>Add New Line </a>
                                  <a class="btn btn-danger" id='remove_row'>Remove Line</a>
                              </div>
                              {{ Form::hidden('count_records', '',array('id' =>'count_records')) }}
                              {{Form::submit('Save Time Sheet', array('class' => 'btn btn-info','id'=>'save_time_sheet'))}}
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

            $('.timepicker-default').timepicker();
             
             $("#hide_div").hide();
             $("#show_hide_div").click(function(){
                $("#hide_div").toggle("slow");
             if ($(this).attr("class") == "fa fa-plus")
                 $(this).removeClass('fa fa-plus').addClass('fa fa-minus');
             else 
                 $(this).removeClass('fa fa-minus').addClass('fa fa-plus');
            });

            $("#nav-accordion ul li").click(function ( e ) {
              $("#nav-accordion ul li a.active").removeClass("active"); //Remove any "active" class  
              $("a", this).addClass("active"); //Add "active" class to selected tab  
              // $(activeTab).show(); //Fade in the active content  
            });

          $('#time_in').change(function() {  
            var interval = 0;
            var start = $( "#time_in" ).val();
            if ($("#time_out").val() != "") {
              var end = $( "#time_out" ).val();
              document.getElementById("time_differnce").value = diff(start,end);
            }});  
            
            $('#time_out').change(function() {  
            var interval = 0;
            var end = $( "#time_out" ).val();
            if ($("#time_in").val() != "") {
              var start = $( "#time_in" ).val();
              document.getElementById("time_differnce").value = diff(start,end);
            }});  

            
            $('#JobNumber').focus(function() {  
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
              
            $('#JobNumber').keypress( function(event) {  
              if (event.which == 13) {
                  event.preventDefault();
                  var job_num = document.getElementById("JobNumber").value;
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
                            $("#type_task_div").html(data.task_type_options);
                            $("#project_activity_div").html(data.project_activity_options);
                        },
                      });
                  }
              }
            });

            $("div#hide_div").html("<table class='table table-bordered table-striped table-condensed cf'><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Daily Log:</b></td><td><textarea class='form-control' rows='2' cols='18' name='daily_log_text' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Recommendations:</b></td><td><textarea class='form-control' rows='2' cols='18' name='recomend_text' style='margin:2px;'></textarea></td></tr><tr><td style='text-align:center; vertical-align:middle; background:#F0F0F0;'><b>Attachments:</b></td><td><input class='form-control' type='file' id='add_file' name='add_file[]' style='margin:2px;'/><a class='btn' id='add_another_file'>Add Another</a></td></tr></table>");
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
            
            $('#add_another_file').click( function() {
              $("#add_file").after("<input type='file' class='form-control' id='add_file' name='add_file[]' style='margin-top:4px;'>");
            });
var count=0;
      
            $('#add_another_row').click( function() {
              count = count +1;
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

              $('#JobNumber_'+count).focus( function(event) {  
                $(this).autocomplete({
                      source: function (request, response) {
                        $("span.ui-helper-hidden-accessible").before("<br/>");
                          $.ajax({
                              url: "{{URL('ajax/getJobNumberAutocomplete')}}",
                              data: {
                                  JobNumber: this.term
                              },
                              success: function (data) {
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
               /* $('#JobNumber_'+count).keypress(function(event) {  
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
              });*/
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