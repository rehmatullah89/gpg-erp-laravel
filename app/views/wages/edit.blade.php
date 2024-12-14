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
                EDIT WAGE RATE  
            
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i>  Wage Information: </i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('wages.updateWageInfo'), 'files'=>true, 'method' => 'post')) }}
                                {{Form::hidden('wage_id',$data[0]->id )}}
                                <section id="no-more-tables" style="padding:10px;">
                                <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <tbody><tr>
                                  <th style="text-align:center; vertical-align:middle; font-weight: bold;">Select Job #*:</th>
                                  <td>
                                  {{ Form::text('JobNumber',$data[0]->job_number, array('class' => 'form-control', 'id' => 'JobNumber', 'required')) }}
                                  </td></tr><tr>
                                  <th style="text-align:center; vertical-align:middle; font-weight: bold;">Select Employee Type*:</th>
                                  <td style="padding-left:2px;">
                                    <table class="table" style="margin-bottom:-10px !important;"><tr>
                                      <td style="padding-left:5px;">
                                      {{Form::select('etype', $emp_types, $data[0]->GPG_employee_type_id, ['id' => 'etype', 'class'=>'form-control l-bot6'])}}
                                      </td>
                                      <td style="padding-left:10px;">
                                      {{ HTML::link('#myModal', 'Add New Employee Type', array('data-toggle'=>'modal','class'=>'btn btn-info'))}}
                                      </td>
                                    </tr></table>
                                  </td></tr><tr>
                                  <th style="text-align:center; vertical-align:middle; font-weight: bold;">County: </th>
                                  <td  style="padding-left:5px;">
                                    <table class="table" style="margin-bottom:-10px !important;"><tr><td style="padding-left:2px;">
                                    {{Form::select('county_name', $county_types,$data[0]->gpg_county_id, ['id' => 'county_name', 'class'=>'form-control m-bot12'])}}
                                      </td>
                                      <td style="padding-left:10px;">
                                        {{ HTML::link('#myModal2', ' Add New Task Type:', array('data-toggle'=>'modal','class'=>'btn btn-info'))}}
                                      </td></tr></table>
                                  </td></tr><tr>
                                  <th style="text-align:center; vertical-align:middle; font-weight: bold;">Task Type:</th><td style="padding-left:5px;">
                                    <table class="table" style="margin-bottom:-10px !important;"><tr><td style="padding-left:2px;">
                                     {{Form::select('task_type',$task_types,$data[0]->gpg_task_type, ['id'=>'task_type', 'class'=>'form-control m-bot12'])}}
                                      </td><td style="padding-left:10px;">
                                        {{ HTML::link('#myModal2', 'Add New Task Type:', array('data-toggle'=>'modal','class'=>'btn btn-info'))}}
                                      </td></tr></table>
                                  </td></tr><tr>
                                  <th style="text-align:center; vertical-align:middle; font-weight: bold;">Status:</th><td >
                                    {{Form::select('status', array('A' => 'Active', 'B' => 'Blocked'), $data[0]->status, ['id' => 'status', 'class'=>'form-control m-bot12'])}}
                                  </td></tr><tr>
                                  <th style="text-align:center; vertical-align:middle; font-weight: bold;">PW wage Type:</th><td>
                                    {{Form::select('wage_type', array('1' => '<$500k', '2' => '>$500k'), $data[0]->wage_type, ['class'=>'form-control m-bot12'])}}
                                  </td></tr><tr><th style="text-align:center; vertical-align:middle; font-weight: bold;">Start Date:</th> 
                                  <td ><input class="form-control form-control-inline input-medium default-date-picker" id="startDate" name="startDate" size="35" type="text" value="<?php echo $data[0]->start_date; ?>" required/></td>
                                  </tr><tr><th style="text-align:center; vertical-align:middle; font-weight: bold;">End Date:</th> 
                                  <td><input class="form-control form-control-inline input-medium default-date-picker" id="endDate" name="endDate" size="35" type="text" value="<?php echo $data[0]->end_date; ?>" required/></td>
                                    </tr>
                                      @foreach($pw_wages_rates_type as $key=>$val)
                                          <tr>
                                            <th style="text-align:center; vertical-align:middle; font-weight: bold;">{{ucwords($val)}}:</th>
                                            <td>
                                               <input class="form-control" name="pw_wages_rate_type_<?=$key ?>" id="pw_wages_rate_type_<?=$key ?>" onChange="javascript:myFunction(this.id)" value="<?php if(isset($rate_row[$key])) echo $rate_row[$key]; ?>" />
                                            </td>
                                          </tr>
                                      @endforeach
                                    <tr><th style="text-align:center; vertical-align:middle; font-weight: bold;">PW Regular: </th>
                                    <td >
                                    {{ Form::text('pwpay1',round($data[0]->pw_reg, 2), array('class' => 'form-control', 'id' => 'pwpay1', 'readonly')) }}
                                    </td></tr>
                                    <tr><th style="text-align:center; vertical-align:middle; font-weight: bold;">PW Overtime:</th>
                                    <td>
                                    {{ Form::text('pwovertime1',round($data[0]->pw_overtime, 2), array('class' => 'form-control', 'id' => 'pwovertime1')) }}
                                    </td></tr>
                                    <tr><th style="text-align:center; vertical-align:middle; font-weight: bold;">PW Double: </th>
                                    <td>
                                    {{ Form::text('pwdouble1',round($data[0]->pw_double, 2), array('class' => 'form-control', 'id' => 'pwdouble1')) }}
                                    </td></tr>
                                    </tbody></table>
                                    <br/>
                                    {{Form::submit("Update Wage Plan", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                                  </section>
                               {{ Form::close() }}
              </section>
             <!-- ////////////////////////////////////////// -->
           
              </section>
              </div>
              </div>
              <!-- page end-->
              <!-- Modal -->
                              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Add New Employee Type</h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group">
                                             {{Form::label('emp_type_name', 'Type Name*:', array('class' => 'control-label col-md-2'))}}
                                              <div class="col-md-6">
                                                {{ Form::text('emp_type_name','', array('class' => 'form-control dpd1', 'id' => 'emp_type_name', 'required')) }}
                                              </div>
                                              </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                           {{Form::submit('Submit', array('class' => 'btn btn-success', 'id'=>'submit_emp_type_name','data-dismiss'=>'modal'))}}
                                           {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
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
                                           {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Add New Task Type</h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group">
                                                {{Form::label('task_type_name', 'Task Type*:', array('class' => 'control-label col-md-2'))}}
                                                <div class="col-md-6">
                                                {{ Form::text('task_type_name','', array('class' => 'form-control dpd1', 'id' => 'task_type_name', 'required')) }}
                                                </div>
                                              </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                            {{Form::submit('Submit', array('class' => 'btn btn-success', 'id'=>'submit_task_type_name','data-dismiss'=>'modal'))}}
                                            {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <!-- modal2 -->                  
       <script>

        function myFunction(id) {
          var split_w = id.split('pw_wages_rate_type_');
            if (split_w[1] == 1){
              var wg_val = document.getElementById('pw_wages_rate_type_1').value;
              if (wg_val != '')
                $('#pwpay1').val(wg_val);
            }
            else{
              if (document.getElementById('pw_wages_rate_type_1').value == '') 
                $('#pwpay1').val('0');
            }
        }
       
       $(document).ready(function(){
         $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
         
         $('#submit_emp_type_name').click(function(){
             if ($('#emp_type_name').val() == '') {
                alert('Employee Type Must not be empty.');
                //$('#myModal2').hide();
            }else{
              var id = '<?php echo $data[0]->id; ?>';
              if (id > 0) {
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
              var id = '<?php echo $data[0]->id; ?>';
              if (id > 0) {
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
          
       });   
      </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
      <script src="{{asset('js/common-scripts.js')}}"></script>
@stop