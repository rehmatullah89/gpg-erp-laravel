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
    Employees Management
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
                                    {{Form::select('Filter', array(''=>'Select Filter','name' => 'Real Name', 'login' => 'Login Name','status' => 'Member Status','new_member' => 'New Members'), null, ['id' => 'Filter','onChange'=> 'set_option(this.value)', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Filter Value:">
                                    {{Form::label('filterValue', 'Filter Value:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('FVal','', array('class' => 'form-control form-control-inline input-medium', 'id' => 'filterValue')) }}
                                    {{Form::select('status', array(''=>'Select Filter','A' => 'Active Members', 'B' => 'Inactive Members'), null, ['id' => 'StatusDropDown','class'=>'form-control m-bot15','style' => 'display:none'])}}
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
                                  {{Form::reset('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
                      </section>
                               {{ Form::close() }}
                               
<div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">

                <tr>
                    <th style="text-align:center;">ID#</th>
                    <th style="text-align:center;">Real Name</th>
                    <th style="text-align:center;">Location</th>
                    <th style="text-align:center;">Email Address</th>
                    <th style="text-align:center;">Active Frontend</th>
                    <th style="text-align:center;">Employee Type</th>
                    <th style="text-align:center;">Wage</th>
                    <th style="text-align:center;">Salary Range</th>
                    <th style="text-align:center;">Burden</th>
                    <th style="text-align:center;">Commission</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Action</th>
                </tr>
                                      </thead>
                                      <tbody>
                <?php 
                
                //echo "<pre>";
                //print_r($query_data);
                //exit;
                foreach($query_data as $data){ ?>
                <tr>
                    <td data-title="#ID:">{{ $data['id'] }}</td>
                  <td data-title="Real Name:">{{($data['name'] != "")? strtoupper($data['name']): "-"}}</td>
                  <td data-title="Location:">
                      {{$data['location']}}
                  </td>
                  <td data-title="Email Address:">{{($data['email'] != "")? $data['email'] :"-"}}</td>
                  <td data-title="Active Frontend:">
                      
                      <?php $fe = preg_split("/,/",$data['frontend']); 
                        for ($feC =0; $feC<count($fe); $feC++) {
                           if ($feC>0) echo ",";
                           echo @EmployeeController::$frontEndArray[$fe[$feC]]; 
                        }
                      ?>
                      
                  </td>
                  <td data-title="Employee Type:">
                      {{$data['employee_type']}}
                  </td>
                  
                  <td data-title="Wage:">
                      <?php
                      $rate = (float)$data['employee_rate'];
                      ?>
                      <b>{{$_DefaultCurrency.number_format($rate,2)}}</b><br>
                      started: {{date('m/d/Y',strtotime($data['employee_rate_start_date']))}}<br>
                      
                      @if($data['salaried'] == 1)
                        <b>Salary</b><br><a href="javascript:void(0)" class="set-rate-html" type="s" id="{{$data['id']}}">Set Salary</a>
                      @else
                        <b>Hourly</b><br><a href="javascript:void(0)" class="set-rate-html" type="h" id="{{$data['id']}}">Set Wage Rate</a>
                      @endif
                  </td>
                  
                  <td data-title="Salary Range:">
                       <?php echo $_DefaultCurrency.@$data['minimum_salary'].' - '. $_DefaultCurrency.@$data['higher_salary']; ?>
                  </td>
                  
                  <td data-title="Burden:">
                     <?php if ($data['burden']>0) {
                        echo "<b>".$_DefaultCurrency.number_format($data['burden'],2)."</b><br>";  
                        echo (!empty($data['burden_start_date'])?"Started:".date("m/d/Y",strtotime($data['burden_start_date']))."<br><b>Hourly</b>":'');  
                    } 
		  
		  echo '<br><a href="javascript:void(0);" class="set-burden-html" id="'.$data['id'].'" >Set Burden</a>'; ?>
                  </td>
                  
                  <td data-title="Commission:">
                      <?php
                        if ($data['estimate_commission']>0 || $data['sales_commission']>0 || $data['contract_sales_commission']>0) {
                          echo "&nbsp;&nbsp;&nbsp; Sales: <b>".number_format($data['sales_commission'],2)."%</b><br>Estimate: <b>".number_format($data['estimate_commission'],2)."% </b><br>Contract: <b>".number_format($data['contract_sales_commission'],2)."% </b><br><br>";  

                          echo (!empty($data['commission_start_date'])?"Started:".date("m/d/Y",strtotime($data['commission_start_date']))."<br>":'');  
                        } 

                        echo ('<a href="javascript:void(0);" id="'.$data['id'].'" class="set-commission-html">Set Comission</a>') 
                       ?>
                  </td>
                  <td>
                      {{EmployeeController::$UserStatus[$data['status']]}}
                  </td>
                  <td data-title="Action:" >
                  <?php
                  //$link = "index.php?main=employee&sub=index&SDate=$SDate&EDate=$EDate&Filter=$Filter&FVal=$FVal&status=$status&";
                  $link = "";
                  $page  = "";
                  ?>
                      <ul style="padding-left:25px"><?php 
                        //$fn = split(",",$row['frontend']);
                        $fn = preg_split("/,/",$data['frontend']);
                        if (in_array("timesheet",$fn)) { ?><li><a href="timesheet/emp_hist/{{$data['id']}}" ><strong>Add Timesheet</strong></a></li>
                        <?php } ?>

                        <li><a href="{{URL::route('employees/jobHistoryList', array('id'=>$data['id']))}}" >Job History</a></li>
                        <li><a href="javascript:void(0);" id="{{$data['id']}}" class="record-view-html" >View Record</a></li>
                        <li><a href="{{URL::route('employees/editEmployee', array('id'=>$data['id']))}}">Edit</a></li>
                        <li><a href="{{URL::route('employees/deleteEmployee', array('id'=>$data['id']))}}" onclick="javascript:return(confirm('Are you source, you want to DELETE this Employee?'))">Delete</a></li>
                        <li><a href="javascript:void(0);" id="{{$data['id']}}" class="change-password-html">Change Password</a></li>
                        <li><a href="javascript:void(0);" id="{{$data['id']}}" class="manage-deductions-html">Manage Deductions</a></li>

                        </ul>
                  
                  </td>
                </tr>
                
                <?php }?>
               
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
                      <h4 class="modal-title">::Set Rate/Salary::</h4>
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
         <?php 
            $uriSegment = Request::segment(2);
            if ($uriSegment == 'index') { ?>
             <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
             <script src="{{asset('js/common-scripts.js')}}"></script> 
        <?php } ?>
  
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
                                $('#myModalDeductions .modal-body p').html(data);
				$('#myModalDeductions').modal('show');
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
                                $('#myModalChangePwd .modal-body p').html(data);
				$('#myModalChangePwd').modal('show');
                               
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
        
        
 function set_option(val){
     $('#filterValue').val(' ');   
     $('#StatusDropDown').hide();
     $('#filterValue').show();
     if(val == 'status'){
      $('#filterValue').hide();   
      $('#StatusDropDown').show();
     } else if(val == 'new_member') {
         $('#filterValue').show();   
         $('#filterValue').val('30');   
     }
 }      
     
</script>
@stop