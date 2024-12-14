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
                 TIMESHEETS CONTROL PANEL [{{$emp_name}}]
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>View/Add Time Sheet</i></b>
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
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
                                      <th style="width:50%"><b>Employee</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr><td data-title="Select Date:">
                                  {{ Form::text('select_date',date('Y-m-d'), array('class' => 'form-control form-control-inline input-medium default-date-picker','id' => 'select_date','style'=>'width:90%;','required')) }}
                                  </td>
                                  <td data-title="Select Employee:">
                                    {{ Form::select('select_emp',$emps_arr,$id, array('class' => 'form-control','id' => 'select_emp','style'=>'width:90%;','readOnly')) }}
                                  </td>
                                  <td>{{Form::submit('Submit', array('class' => 'btn btn-success'))}}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </section>
                          {{ Form::close() }}
              </section>
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">Edit</th>
                            <th style="text-align:center;">Date</th>
                            <th style="text-align:center;">Status</th>
                          </tr>
                        </thead>
                      <tbody>
                        <?php $i=1;?>
                        @foreach($query_data as $row)
                          <tr>
                            <td><?php echo $i++;?></td>
                            <td><a href="{{URL::route('timesheet.edit', array('id'=>$row['id'],'emp_id'=>$row['GPG_employee_Id'],'date'=>$row['date']))}}">
                              {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}</a> 
                            </td>
                            <td>{{date('m/d/Y',strtotime($row['date']))}}</td>
                            <td>{{(!empty($row['ed_lock'])?'Locked':'Editable')}}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
              </section>
                     </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd',
          minDate: new Date()
      });

    </script>
       <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>   
@stop