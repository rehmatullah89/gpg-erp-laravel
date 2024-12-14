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
                 WAGES MANAGEMENT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b>Search by:</b><i> Employee  Join Date / Name Filter </i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('wages.search'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                     <th>
                                      {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                     <th>
                                        {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                      <th><b>Filter</b></th>
                                      <th><b>Filter Value</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr>
                                  <td data-title="Start Date:">
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'start_date')) }}
                                  </td><td data-title="End Date:">
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'end_date')) }}
                                  </td>
                                  <td data-title="Filter:">
                                    {{Form::select('Filter', array(''=>'Select Filter','name' => 'Customer Name', 'emp_type' => 'Employee Type', 'job_number' => 'Job Number', 'contract_number' => 'Contract Number','job_regarding' => 'Job Regarding','county_name' => 'County'), null, ['id' => 'emp_time_chng', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Filter Value:">
                                  {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'filter_value')) }}
                                  </td>
                                    </tr></tbody></table>
                                    <br/>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
                                  </section>
                               {{ Form::close() }}
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
     <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th style="text-align:center;">ID#</th>
                                          <th style="text-align:center;">Job#</th>
                                          <th style="text-align:center;">County</th>
                                          <th style="text-align:center;">Contract#</th>
                                          <th style="text-align:center;">Job Regarding</th>
                                          <th style="text-align:center;">hours</th>
                                          <th style="text-align:center;">Task Type</th>
                                          <th style="text-align:center;">Coustomer Name</th>
                                          <th style="text-align:center;">Location</th>
                                          <th style="text-align:center;">Type</th>
                                          <th style="text-align:center;">PW Reg</th>
                                          <th style="text-align:center;">PW Over time</th>
                                          <th style="text-align:center;">PW Double</th>
                                          <th style="text-align:center;">Start</th>
                                          <th style="text-align:center;">End</th>
                                          <th style="text-align:center;">Type</th>
                                          <th style="text-align:center;">Status</th>
                                          <th style="text-align:center;">Action</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      @foreach($query_data as $data)
                                      <tr>
                                        <td data-title="#ID:">{{ $data['id'] }}</td>
                                        <td data-title="Job#:">{{($data['job_number'] != "")? strtoupper($data['job_number']): "-"}}</td>
                                        <td data-title="County:">{{($data['County_Name'] != "")? ucwords($data['County_Name']): "ALL"}}</td>
                                        <td data-title="Contract:">{{($data['contract_number'] != "")? $data['contract_number'] :"-"}}</td>
                                        <td data-title="Job Regarding:">{{($data['gpg_job_regarding'] != "")? (($data['gpg_job_regarding'] == "~~ALL")? "ALL" : $data['gpg_job_regarding']): "-"}}</td>
                                        <td data-title="hours:">{{($data['prevailing_hours'] != "")? $data['prevailing_hours']: "-"}}</td>
                                        <td data-title="Task Type:">
                                        @if($data['gpg_task_type'] != '') {{ ($task_types[$data['gpg_task_type']] != '0')? $task_types[$data['gpg_task_type']] : "-"}}
                                          @else 
                                            {{"-"}} 
                                        @endif
                                        </td>
                                        <td data-title="Coustomer Name:">{{ $data['customer_name'] }}</td>
                                        <td data-title="Location:">{{ $data['customer_loc'] }}</td>
                                        <td data-title="Type:">{{($data['GPG_employee_type_id'] != "" && isset($emp_types[$data['GPG_employee_type_id']]))? $emp_types[$data['GPG_employee_type_id']]: "-"}}</td>
                                        <td data-title="PW Regular:">${{round($data['pw_reg'], 2)}}</td>
                                        <td data-title="PW Overtime:">${{round($data['pw_overtime'], 2)}}</td>
                                        <td data-title="PW Double:">${{round($data['pw_double'], 2)}}</td>
                                        <td data-title="Start">{{date('m/d/Y',strtotime($data['start_date']))}}</td>
                                        <td data-title="End">{{date('m/d/Y',strtotime($data['end_date']))}}</td>
                                        <td data-title="Type:">{{($data['wage_type']==1?'<$500k':'>$500k')}}</td>
                                        <td data-title="Status">{{($data['status'] == "A") ? 'Active' : "In-Active"}}</td>
                                        <td data-title="Action:">
                                        <a data-toggle="modal" style="display:inline;" href="{{URL::route('wages.edit', array('id'=>$data['id']))}}">
                                        {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}} 
                                        </a>                                        
                                        {{ Form::open(array('method' => 'DELETE', 'id'=>'myForm'.$data['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('wages.destroy', $data['id']))) }}
                                          {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$data['id'].'").submit()')) }}
                                        {{ Form::close() }}
                                        </td>
                                      </tr>
                                      @endforeach
                                      </tbody>
                                  </table>
                                  {{ $query_data->links() }}
              </section>
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
       <script>
       $(document).ready(function(){
         

            $('#reset_search_form').click(function(){
              $('#start_date').val("");
              $('#end_date').val("");
              $('#emp_time_chng').val("none");
              $('#filter_value').val(""); 
            });

           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });

       });   
      </script>
@stop