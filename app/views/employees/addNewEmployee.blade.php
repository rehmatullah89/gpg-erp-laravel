@extends("layouts/dashboard_master")
@section('content')
 
@stop
@section('dashboard_panels')
    <!-- page start--> 
<?php
    $action = "add";  
    $breadCrumb = "ADD NEW EMPLOYEE";
    if(isset($GpgEmployeeData) && !empty($GpgEmployeeData)){
         $action = "update";
         $breadCrumb = "UPDATE EMPLOYEE";
    }
?>
    
    <div class="row">
      <div class="col-sm-12">
    <section class="panel">
    <header class="panel-heading">
     
        {{$breadCrumb}}
      
    </header>
    <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
    <section class="panel">
        <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
            <i>  Employee Information: </i>
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
            
        @if($action == 'add')
            {{ Form::open(array('before' => 'csrf' ,'url'=>route('employees/addNewEmployee'), 'id'=>'employeeForm', 'files'=>true, 'method' => 'post')) }}
            
        @elseif($action == 'update')
            {{ Form::open(array('before' => 'csrf' ,'url'=>URL::route('employees/editEmployee', array('id'=>$GpgEmployeeData->id)), 'id'=>'employeeForm', 'files'=>true, 'method' => 'post')) }}
            <input type="hidden" name="db_uname" value="{{@$GpgEmployeeData->login}}">
        @endif
        <section id="no-more-tables" style="padding:10px;">
          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
            <tbody>
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Login Name*:</th>
                      <td>
                          {{ Form::text('uname',@$GpgEmployeeData->login, array('class' => 'form-control', 'id' => 'uname', 'required','onkeyUp'=>'populateLogin(this.value)')) }} e.g. john
                      </td>
                  </tr>
                  <?php $required =  (($action == 'update')? "": 'required' );
                  $rule =  (($action == 'd')? "": 'data-rule-equalto="#pass"' );
                  
                  ?>
                  
                  <tr <?php echo (($action == 'update')? 'style="display:none"': ''); ?> id="tr_pass">
                    <th style="text-align:center; vertical-align:middle; font-weight: bold;">Password:*</th>
                    <td>
                        
                        {{ Form::password('pass', array('class' => 'form-control','id'=>'pass',$required)) }}
                    </td>
                  </tr>
                  <tr <?php echo (($action == 'update')? 'style= "display:none"': ''); ?> id="tr_re_pass">
                    <th style="text-align:center; vertical-align:middle; font-weight: bold;">Re-enter Password:*</th>
                    <td>
                        {{ Form::password('repass', array('class' => 'form-control','id'=>'repass','equalTo' => '#pass',$required)) }}<br>

                    </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Select Employee Type:</th>
                      <td style="padding-left:2px;">
                          {{Form::select('etype', $employeeTypesList, @$GpgEmployeeData->GPG_employee_type_id, ['id' => 'etype', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Select Accural Rate:</th>
                      <td style="padding-left:2px;">
                          {{Form::select('accRate', array('A' => 'Active','B' => 'Blocked'), @$GpgEmployeeData->accural_rate, ['id' => 'accRate', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Employee Front End Access:</th>
                      <td style="padding-left:2px;">
                          {{Form::select('eFront[]', array('timesheet' => 'Time Sheet User','sales' => 'Sales Tracking User','po' => 'Purchase Order User'), @$GpgEmployeeData->frontend, ['id' => 'eFront','multiple' => 'multiple', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>
                  
                  
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Real Name:</th>
                      <td>
                          {{ Form::text('realname',@$GpgEmployeeData->name, array('class' => 'form-control', 'id' => 'realname', '')) }}
                      </td>
                  </tr>       
                        
                        
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Email Address:</th>
                      <td>
                          {{ Form::text('email',@$GpgEmployeeData->email, array('class' => 'form-control', 'id' => 'email')) }}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Email Password:</th>
                      <td>
                           {{ Form::password('email_pwd', array('class' => 'form-control','id'=>'email_pwd')) }}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Phone#:</th>
                      <td>
                          {{ Form::text('phone',@$GpgEmployeeData->phone, array('class' => 'form-control', 'id' => 'phone', '')) }}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Date Of Birth:</th>
                      <td>
                          {{ Form::text('DOB','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'DOB')) }}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Location:</th>
                      <td>
                          {{Form::select('loc_name', array('' => 'Select Location','3' => 'Anaheim','2' => 'El Centro','1' => 'San Diego'), @$GpgEmployeeData->gpg_employee_location_id, ['id' => 'loc_name', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>
                  
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Member's Status:</th>
                      <td>
                          {{Form::select('status', array('A' => 'Active','B' => 'Blocked'), @$GpgEmployeeData->status, ['id' => 'status', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Hire Date:</th>
                      <td>
                          {{ Form::text('hireDate',@$GpgEmployeeData->hire_date, array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'hireDate')) }}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Starting Salary:</th>
                      <td>
                          {{ Form::text('starting_salary',@$GpgEmployeeData->starting_salary, array('class' => 'form-control form-control-inline input-medium', 'id' => 'starting_salary')) }}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Minimum Salary:</th>
                      <td>
                          {{ Form::text('minimum_salary',@$GpgEmployeeData->minimum_salary, array('class' => 'form-control form-control-inline input-medium', 'id' => 'minimum_salary')) }}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Higher Salary:</th>
                      <td>
                          {{ Form::text('higher_salary',@$GpgEmployeeData->higher_salary, array('class' => 'form-control form-control-inline input-medium', 'id' => 'higher_salary')) }}
                      </td>
                  </tr>
                  
                   <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Starting Salary Type:   :</th>
                      <td>
                          {{Form::select('st_sal_type', array('s' => 'Salaried','h' => 'Hourly Rate'), @$GpgEmployeeData->start_salary_type, ['id' => 'st_sal_type', 'class'=>'form-control l-bot6'])}}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Member's Photo:</th>
                      <td>
                          {{ Form::file('photo','', array('id' => 'photo')) }}
                      </td>
                  </tr>
                  
                  <tr>
                      <th style="text-align:center; vertical-align:middle; font-weight: bold;">Check if Salaried Employee:  :</th>
                      <td>
                          <input type="checkbox" name="salaried" id="salaried" value="1" {{ (@$GpgEmployeeData->salaried == 0)? 'checked="chekced"': ''}}>
                      </td>
                  </tr>

                  <tr>
                      <td>&nbsp;</td>   
                      <td >
                          <br><br>
                          @if($action == 'add')
                            {{Form::submit("Create Employee", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                          @else
                            {{Form::submit("Update Employee", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                          @endif  
                      </td>
                  </tr>

              <br/>

            </section>
        {{ Form::close() }}
    </section>
<!-- ////////////////////////////////////////// -->
 </section>
 </div>
 </div>
 <!-- page end-->


      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
      <script src="{{asset('js/common-scripts.js')}}"></script>
      <script src="{{asset('js/jquery.validate.js')}}"></script>
      
      <script type="text/javascript">
            $( document ).ready(function() {
                $('.default-date-picker').datepicker({
                    format: 'yyyy-mm-dd'
                 });
                 
//                 $('#employeeForm').validate({
//                rules: {
//                    email: 'required',
//                    emailConfirm: {
//                        equalTo: '#email'
//                    }
//                }
//});
            });
   
   
          function toggleLogin(obj) {
            if (obj.value=='C'){
               $('#loginFrm').show();
               
               $('#pass').attr('required','required');
               $('#repass').attr('required','required');
               $('#repass').attr('equalTo','#pass'); 
               
               loginVal = $('#name').val();
               populateLogin(loginVal);
               
            } else{
                
                $('#loginFrm').hide();
                $('#pass').removeAttr('required');
                $('#repass').removeAttr('required');
                $('#repass').removeAttr('equalTo'); 
                $('#login').val('');
            } 
                
            //else DG('loginFrm').style.display = 'none';
            
	}
        function populateLogin(strVal){
            var login = strVal.toLowerCase();
            login = login.replace(" ","");
            login = login.replace('"',"");
            login = login.replace('/',"");
            login = login.replace('$',"");
            login = login.replace('.',"");
            login = login.replace('%',"");
            
            $('#login').val(login );
        }
        
        function chnagePasswordToggle(){
            
            if ($('#defaultpwd').is(':checked')){
               
                $('#tr_pass').show();
                $('#tr_re_pass').show();
                $('#pass').attr('required','required');
                $('#repass').attr('required','required');
                $('#repass').attr('equalTo','#pass'); 
               
            } else {
                $('#tr_pass').hide();
                $('#tr_re_pass').hide();
                $('#pass').removeAttr('required');
                $('#repass').removeAttr('required');
                $('#repass').removeAttr('equalTo');   
            } 
        }
        
      </script>    
@stop