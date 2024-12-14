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
               UPDATE TASK 
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Enter required* Inoformation! </i></b>
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
              </section>
             <!-- ////////////////////////////////////////// -->
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('task.update',$row['id']), 'files'=>true, 'method' => 'put')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <tbody>
                          <tr>
                          <th style="text-align:center;">Select Department*:<br/><sub>Note: Optional for own tasks</sub></th>
                          <td>{{ Form::select('gpg_department_id',array(''=>'Select Department')+$depts,$row['gpg_department_id'], array('class' => 'form-control dpd1', 'id' => 'gpg_department_id', 'required')) }}</td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Select Customer:</th>
                          <td>{{ Form::select('customer',array(''=>'Select Cusotomer')+$customers,$row['gpg_customer_id'], array('class' => 'form-control dpd1', 'id' => 'customer')) }}</td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Task Type:</th>
                          <td>{{ Form::select('tasktype',array('Onetime'=>'One Time','Ongoing'=>'On Going'),$row['task_type'], array('class' => 'form-control dpd1', 'id' => 'tasktype','onChange'=>"check()")) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Recurring:</th>
                            <td>{{ Form::select('recuring',array('Daily'=>'Daily','Weekly'=>'Weekly','Monthly'=>'Monthly'),$row['recuring'], array('class' => 'form-control dpd1', 'id' => 'recuring','disabled')) }}</td>
                          </tr> 
                          <tr>
                            <th style="text-align:center;">Priority:</th>
                            <td>{{ Form::select('priority',array('High'=>'High','Medium'=>'Medium','Low'=>'Low'),$row['task_priority'], array('class' => 'form-control dpd1', 'id' => 'priority')) }}</td>
                          </tr> 
                          <tr>
                            <th style="text-align:center;" class="type_ksize">Expected End Date</th>
                            <td>{{ Form::text('endDate',$row['expected_end_date'], array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'endDate')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Own Task:</th>
                            <td>{{ Form::checkbox('ownedTask', 1,1, ['class' => 'form-control','id'=>'ownedTask']) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Job Number:</th>
                            <td>{{ Form::text('POjobNum',$row['job_num'], array('class' => 'form-control dpd1', 'id' => 'POjobNum')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Task Details*:</th>
                            <td>{{ Form::textarea('task_detail',$row['task_detail'], array('class' => 'form-control dpd1', 'id' => 'task_detail','required')) }}</td>
                          </tr>
                      </tbody>
                  </table>
                    {{ Form::submit('Update Task', array('class' => 'btn btn-success')) }}
              </section>
              </div>
              </div>
              {{ Form::close() }}
              </section>
              </div>
              </div>
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
      });
  
  $('#POjobNum').focus(function() {  
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
  });
  function check(){
    if(document.getElementById('tasktype').value=='Onetime'){
      document.getElementById('recuring').disabled=true;
      document.getElementById('endDate').disabled=false;
    }
    else{
      document.getElementById('recuring').disabled=false;
      document.getElementById('endDate').disabled=true;
    }
  }
</script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>
@stop