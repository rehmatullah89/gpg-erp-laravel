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
                  TIME SHEET MANAGEMENT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                  <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                    <i>( Search / Lock )</i> Time Sheet
                  </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('timesheet.search'), 'files'=>true, 'method' => 'post')) }}
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
                                      <th><b>Lock Timesheet</b></th>
                                      <th><b>Filter</b></th>
                                      <th><b>Filter Value</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr><td data-title="Start Date:">
                                  {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'start_date')) }}
                                 </td><td data-title="End Date:">
                                  {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'end_date')) }}
                                 </td>
                                   <td  data-title="Lock Time Sheet:" style='text-align:center; vertical-align:middle;'>
                                   {{ Form::checkbox('lock_time_sheet','') }}
                                   </td>
                                    <td data-title="Filter:">
                                   <div>
                                    {{Form::select('filter_val', array('none' =>'--None--','emp_name' =>'Employee Name','ed_lock' =>'Timesheet Lock Status' ), null, ['id'=>'emp_time_chng','class'=>'form-control m-bot15'])}}
                                    </div>
                                    </td>
                                    <td data-title="Filter Value:"><div id="filter_change">
                                    </div></td>
                                    </tr></tbody></table>
                                    <br/>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info','style'=>'margin-top:-15px;'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;','id'=>'reset_search_form'))}}
                                  </section>
                               {{ Form::close() }}
              </section>
             <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              Add New Time Sheet
                          </header>
                           {{ Form::open(array('before' => 'csrf' ,'url'=>route('timesheet.create'),  'method' => 'get')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                      <th style="width:50%">
                                      {{Form::label('select_date', 'Select Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                      <th style="width:50%"><b>Select Employee</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr><td data-title="Select Date:">
                                  {{ Form::text('select_date','', array('class' => 'form-control form-control-inline input-medium default-date-picker','id' => 'select_date','style'=>'width:90%;','required')) }}
                                 </td>
                                    <td data-title="Select Employee:">
                                   <div> {{$emp_select}}
                                    </div>
                                    </td>
                                    </tr></tbody></table>
                                    <br/>
                                   {{Form::submit('Submit', array('class' => 'btn btn-info','style'=>'margin-top:-15px;'))}}
                                   {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;','id'=>'reset_addtimesheet_form'))}}
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
                                          <th style="text-align:center;">#ID</th>
                                          <th style="text-align:center;">Date</th>
                                          <th style="text-align:center;">User</th>
                                          <th style="text-align:center;">Locked for Editing</th>
                                          <th style="text-align:center;">Action</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      @foreach($query_data as $data)
                                      <tr>
                                        <td data-title="#ID">{{ $data->tid }}</td>
                                        <td data-title="Date">{{date('m/d/Y',strtotime($data->date))}}</td>
                                        <td data-title="User">{{ $data->name }}</td>
                                        <td data-title="Locked for Editing">
                                         @if (!empty($data->ed_lock) && $data->ed_lock==1)
                                             {{ HTML::linkAction('checkEditable', 'Un-Editable', array('id'=>$data->tid,'lock'=>'0','page'=>Input::get('page'))) }}
                                         @else
                                              {{ HTML::linkAction('checkEditable', 'Editable', array('id'=>$data->tid,'lock'=>'1','page'=>Input::get('page'))) }}
                                         @endif
                                        <td data-title="Action">
                                        <a href="{{URL::route('timesheet.show', array('id'=>$data->tid,'emp_id'=>$data->eid,'date'=>$data->date))}}">
                                        {{Form::button('<i class="fa fa-check"></i>', array('class' => 'btn btn-success btn-xs'))}}
                                        </a>
                                        <a href="{{URL::route('timesheet.edit', array('id'=>$data->tid,'emp_id'=>$data->eid,'date'=>$data->date))}}">
                                        {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                                        </a> 
                                        <a href="{{URL::route('timesheet/emp_hist', array('id'=>$data->eid))}}">
                                        {{Form::button('<i class="fa fa-history"></i>', array('target'=>'_blank','class' => 'btn btn-warning btn-xs'))}}
                                        </a>
                                        {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$data->tid.'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('timesheet.destroy', $data->tid))) }}
                                        {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$data->tid.'").submit()')) }}
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
        $('div#filter_change').html("<input type='text' name='FVal' class='form-control'>"); 
       $(document).ready(function(){
           
            $('#reset_addtimesheet_form').click(function(){
              $('#select_date').val("");
              $('#select_emp').val("");
            });

            $('#reset_search_form').click(function(){
              $('#start_date').val("");
              $('#end_date').val("");
              $('#emp_time_chng').val("none");
              $('div#filter_change').html("<input type='text' name='FVal' value='' class='form-control'>"); 
            });
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
            $("select#emp_time_chng").change(function() { 

              if( $(this).find('option:selected').val() == 'ed_lock')
                  $('div#filter_change').html("<select name='ed_lock_check' class='form-control m-bot15'><option value='1'>Locked Timesheets</option><option value='0'>Editable Timesheets</option></select>"); 
                              
              if( $(this).find('option:selected').val() == 'none')
                  $('div#filter_change').html("<input type='text' name='FVal' class='form-control'>"); 
             
              if( $(this).find('option:selected').val() == 'emp_name')
                  $('div#filter_change').html("<input type='text' name='FVal' class='form-control'>"); 
            });   
       });   
      </script>
@stop