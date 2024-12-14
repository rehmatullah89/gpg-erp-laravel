@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$sdate = "";
$edate = "";
?>
 <header class="panel-heading">
     	VIEW JOBS HISTORY [{{$employeeinfo[0]->name}} ]
    <span class="tools pull-right">
       <a href="javascript:;" class="fa fa-chevron-down"></a>
    </span>
 </header>

@if (isset($errors) && ($errors->any()))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Error</h4>
                    <ul>
                        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                    </ul>
                </div>
            @endif
            
            @if(@Session::has('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Success</h4>
                    <ul>
                        {{ Session::get('success') }}
                    </ul>
                </div>
            @endif

 {{ Form::open(array('before' => 'csrf' ,'action' => 'EmployeeController@index', 'files'=>true, 'method' => 'post', 'id' => 'customer_filters_form')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Start Date:">
                                    {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td><td data-title="End Date:">
                                    {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>                                  
                                 
                                  <td data-title="Filter:">
                                    {{Form::label('fliter', 'Filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('Filter', array(''=>'Select Filter','name' => 'Real Name','login' => 'Login Name','status' => 'Member Status' ), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Filter Value:">
                                    {{Form::label('filterValue', 'Filter Value:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('FVal','', array('class' => 'form-control form-control-inline input-medium', 'id' => 'filterValue')) }}
                                  </td>
                                </tr>
                             
                                <tr>
                                <td colspan="8">
                                  <span class="smallblack"><strong>Note:</strong> 
                                     </span><br/>
                                     <span>
                                          1.Please leave empty the End date if you want to search for a particular date.
                                     </span><br>
                                      <span>
                                         2.Please leave empty the Start date if you want to search from very start to End Date
                                      </span>
                                  
                                </td>                                
                              </tbody>
                          </table>
                                    <br/>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info submit-form', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
                      </section>
                               {{ Form::close() }}
                               
<div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                    <thead class="cf">

                        <tr>
                            <th style="text-align:center;">ID#</th>
                            <th style="text-align:center;">Dated</th>
                            <th style="text-align:center;">Job(s) Done</th>
                            <th style="text-align:center;">Job(s) worked on</th>
                            <th style="text-align:center;">Total Work Hours</th>
                        </tr>
                    </thead>
                <tbody>
                
                <?php 
                 $prev = "";
                foreach($query_data as $data){
                    
                ?>
                
                   <tr >
          <td align="center" ><strong>&nbsp;<? echo $data['id'] ?></strong></td>
          <td height="30" >&nbsp;<strong><? echo date("d M, Y",strtotime($data['date'])); ?></strong></td>
          <td ><div align="center"><? echo $data['job_done'] ?></div></td>
          <td ><div align="center"><strong>&nbsp;<? echo $data['t_jobs'] ?></strong></div></td>
		  <td align="center"><div align="center"><strong><? echo $data['t_jobs_time']; ?></strong></div></td>
		  </tr>
        <tr >
          <td height="30" colspan="5" >
            <table width="100%" border="0" cellspacing="2" cellpadding="2">
              <tr >
                <td width="13%" ><strong>Job Number</strong></td>
                <td width="21%" ><strong>Type</strong></td>
                <td width="15%" ><strong>Time In</strong></td>
                <td width="13%" ><strong>Time Out</strong></td>
				<td width="12%" ><strong>Difference</strong></td>
                <td width="10%" ><strong>Fraction</strong></td>
                <td width="16%" ><strong>Status</strong></td>
              </tr>
                <?  
                    foreach(@$data['wages_array'] as $key => $wagesRow) {

                 ?>
                    <tr >
                        <td ><? echo $wagesRow->job_num ?></td>
                                        <td ><? if ($wagesRow->prevail==1) echo "<strong>PREVAILING</strong>"; else echo "REGULAR"; ?></td>
                        <td ><? echo date("g:ia",strtotime($wagesRow->time_in)); ?></td>
                        <td ><? echo date("g:ia",strtotime($wagesRow->time_out)); ?></td>
                        <td ><? $timearray = EmployeeController::get_time_difference( $wagesRow->time_in, $wagesRow->time_out); 
                              echo $timearray['hours']."H ".$timearray['minutes']."M";
                        ?></td>
                        <td ><? echo EmployeeController::convertTime($timearray['hours'].":".$timearray['minutes'])."H";	?></td>
                        <td ><? if ($wagesRow->complete_flag==1) echo "<strong>Completed</strong>"; else echo "Uncompleted";	?></td>
                    </tr>
                <? } ?>
            </table></td>
          </tr>
                  
               <? }?>
               
                    </tbody>
                </table>
                {{ $query_data->links() }}
              </section>
              </div>
              </div>

          <!-- Modal# -->
           <div class="modal fade" id="myModalDeductions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">::Manage Deductions::</h4>
                      </div>
                    <div class="modal-body">
                        <p></p>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# end--> 
        
        
         <!-- Modal# -->
           <div class="modal fade" id="myModalChangePwd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">::Change Password::</h4>
                      </div>
                    <div class="modal-body">
                        <p></p>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# end--> 
        
        
         <!-- Modal# -->
           <div class="modal fade" id="myModalSetBurden" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">::Set Burden::</h4>
                      </div>
                    <div class="modal-body">
                        <p></p>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# end--> 
        
        <!-- Modal# -->
           <div class="modal fade" id="myModalSetCommission" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">::Set Commission::</h4>
                      </div>
                    <div class="modal-body">
                        <p></p>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# end--> 
       
        <!-- Modal# -->
           <div class="modal fade" id="myModalSetRate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">::Set Commission::</h4>
                      </div>
                    <div class="modal-body">
                        <p></p>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# end-->  
        
      <!-- Modal# -->
           <div class="modal fade" id="myModalRecordView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">::VIEW EMPLOYEE::</h4>
                      </div>
                    <div class="modal-body">
                        <p></p>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# end-->    
        <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>
        <script type="text/javascript">
       
        $('.default-date-picker').datepicker({
              format: 'yyyy-mm-dd'
        });
        
        $("section[mysection=hide_n_show]").hide();
        $('#togglerButton').click(function(){
           $("section[mysection=hide_n_show]").toggle("slow");
           if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
              $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
           else 
              $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
        }); 
      
        $('.submit-form').click(function(){ 
            $('#customer_filters_form').submit();
        });
      
        $('.manage-deductions-html').click(function(){
    	
        var id = this.id;
        $.ajax({
                type:'GET',          
                url: "{{URL('ajax/employees/getDeductionsHTML')}}",
                        data: {
                          'id' : id,
                        },
                        success: function (data) {
                                $('#myModalChangePwd .modal-body p').html(data);
				$('#myModalChangePwd').modal('show');
                      },
            });

    	});
        
        $('.change-password-html').click(function(){
    	
            var id = this.id;
            $.ajax({
                type:'GET',          
                url: "{{URL('ajax/employees/changeEmployeePassword')}}",
                        data: {
                          'id' : id,
                        },
                        success: function (data) {
                                $('#myModalDeductions .modal-body p').html(data);
				$('#myModalDeductions').modal('show');
                               
                      },
            });

    	});
        
        
        $('.set-burden-html').click(function(){
    	
            var id = this.id;
            $.ajax({
                type:'GET',          
                url: "{{URL('ajax/employees/setBurden')}}",
                        data: {
                          'id' : id,
                        },
                        success: function (data) {
                                $('#myModalSetBurden .modal-body p').html(data);
				$('#myModalSetBurden').modal('show');
                                 $('.default-date-picker').datepicker({
                                        format: 'mm/dd/yyyy'
                                  });
                      },
            });

    	});

        $('.set-commission-html').click(function(){
    	
            var id = this.id;
            
            
            $.ajax({
                type:'GET',          
                url: "{{URL('ajax/employees/setCommission')}}",
                        data: {
                          'id' : id,
                        },
                        success: function (data) {
                                $('#myModalSetCommission .modal-body p').html(data);
				$('#myModalSetCommission').modal('show');
                                 $('.default-date-picker').datepicker({
                                        format: 'mm/dd/yyyy'
                                  });
                      },
            });

    	});
        
        $('.set-rate-html').click(function(){
    	
            var id = this.id;
            var type = this.type;
           
            $.ajax({
                type:'GET',          
                url: "{{URL('ajax/employees/setRate')}}",
                        data: {
                          'id' : id,
                          'type': type,
                        },
                        success: function (data) {
                                $('#myModalSetRate .modal-body p').html(data);
				$('#myModalSetRate').modal('show');
                                $('.default-date-picker').datepicker({
                                        format: 'mm/dd/yyyy'
                                });
                      },
            });

    	});

        $('.record-view-html').click(function(){
    	
            var id = this.id;
            
            $.ajax({
                type:'GET',          
                url: "{{URL('employees/viewRecord')}}",
                        data: {
                          'id' : id,
                        },
                        success: function (data) {
                                $('#myModalRecordView .modal-body p').html(data);
				$('#myModalRecordView').modal('show');
                                $('.default-date-picker').datepicker({
                                        format: 'mm/dd/yyyy'
                                });
                      },
            });

    	});
        
        
       
     
</script>
@stop