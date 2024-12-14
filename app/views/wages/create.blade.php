@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<style>
  #mytable {
     overflow-x:auto;
  }
  #mytable2 {
     overflow: auto;
  }
@media screen and (max-width: 900px) {

  #st_date{
    width: 100%;  
  }
  #en_date{
    width: 100%;  
  }        
}
</style>
              <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                ADD NEW WAGE PLAN 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b>Add Wage Plan by:</b> <i>( Job Number / Contract Number )</i> 
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
             <div class="panel-body">
             <div class="adv-table">
              <div class="form-group">
                {{ Form::open(array('before' => 'csrf' ,'url'=>route('wages.store'), 'id'=>'frmid1', 'files'=>true, 'method' => 'post')) }}
                  <section id="no-more-tables"  style="padding:10px;">
                  {{Form::label('JobNumber', 'Select Job Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                  {{ Form::text('JobNumber','', array('class' => 'form-control', 'style'=>'width:220px; margin-bottom:5px;' ,'id' => 'JobNumber','required')) }}
                          <table class="table table-bordered table-striped table-condensed cf" style="cellspacing=0px; cellpadding=0px;"   id="mytable" align="center">
                                  <thead>
                                    <tr>
                                      <th rowspan="2"><a data-toggle="modal" href="#myModal" style="color:red; font-size:10px; margin:0px; padding:0px;">Add Employee Type</a><br/>Employee Type</th>
                                      <th rowspan="2">County</th>
                                      <th colspan="4" style="text-align:center;">Prevailing Wage &nbsp;<i id="iconChange" class="fa fa fa-bars fa-fw"></i></th>
                                      <th rowspan="2">Start Date</th>
                                      <th rowspan="2">End Date</th>
                                      <th rowspan="2"><a data-toggle="modal" href="#myModal2" style="color:red; font-size:10px;"> Add Task Type</a><br/>Task Type</th>
                                      <th rowspan="2">Status</th>
                                      <th rowspan="2">PW Wage Rate</th>
                                    </tr>
                                    <tr>
                                      @if(!empty($pw_wages_rates_type))
                                          @foreach($pw_wages_rates_type as $key=>$val) 
                                            @if($key == 1)
                                              <th align="center">{{ucwords($val)}}</th>
                                            @endif
                                          @endforeach      
                                      @endif
                                          <th align="center">Regular</th>
                                          <th align="center">Over Time</th>
                                          <th align="center">Double Time</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                        <td data-title="Employee Types:" id="emp_type_css">
                                          <select name="etype_0" id="etype" class="form-control l-bot6" required>
                                            <option value="">Select Type</option>
                                            @foreach ($emp_types as $key => $value)
                                              <option value="{{$value->type_id}}">{{$value->type}}</option>
                                            @endforeach
                                          </select>
                                        </td> 
                                        <td data-title="County:" id="county_css">
                                          <select name="countyName_0" id="countyName" class="form-control m-bot12" >
                                            <option value="">ALL</option>
                                            @foreach ($county_types as $key => $value)
                                              <option value="{{$value->id}}">{{$value->county_name}}</option>
                                            @endforeach
                                          </select>
                                        </td>
                                        @if(!empty($pw_wages_rates_type))
                                          @foreach($pw_wages_rates_type as $key=>$val)
                                            @if($key == 1)
                                              <td align="center" data-title="Rate Breakup_<?php echo $key;?>:">
                                              <input class="form-control" id="rateBreakup" name="rateBreakup_0_<?php echo $key;?>" type="text" required/>
                                              <i style="margin-top:5px;" id="show_hide_div_<?php echo $key; ?>" name="show_hide_div_<?php echo $key; ?>" class='fa fa-plus'></i>
                                              </td>
                                            @endif
                                          @endforeach
                                        @endif
                                        <td align="center" data-title="Regular:">
                                         {{ Form::text('pwpay_0','', array('class' => 'form-control','id' => 'pwpay_0','readonly')) }}
                                        </td>
                                        <td align="center" data-title="Over Time:">
                                        {{Form::text('pwovertime_0','', array('class'=>'form-control','id' => 'pwovertime'))}}
                                        </td>
                                        <td align="center" data-title="Double Time:">
                                        {{Form::text('pwdouble_0','', array('class'=>'form-control','id' => 'pwdouble'))}}
                                        </td>
                                        <td data-title="Start Date:" id="st_date">
                                        {{Form::text('SDate_0','', array('class'=>'form-control form-control-inline input-medium default-date-picker','id' => 'start_date','required'))}}
                                        </td> 
                                        <td data-title="End Date:" id="en_date">
                                        {{Form::text('EDate_0','', array('class'=>'form-control form-control-inline input-medium default-date-picker','id' => 'end_date','required'))}}
                                        </td>
                                        <td data-title="Task Type:" id="taskType_td">
                                          <select name="taskType_0" id="taskType" class="form-control m-bot12" >
                                            <option value="">ALL</option>
                                            @foreach($task_types as $key => $value)
                                              <option value="{{$value->id}}">{{$value->task_type}}</option>
                                            @endforeach
                                          </select>
                                        </td>
                                        <td data-title="Status:" id="status_css">
                                          <select name="status_0" id="status"  class="form-control m-bot12">
                                            <option value="A"> Active </option>
                                            <option value="B"> Blocked </option>
                                          </select>
                                        </td>
                                        <td data-title="Wage Type:" id="wage_type_css">
                                          <select name="wageType_0" id="wageType" class="form-control m-bot12">
                                            <option value="1"> <$500k</option>
                                            <option value="2"> >$500k</option>
                                          </select>
                                        </td>
                                    </tr>
                                    <tr>
                                      <td colspan="11">
                                        <div id="toggle_for_table1_1">
                                              <table class="table table-bordered table-striped table-condensed cf" style="cellspacing=0px; cellpadding=0px;" align="center">
                                              <tbody>
                                                <tr>
                                                  @foreach($pw_wages_rates_type as $key2=>$val2) 
                                                   @if ($key2 != 1)
                                                  <td data-title="Rate Breakup_<?php echo $key2;?>:">{{$val2}}<br/>
                                                  <input class="form-control" id="rateBreakup" name="rateBreakup_0_<?php echo $key2;?>" type="text"/></td>
                                                   @endif
                                                  @endforeach 
                                                </tr>
                                              </tbody>
                                              </table>
                                            </div>
                                            
                                      </td>
                                    </tr>
                              </tbody>
                              </table>
                        <br/>
                        {{Form::hidden('count_job_records','', array('id' => 'count_job_records'))}}
                        <div class="btn-group" style="float: left;">
                        {{Form::button('Add Line', array('class' => 'btn btn-success', 'id'=>'add_another_row'))}}
                        {{Form::button('Remove Line', array('class' => 'btn btn-danger', 'id'=>'remove_row'))}}
                        </div>
                        <div class="btn-group" style="float:right;">
                        {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                        </div>
                    </section>
              {{ Form::close() }}
              <br/><hr/><br/>
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('wages.store'), 'id'=>'frmid2', 'files'=>true, 'method' => 'post')) }}
              <section id="no-more-tables" style="padding:10px;">
                  {{Form::label('ContractNumber', 'Select Contract Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                  <br/>
                  <div  style="display:inline;  float:left;">
                  {{ Form::text('ContractNumber','', array('class' => 'form-control', 'id' => 'ContractNumber',  'style'=>'width:220px;  float:left; margin-bottom:5px; display:inline;','required')) }}
                  <a id='go_contrct_number' style="display:inline; float:left; margin-top:7px; margin-left:2px; cursor: pointer;">GO <i class="fa fa-arrow-right fa-lg"></i></a></div>
                  <table class="table table-bordered table-striped table-condensed cf" style="margin:0px; padding:0px; border-collapse:collapse;" id="mytable2" align="center">
                                  <thead>
                                    <tr>
                                      <th rowspan="2" colspan="2">Job Type</th>
                                      <th rowspan="2">Regarding</th>
                                      <th rowspan="2"><a data-toggle="modal" href="#myModal" style="color:red; font-size:10px;">Add Employee Type</a><br/>Employee Type</th>
                                      <th rowspan="2">County</th>
                                      <th rowspan="2">Hours</th>
                                      <th colspan="4" style="text-align:center;">Prevailing Wage &nbsp;<i class="fa fa fa-bars fa-fw"></i></th>
                                      <th rowspan="2">Start Date</th>
                                      <th rowspan="2">End Date</th>
                                      <th rowspan="2"><a data-toggle="modal" href="#myModal2" style="color:red; font-size:10px;"> Add Task Type</a><br/>Task Type</th>
                                      <th rowspan="2">Status</th>
                                      <th rowspan="2">PW Wage Rate</th>
                                    </tr>
                                    <tr>
                                       @if(!empty($pw_wages_rates_type))
                                          @foreach($pw_wages_rates_type as $key=>$val)
                                            @if($key == 1)
                                              <th align="center">{{ucwords($val)}}</th>
                                            @endif
                                          @endforeach
                                       @endif 
                                          <th align="center">Regular</th>
                                          <th align="center">Over Time</th>
                                          <th align="center">Double Time</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr id="create_row_for_ContractNumber"></tr>
                                    <tr id="create_toggle_row_for_ContractNumber"></tr>
                              </tbody>
                              </table>
                            </section>    
              <hr/>
              <section id="no-more-tables" style="padding:10px;">
                  {{Form::label('JobNumber', 'By Job Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                  <table class="table table-bordered table-striped table-condensed cf" style="margin:0px; padding:0px; border-collapse:collapse;" id="mytable3" align="center">
                                  <thead>
                                    <tr>
                                      <th rowspan="2" colspan="2">Job Number</th>
                                      <th rowspan="2">Regarding</th>
                                      <th rowspan="2"><a data-toggle="modal" href="#myModal" style="color:red; font-size:10px;">Add Employee Type</a><br/>Employee Type</th>
                                      <th rowspan="2">County</th>
                                      <th rowspan="2">Hours</th>
                                      <th colspan="4" style="text-align:center;">Prevailing Wage &nbsp;<i class="fa fa fa-bars fa-fw"></i></th>
                                      <th rowspan="2">Start Date</th>
                                      <th rowspan="2">End Date</th>
                                      <th rowspan="2"><a data-toggle="modal" href="#myModal2" style="color:red; font-size:10px;"> Add Task Type</a><br/>Task Type</th>
                                      <th rowspan="2">Status</th>
                                      <th rowspan="2">PW Wage Rate</th>
                                    </tr>
                                    <tr>
                                       @if(!empty($pw_wages_rates_type))
                                          @foreach($pw_wages_rates_type as $key=>$val) 
                                            @if($key == 1)
                                              <th align="center">{{ucwords($val)}}</th>
                                            @endif
                                          @endforeach  
                                       @endif
                                          <th align="center">Regular</th>
                                          <th align="center">Over Time</th>
                                          <th align="center">Double Time</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <div id="create_row_for_JobNumber"></div>
                              </tbody>
                              </table>
                              <br/>
                              {{ Form::hidden('count_records','', array('id' => 'count_records')) }}
                              {{ Form::hidden('count_cj_records','', array('id' => 'count_cj_records')) }}
                        <div class="btn-group" style="float:right;">
                        {{Form::submit('Submit', array('class' => 'btn btn-info', 'onclick'=>'return validateForm()'))}}
                        </div>
              </section>    
              {{ Form::close() }}
              <!-- ************************** Modal ***************************** -->
                              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              {{Form::button('&times;', array('class' => 'close', 'data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Add New Employee Type</h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group">
                                                  {{Form::label('emp_type_name', 'Type Name*: ', array('class' => 'control-label col-md-2'))}}
                                                  <div class="col-md-6">
                                                  {{ Form::text('emp_type_name','', array('class' => 'form-control dpd1', 'id' => 'emp_type_name','required')) }}   
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                            {{Form::submit('Submit', array('class' => 'btn btn-success', 'data-dismiss'=>'modal', 'id'=>'submit_emp_type_name'))}}
                                            {{Form::button('Cancel', array('class' => 'btn btn-danger', 'data-dismiss'=>'modal'))}}
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <!-- modal -->
                              <!-- Modal2 -->
                              <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close', 'data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Add New Task Type</h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group">
                                                  {{Form::label('task_type_name', 'Task Type*: ', array('class' => 'control-label col-md-2'))}}
                                                  <div class="col-md-6">
                                                  {{ Form::text('task_type_name','', array('class' => 'form-control dpd1', 'id' => 'task_type_name','required')) }}  
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                            {{Form::submit('Submit', array('class' => 'btn btn-success', 'data-dismiss'=>'modal', 'id'=>'submit_task_type_name'))}}
                                            {{Form::button('Cancel', array('class' => 'btn btn-danger', 'data-dismiss'=>'modal'))}}
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <!-- modal2 -->  
              </div>
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
      <script type="text/javascript">
      var status_msg = '<?php echo $insert_status_msg;?>';
      if (status_msg == 1)
         alert("Record Saved Successfully"); 

function validateForm() {
  var i=0;
  var len = $('input#count_records').val();

 while(i < $('input#count_cj_records').val()){
  //if ($("#typeOfEmp_"+i).val() != ""){
    document.getElementById("typeOfEmp_"+i).required = true;
    document.getElementById("ContractTaskTypePwHours_"+i).required = true;
    document.getElementById("rtBreakUps_"+i).required = true;
    document.getElementById("SSDate_"+i).required = true;
    document.getElementById("EEDate_"+i).required = true;
  //}

  i = parseInt(i) + parseInt("1");
 }
 return true;
}
        $('.default-date-picker').datepicker({
              format: 'yyyy-mm-dd',
              minDate: new Date()
        });
      

         $('#JobNumber').focus(function(event) {
            event.preventDefault();  
            $(this).autocomplete({
            source: function (request, response) {
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
        });
        $('#JobNumber').on('change',function(){
          var jobNum = $('#JobNumber').val();
          if (jobNum != "") {
               $.ajax({
                      url: "{{URL('ajax/validateJobNumber')}}",
                      data: {
                        'job_num' : jobNum,
                      },
                      success: function (data) {
                        if (data == 0){ 
                          alert("Job Number Specified does not exist!  Please Select another Job Number.");
                          $('#JobNumber').val("");
                        }
                      },
                });
          }

        }); 
          
          $('#ContractNumber').focus(function(event) {
            event.preventDefault();  
            $(this).autocomplete({
            source: function (request, response) {
            $.ajax({
              url: "{{URL('ajax/getContractNumberAutocomplete')}}",
              data: {
              ContractNumber: this.term
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
          });

          $('#submit_emp_type_name').click(function(){
             if ($('#emp_type_name').val() == '') {
                alert('Employee Type Must not be empty.');
                //$('#myModal2').hide();
            }else{
              var val_emp = $('#emp_type_name').val();
              if (val_emp != '') {
                 $.ajax({
                      url: "{{URL('ajax/createNewEmployeetype')}}",
                      data: {
                        'emp_type_val' : $('#emp_type_name').val(),
                      },
                      success: function (data) {
                        alert("Successfully Employee Type added.");
                        location.reload();
                      },
                });
              }
            }
         }); 

          $('#submit_task_type_name').click(function(){
            if ($('#task_type_name').val() == '') {
                alert('Task Type Must not be empty.');
                //$('#myModal2').hide();
            }else{
              var task_val = $('#task_type_name').val();
              if (task_val != '') {
                 $.ajax({
                      url: "{{URL('ajax/creatNewTasktype')}}",
                      data: {
                        'task_type_val' : $('#task_type_name').val(),
                      },
                      success: function (data) {
                        alert("Successfully Task Type added.");
                        location.reload();
                      },
                });
              }
            }
         });
          $('#rateBreakup').change(function() {
              var split0 = $(this).attr("name").split('rateBreakup_');
              var split1 = split0[1].split('_');
              if (split1[1] == 1)
                $('#pwpay_'+split1[0]).val($(this).val()); 
          });
        var count = 1;         
        $("#add_another_row").click(function(){
          var str = "<td data-title='Employee Types:'><select name='etype_"+count+"' id='etype_"+count+"' class='form-control l-bot6' required>"+document.getElementById('etype').innerHTML+"</select></td>";
              str += "<td data-title='County:'><select name='countyName_"+count+"' id='countyName_"+count+"' class='form-control m-bot12'>"+document.getElementById('countyName').innerHTML+"</select></td>";
              str += "<?php if(!empty($pw_wages_rates_type)){ foreach($pw_wages_rates_type as $key=>$val){ if($key == 1){?> <td align='center' data-title='Rate Breakup_<?php echo $key;?>:'><input class='form-control' id='rateBreakup_"+count+"' name='rateBreakup_"+count+"_<?php echo $key;?>' type='text' required/><br/><i style='margin-top:5px;' myCounter='"+count+"' id='show_hide_div_"+(parseInt('1')+parseInt(count))+"' class='fa fa-plus'></i></td><?php }}} ?>";
              str += "<td align='center' data-title='Regular:'><input class='form-control' id='pwpay_"+count+"' name='pwpay_"+count+"' type='text' readonly/></td>";
              str += "<td align='center' data-title='Over Time:'><input class='form-control' id='pwovertime_"+count+"' name='pwovertime_"+count+"' type='text' /></td>";
              str += "<td align='center' data-title='Double Time:'><input class='form-control' id='pwdouble_"+count+"' name='pwdouble_"+count+"' type='text' /></td>";
              str += "<td data-title='Start Date:'><input class='form-control form-control-inline input-medium default-date-picker2' id='SDate_"+count+"' name='SDate_"+count+"' size='35' type='text' value='' required/></td>";
              str += "<td data-title='End Date:'><input class='form-control form-control-inline input-medium default-date-picker2' id='SDate_"+count+"' name='EDate_"+count+"' size='35' type='text' value='' required/></td>";
              str += "<td data-title='Task Type:'><select name='taskType_"+count+"' id='taskType_"+count+"' class='form-control m-bot12'>"+document.getElementById('taskType').innerHTML+"</select></td>";
              str += "<td data-title='Status:'><select name='status_"+count+"' id='status_"+count+"'  class='form-control m-bot12'>"+document.getElementById('status').innerHTML+"</select></td>";
              str += "<td data-title='Wage Type:'><select name='wageType_"+count+"' id='wageType_"+count+"' class='form-control m-bot12'>"+document.getElementById('wageType').innerHTML+"</select></td>";
              str += "";

              str2 = "<td colspan='11'><div id='toggle_for_table1_"+(parseInt('1')+parseInt(count))+"'><table class='table table-bordered table-striped table-condensed cf' style='cellspacing=0px; cellpadding=0px;' align='center'><tbody><tr><?php  foreach($pw_wages_rates_type as $key2=>$val2){ if ($key2 != 1){ ?><td data-title='Rate Breakup_<?php echo $key2;?>:'><?php echo $val2; ?><br/><input class='form-control' id='rateBreakup' name='rateBreakup_"+count+"_<?php echo $key2;?>' type='text'/></td><?php } }?></tr></tbody></table></div></td>";
              //$('#mytable > tbody > tr:last').after(str);
              newTR = document.createElement('tr');
              $(newTR).html(str);
              newTR2 = document.createElement('tr');
              $(newTR2).html(str2);
              //$('#mytable tr').eq(parseInt('1')+parseInt(count)).append(newTR);
              $('#mytable').append(newTR);
              $('#mytable').append(newTR2);

              $('.default-date-picker2').datepicker({
                format: 'yyyy-mm-dd',
                startDate: 'today' 
              });

              $("#toggle_for_table1_"+(parseInt('1')+parseInt(count))).hide(); 
              $("#show_hide_div_"+(parseInt('1')+parseInt(count))).click(function(){
                counter = $(this).attr('myCounter');
                //alert(("#toggle_for_table1_"+(parseInt('1')+parseInt(counter))));
              $("#toggle_for_table1_"+(parseInt('1')+parseInt(counter))).toggle("slow");
                    if ($(this).attr("class") == "fa fa-plus")
                        $(this).removeClass('fa fa-plus').addClass('fa fa-minus');
                    else 
                        $(this).removeClass('fa fa-minus').addClass('fa fa-plus');

              }); 

               $('#rateBreakup_'+count).change(function() {
              var split0 = $(this).attr("name").split('rateBreakup_');
              var split1 = split0[1].split('_');
              if (split1[1] == 1)
                $('#pwpay_'+split1[0]).val($(this).val()); 
             });

             count = parseInt(count) + parseInt("1");
               $('input#count_job_records').val(count);  
            return false;
        });

        $('#remove_row').click( function() {
            if (count>1){
              $('#mytable > tbody > tr:last').remove();
              $('#mytable > tbody > tr:last').remove();
              count=count-1;
              $('input#count_job_records').val(count);
            }
        });  

          if ($(window).width() > 900)
            var my_cell_width = '100px';
          else
            var my_cell_width = '100%';

          var str1 = "<td><i id='add_new_contract_line' class='fa fa-plus'></i></td>";
              str1 += "<td data-title='Job Type:'><select name='jobType_0' id='jobType_0' class='form-control l-bot6'><?php foreach($job_type_array as $key=>$val){?><option value='<?php echo $val;?>'><?php echo $val;?></option><?php }?></select></td>";
              str1 += "<td data-title='Regarding:'><select name='contractTypeRegarding_0' class='form-control m-bot12' id='contractTypeRegarding_0'></select></td>";
              str1 += "<td data-title='Employee Types:'><select name='emptype_0' id='emptype_0' class='form-control l-bot6' required>"+document.getElementById('etype').innerHTML+"</select></td>";
              str1 += "<td data-title='County:'><select name='cntyName_0' id='cntyName_0' class='form-control m-bot12'>"+document.getElementById('countyName').innerHTML+"</select></td>";
              str1 += "<td align='center' data-title='Hours:'><input class='form-control' id='pwhours_contract_taskstype_0' name='pwhours_contract_taskstype_0' type='text' required/></td>";
              str1 += "<?php if(!empty($pw_wages_rates_type)){ foreach($pw_wages_rates_type as $key=>$val){ if($key == 1){?> <td align='center' data-title='Rate Breakup_<?php echo $key;?>:'><input class='form-control' id='RTBreakup' name='RTBreakup_0_<?php echo $key;?>' type='text' required/><br/><i id='show_hide_div2_0' class='fa fa-plus' style='margin-top:5px;'></i></td><?php }}} ?>";
              str1 += "<td align='center' data-title='Regular:'><input class='form-control' id='regPw_0' name='regPw_0' type='text' readonly/></td>";
              str1 += "<td align='center' data-title='Over Time:'><input class='form-control' id='overtimePW_0' name='overtimePW_0' type='text' /></td>";
              str1 += "<td align='center' data-title='Double Time:'><input class='form-control' id='DoublePW_0' name='DoublePW_0' type='text' /></td>";
              str1 += "<td data-title='Start Date:'><input class='form-control form-control-inline input-medium default-date-picker4' id='S_Date_0' name='S_Date_0' size='35' type='text' value='' required/></td>";
              str1 += "<td data-title='End Date:'><input class='form-control form-control-inline input-medium default-date-picker4' id='E_Date_0' name='E_Date_0' size='35' type='text' value='' required/></td>";
              str1 += "<td data-title='Task Type:'><select name='taskTypes_0' id='taskTypes_0' class='form-control m-bot12'>"+document.getElementById('taskType').innerHTML+"</select></td>";
              str1 += "<td data-title='Status:'><select name='Status_0' id='Status_0'  class='form-control m-bot12'>"+document.getElementById('status').innerHTML+"</select></td>";
              str1 += "<td data-title='Wage Type:'><select name='WageTypes_0' id='WageTypes_0' class='form-control m-bot12'>"+document.getElementById('wageType').innerHTML+"</select></td>";
              str1_2 = "<td colspan='15'><div id='toggle_for_table2_0'><table class='table table-bordered table-striped table-condensed cf' style='cellspacing=0px; cellpadding=0px;' align='center'><tbody><tr><?php  foreach($pw_wages_rates_type as $key2=>$val2){ if ($key2 != 1){ ?><td data-title='Rate Breakup_<?php echo $key2;?>:'><?php echo $val2; ?><br/><input class='form-control' id='RTBreakup' name='RTBreakup_0_<?php echo $key2;?>' type='text'/></td><?php } }?></tr></tbody></table></div></td>";
        
        $("#go_contrct_number").click(function(){
            if ($('#ContractNumber').val() == '')
              alert("Contract Number must not be empty!");
            else{
               $.ajax({
                      url: "{{URL('ajax/testContractNumber')}}",
                      data: {
                        'contract_number' : $('#ContractNumber').val(),
                      },
                      success: function (data) {
                        if (data.search_result == 0)
                           alert("No jobs found against Contract Number:"+$('#ContractNumber').val()); 
                        else if(data.search_result == 1){
                          $("#create_row_for_ContractNumber").html(str1);
                          $("#create_toggle_row_for_ContractNumber").html(str1_2);

                          $("#toggle_for_table2_0").hide(); 
                          $("#show_hide_div2_0").click(function(){
                              $("#toggle_for_table2_0").toggle("slow");
                                if ($(this).attr("class") == "fa fa-plus")
                                    $(this).removeClass('fa fa-plus').addClass('fa fa-minus');
                                else 
                                    $(this).removeClass('fa fa-minus').addClass('fa fa-plus');
                          }); 

                          $('#RTBreakup').change(function() {
                            var split0 = $(this).attr("name").split('RTBreakup_');
                            var split1 = split0[1].split('_');
                            if (split1[1] == 1)
                              $('#regPw_'+split1[0]).val($(this).val()); 
                          });

                          $("#contractTypeRegarding_0").html(data.regarding_opts);
                          $("#mytable3 > tbody").html("");
                          var job_num_obj = data.job_nums_arr;
                          var job_tasks_obj = data.job_tasks_arr;
                          var word = JSON.stringify(data.job_nums_arr);
                          var arr = word.split(',');
                           $('input#count_records').val(parseInt('1'));
                          for (var i = 0; i < arr.length; i++) {//write line for table3                           
                            var str2 = "<tr id='table3RowId_"+i+"'><td name='table3ColId' id='table3ColId_"+i+"'><i class='fa fa-plus'></i></td>";
                                str2 += "<td data-title='Job Number:'><input class='form-control' id='numberOfJob_"+i+"' name='numberOfJob_"+i+"' value='"+job_num_obj[i]+"' type='text' readonly/></td>";
                                str2 += "<td data-title='Regarding:'><input class='form-control' id='regardingTask_"+i+"' name='regardingTask_"+i+"' value='"+job_tasks_obj[i]+"' type='text' readonly/></td>";
                                str2 += "<td data-title='Employee Types:'><select name='typeOfEmp_"+i+"' id='typeOfEmp_"+i+"' class='form-control l-bot6' >"+document.getElementById('etype').innerHTML+"</select></td>";
                                str2 += "<td data-title='County:'><select name='nameOfCounty_"+i+"' id='nameOfCounty_"+i+"' class='form-control m-bot12'>"+document.getElementById('countyName').innerHTML+"</select></td>";
                                str2 += "<td align='center' data-title='Hours:'><input class='form-control' id='ContractTaskTypePwHours_"+i+"' name='ContractTaskTypePwHours_"+i+"' type='text'/></td>";
                                str2 += "<?php if(!empty($pw_wages_rates_type)){ foreach($pw_wages_rates_type as $key=>$val){ if($key == 1){?> <td align='center' data-title='Rate Breakup_<?php echo $key;?>:'><input class='form-control' id='rtBreakUps_"+i+"' name='rtBreakUps_"+i+"_<?php echo $key;?>' type='text'/><br/><i id='show_hide_div3_"+i+"' myCounter3='"+i+"' class='fa fa-plus' style='margin-top:5px;'></i></td><?php }}} ?>";
                                str2 += "<td align='center' data-title='Regular:'><input class='form-control' id='regOFPw_"+i+"' name='regOFPw_"+i+"' type='text' readonly/></td>";
                                str2 += "<td align='center' data-title='Over Time:'><input class='form-control' id='overPWTime_"+i+"' name='overPWTime_"+i+"' type='text' /></td>";
                                str2 += "<td align='center' data-title='Double Time:'><input class='form-control' id='DoublePWTime_"+i+"' name='DoublePWTime_"+i+"' type='text' /></td>";
                                str2 += "<td data-title='Start Date:'><input class='form-control form-control-inline input-medium default-date-picker5' id='SSDate_"+i+"' name='SSDate_"+i+"' size='35' type='text' value='' /></td>";
                                str2 += "<td data-title='End Date:'><input class='form-control form-control-inline input-medium default-date-picker5' id='EEDate_"+i+"' name='EEDate_"+i+"' size='35' type='text' value='' /></td>";
                                str2 += "<td data-title='Task Type:'><select name='typesOfTask_"+i+"' id='typesOfTask_"+i+"' class='form-control m-bot12'>"+document.getElementById('taskType').innerHTML+"</select></td>";
                                str2 += "<td data-title='Status:'><select name='statusByJobNum_"+i+"' id='statusByJobNum_"+i+"'  class='form-control m-bot12'>"+document.getElementById('status').innerHTML+"</select></td>";
                                str2 += "<td data-title='Wage Type:'><select name='typesofWages_"+i+"' id='typesofWages_"+i+"' class='form-control m-bot12'>"+document.getElementById('wageType').innerHTML+"</select></td></tr>";
                                str2_c = "<td colspan='15'><div id='toggle_for_table3_"+i+"'><table class='table table-bordered table-striped table-condensed cf' style='cellspacing=0px; cellpadding=0px;' align='center'><tbody><tr><?php  foreach($pw_wages_rates_type as $key2=>$val2){ if ($key2 != 1){ ?><td data-title='Rate Breakup_<?php echo $key2;?>:'><?php echo $val2; ?><br/><input class='form-control' id='rtBreakUps_"+i+"' name='rtBreakUps_"+i+"_<?php echo $key2;?>' type='text'/></td><?php } }?></tr></tbody></table></div></td>";
                                $('#mytable3 > tbody:last').append(str2); 
                                $('#mytable3 > tbody:last').append("<tr>"+str2_c+"</tr>");

                                $("#toggle_for_table3_"+i).hide(); 
                                $("#show_hide_div3_"+i).click(function(){
                                  counter = $(this).attr('myCounter3');
                                    $("#toggle_for_table3_"+counter).toggle("slow");
                                      if ($(this).attr("class") == "fa fa-plus")
                                          $(this).removeClass('fa fa-plus').addClass('fa fa-minus');
                                      else 
                                          $(this).removeClass('fa fa-minus').addClass('fa fa-plus');
                                });  

                                $('#rtBreakUps_'+i).change(function() {
                                      var split0 = $(this).attr("name").split('rtBreakUps_');
                                      var split1 = split0[1].split('_');
                                      if (split1[1] == 1)
                                        $('#regOFPw_'+split1[0]).val($(this).val()); 
                                });
                                $('input#count_cj_records').val(i);

                                $('.default-date-picker5').datepicker({
                                    format: 'yyyy-mm-dd',
                                    startDate: 'today' 
                                });
                          }// end for loop for table3 line
                          counter=0;
                          $('td[name=table3ColId]').on('click',function(){
                              var splits = $(this).attr("id").split('table3ColId_');
                               $('input#count_records').val(parseInt(counter)+parseInt('1'));                               
                              // create str copy here and also pick values from the above table row
                              NewCounter = (parseInt(counter)+parseInt(arr.length));
                              var str2c = "<tr id='table3RowId_"+NewCounter+"'>";
                                str2c += "<td data-title='Job Number:' colspan='2'><input class='form-control' id='numberOfJob_"+NewCounter+"' name='numberOfJob_"+NewCounter+"' value='"+document.getElementById("numberOfJob_"+splits[1]).value+"' type='text' readonly/></td>";
                                str2c += "<td data-title='Regarding:'><input class='form-control' id='regardingTask_"+NewCounter+"' name='regardingTask_"+NewCounter+"' value='"+document.getElementById("regardingTask_"+splits[1]).value+"' type='text' readonly/></td>";
                                str2c += "<td data-title='Employee Types:'><select name='typeOfEmp_"+NewCounter+"' id='typeOfEmp_"+NewCounter+"' class='form-control l-bot6' >"+document.getElementById('etype').innerHTML+"</select></td>";
                                str2c += "<td data-title='County:'><select name='nameOfCounty_"+NewCounter+"' id='nameOfCounty_"+NewCounter+"' class='form-control m-bot12'>"+document.getElementById('countyName').innerHTML+"</select></td>";
                                str2c += "<td align='center' data-title='Hours:'><input class='form-control' id='ContractTaskTypePwHours_"+NewCounter+"' name='ContractTaskTypePwHours_"+NewCounter+"' type='text'/></td>";
                                str2c += "<?php if(!empty($pw_wages_rates_type)){ foreach($pw_wages_rates_type as $key=>$val){ if($key == 1){?> <td align='center' data-title='Rate Breakup_<?php echo $key;?>:'><input class='form-control' id='rtBreakUps_"+NewCounter+"' name='rtBreakUps_"+NewCounter+"_<?php echo $key;?>' type='text'/><br/><i id='show_hide_div4_"+NewCounter+"' myCounter4='"+NewCounter+"' class='fa fa-plus' style='margin-top:5px;'></i></td><?php }}} ?>";
                                str2c += "<td align='center' data-title='Regular:'><input class='form-control' id='regOFPw_"+NewCounter+"' name='regOFPw_"+NewCounter+"' type='text' readonly/></td>";
                                str2c += "<td align='center' data-title='Over Time:'><input class='form-control' id='overPWTime_"+NewCounter+"' name='overPWTime_"+NewCounter+"' type='text' /></td>";
                                str2c += "<td align='center' data-title='Double Time:'><input class='form-control' id='DoublePWTime_"+NewCounter+"' name='DoublePWTime_"+NewCounter+"' type='text' /></td>";
                                str2c += "<td data-title='Start Date:'><input class='form-control form-control-inline input-medium default-date-picker5' id='SSDate_"+NewCounter+"' name='SSDate_"+NewCounter+"' size='35' type='text' value='' required/></td>";
                                str2c += "<td data-title='End Date:'><input class='form-control form-control-inline input-medium default-date-picker5' id='EEDate_"+NewCounter+"' name='EEDate_"+NewCounter+"' size='35' type='text' value='' required/></td>";
                                str2c += "<td data-title='Task Type:'><select name='typesOfTask_"+NewCounter+"' id='typesOfTask_"+NewCounter+"' class='form-control m-bot12'>"+document.getElementById('taskType').innerHTML+"</select></td>";
                                str2c += "<td data-title='Status:'><select name='statusByJobNum_"+NewCounter+"' id='statusByJobNum_"+NewCounter+"'  class='form-control m-bot12'>"+document.getElementById('status').innerHTML+"</select></td>";
                                str2c += "<td data-title='Wage Type:'><select name='typesofWages_"+NewCounter+"' id='typesofWages_"+NewCounter+"' class='form-control m-bot12'>"+document.getElementById('wageType').innerHTML+"</select></td></tr>";
                                str2c_c = "<td colspan='15'><div id='toggle_for_table4_"+NewCounter+"'><table class='table table-bordered table-striped table-condensed cf' style='cellspacing=0px; cellpadding=0px;' align='center'><tbody><tr><?php  foreach($pw_wages_rates_type as $key2=>$val2){ if ($key2 != 1){ ?><td data-title='Rate Breakup_<?php echo $key2;?>:'><?php echo $val2; ?><br/><input class='form-control' id='rtBreakUps_"+NewCounter+"' name='rtBreakUps_"+NewCounter+"_<?php echo $key2;?>' type='text'/></td><?php } }?></tr></tbody></table></div></td>";
                                $(this).closest('tr').after(str2c_c);
                                $(this).closest('tr').after(str2c);

                                $("#toggle_for_table4_"+(parseInt(counter)+parseInt(arr.length))).hide(); 
                                $("#show_hide_div4_"+(parseInt(counter)+parseInt(arr.length))).click(function(){
                                  counter = $(this).attr('myCounter4');
                                    $("#toggle_for_table4_"+counter).toggle("slow");
                                      if ($(this).attr("class") == "fa fa-plus")
                                          $(this).removeClass('fa fa-plus').addClass('fa fa-minus');
                                      else 
                                          $(this).removeClass('fa fa-minus').addClass('fa fa-plus');
                                });    
                                
                                $('#rtBreakUps_'+(parseInt(counter)+parseInt(arr.length))).change(function() {
                                      var split0 = $(this).attr("name").split('rtBreakUps_');
                                      var split1 = split0[1].split('_');
                                      if (split1[1] == 1)
                                        $('#regOFPw_'+split1[0]).val($(this).val()); 
                                });
                                
                                $('.default-date-picker5').datepicker({
                                    format: 'yyyy-mm-dd',
                                    startDate: 'today' 
                                });

                              counter = parseInt(counter)+1;
                              $('input#count_cj_records').val((parseInt(counter)+parseInt(arr.length)));
                          });

                          $('.default-date-picker4').datepicker({
                              format: 'yyyy-mm-dd',
                              startDate: 'today' 
                          });
                          var count2=1;
                          $("#add_new_contract_line").click(function(){
                                  var  strc1n = "<td data-title='Job Type:' colspan='2'><select name='jobType_"+count2+"' id='jobType_"+count2+"' class='form-control l-bot6'><?php foreach($job_type_array as $key=>$val){?><option value='<?php echo $val;?>'><?php echo $val;?></option><?php }?></select></td>";
                                    strc1n += "<td data-title='Regarding:'><select name='contractTypeRegarding_"+count2+"' class='form-control m-bot12' id='contractTypeRegarding_"+count2+"'>"+document.getElementById('contractTypeRegarding_0').innerHTML+"</select></td>";
                                    strc1n += "<td data-title='Employee Types:'><select name='emptype_"+count2+"' id='emptype_"+count2+"' class='form-control l-bot6' required>"+document.getElementById('etype').innerHTML+"</select></td>";
                                    strc1n += "<td data-title='County:'><select name='cntyName_"+count2+"' id='cntyName_"+count2+"' class='form-control m-bot12'>"+document.getElementById('countyName').innerHTML+"</select></td>";
                                    strc1n += "<td align='center' data-title='Hours:'><input class='form-control' id='pwhours_contract_taskstype_"+count2+"' name='pwhours_contract_taskstype_"+count2+"' type='text' required/></td>";
                                    strc1n += "<?php if(!empty($pw_wages_rates_type)){ foreach($pw_wages_rates_type as $key=>$val){ if($key == 1){ ?> <td align='center' data-title='Rate Breakup_<?php echo $key;?>:'><input class='form-control' id='RTBreakup_"+count2+"' name='RTBreakup_"+count2+"_<?php echo $key;?>' type='text' required/><br/><i id='show_hide_div2_"+count2+"' myCounter1='"+count2+"' class='fa fa-plus' style='margin-top:5px;'></i></td><?php }}} ?>";
                                    strc1n += "<td align='center' data-title='Regular:'><input class='form-control' id='regPw_"+count2+"' name='regPw_"+count2+"' type='text' readonly/></td>";
                                    strc1n += "<td align='center' data-title='Over Time:'><input class='form-control' id='overtimePW_"+count2+"' name='overtimePW_"+count2+"' type='text' /></td>";
                                    strc1n += "<td align='center' data-title='Double Time:'><input class='form-control' id='DoublePW_"+count2+"' name='DoublePW_"+count2+"' type='text' /></td>";
                                    strc1n += "<td data-title='Start Date:'><input class='form-control form-control-inline input-medium default-date-picker3' id='S_Date_"+count2+"' name='S_Date_"+count2+"' size='35' type='text' value='' required/></td>";
                                    strc1n += "<td data-title='End Date:'><input class='form-control form-control-inline input-medium default-date-picker3' id='E_Date_"+count2+"' name='E_Date_"+count2+"' size='35' type='text' value='' required/></td>";
                                    strc1n += "<td data-title='Task Type:'><select name='taskTypes_"+count2+"' id='taskTypes_"+count2+"' class='form-control m-bot12'>"+document.getElementById('taskType').innerHTML+"</select></td>";
                                    strc1n += "<td data-title='Status:'><select name='Status_"+count2+"' id='Status_"+count2+"'  class='form-control m-bot12'>"+document.getElementById('status').innerHTML+"</select></td>";
                                    strc1n += "<td data-title='Wage Type:'><select name='WageTypes_"+count2+"' id='WageTypes_"+count2+"' class='form-control m-bot12'>"+document.getElementById('wageType').innerHTML+"</select></td>";
                                    strc1n_2 = "<td colspan='15'><div id='toggle_for_table2_"+count2+"'><table class='table table-bordered table-striped table-condensed cf' style='cellspacing=0px; cellpadding=0px;' align='center'><tbody><tr><?php  foreach($pw_wages_rates_type as $key2=>$val2){ if ($key2 != 1){ ?><td data-title='Rate Breakup_<?php echo $key2;?>:'><?php echo $val2; ?><br/><input class='form-control' id='RTBreakup' name='RTBreakup_"+count2+"_<?php echo $key2;?>' type='text'/></td><?php } }?></tr></tbody></table></div></td>";

                                    $('#mytable2 > tbody:last').append("<tr>"+strc1n+"</tr>");
                                    $('#mytable2 > tbody:last').append("<tr>"+strc1n_2+"</tr>");

                                    $("#toggle_for_table2_"+count2).hide(); 
                                    $("#show_hide_div2_"+count2).click(function(){
                                      counter = $(this).attr('myCounter1');
                                        $("#toggle_for_table2_"+(counter)).toggle("slow");
                                          if ($(this).attr("class") == "fa fa-plus")
                                              $(this).removeClass('fa fa-plus').addClass('fa fa-minus');
                                          else 
                                              $(this).removeClass('fa fa-minus').addClass('fa fa-plus');
                                    }); 
                                    
                                    $('#RTBreakup_'+count2).change(function() {
                                      var split0 = $(this).attr("name").split('RTBreakup_');
                                      var split1 = split0[1].split('_');
                                      if (split1[1] == 1)
                                        $('#regPw_'+split1[0]).val($(this).val()); 
                                    });
                                  
                                    $('.default-date-picker3').datepicker({
                                          format: 'yyyy-mm-dd',
                                          startDate: 'today' 
                                    });
                               
                             count2 = parseInt(count2) + parseInt("1");  
                          });
                          
                        }
                      },
                });
            }
        });

      $("#toggle_for_table1_1").hide(); 
      $("#show_hide_div_1").click(function(){
          $("#toggle_for_table1_1").toggle("slow");
            if ($(this).attr("class") == "fa fa-plus")
                $(this).removeClass('fa fa-plus').addClass('fa fa-minus');
            else 
                $(this).removeClass('fa fa-minus').addClass('fa fa-plus');
      });
      </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
      <script src="{{asset('js/common-scripts.js')}}"></script>
      
@stop