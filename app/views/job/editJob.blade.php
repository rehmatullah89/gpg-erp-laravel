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
               UPDATE JOB
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                        <i>  Edit job's Information here! </i>
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
                            {{ Form::open(array('before' => 'csrf','id'=>'update_job_form','route' => array('job/updateThisJob', 'id='.$jobObj->id), 'files'=>true, 'method' => 'post')) }}
                            <section id="no-more-tables" style="padding:10px;">
                                <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <tbody>
                                    <tr>
                                      <th>Select Job Cat.*:</th>
                                      <td>{{Form::select('jobCat',$job_type_arr,$jobObj->GPG_job_type_id, ['id'=>'jobCat', 'class'=>'form-control m-bot12','style'=>'width:50%; display:inline;'])}}&nbsp;&nbsp;{{ HTML::link('#myModal', 'Add New Job Category', array('data-toggle'=>'modal','class'=>'btn btn-info','style'=>'display:inline;'))}}</td>
                                    </tr>  
                                    <tr>
                                      <th>Select Job Plan.*:</th>
                                      <td>{{Form::select('jobPlan',array(''=>'Select Job Plan','1' => '$12/hour [Regular Plan]','2' => '$15/hour [Govt. Job Plan]'),$jobObj->GPG_wage_plan_id, ['id'=>'jobPlan', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr> 
                                    <tr>
                                      <th>Select Customer.*:</th>
                                      <td>{{Form::select('customer',$customer_arr,$jobObj->GPG_customer_id, ['id'=>'customer', 'class'=>'form-control m-bot12','style'=>'width:50%; display:inline;'])}}&nbsp;&nbsp;{{ HTML::link('customers/create', 'Add New Customer', array('class'=>'btn btn-info','style'=>'display:inline;'))}}</td>
                                    </tr>  
                                    <tr>
                                      <th>Add Job Number.*:</th>
                                      <td>{{Form::text('job_num',$jobObj->job_num, ['id'=>'job_num', 'class'=>'form-control m-bot12'])}}</td>
                                      <input type="hidden" name="old_job_num" value="{{$jobObj->job_num}}">
                                    </tr>  
                                    <tr>
                                      <th>Assign to:</th>
                                      <td>{{Form::select('assignTo',$employee_arr,$jobObj->GPG_employee_id, ['id'=>'assignTo', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr>  
                                    <tr>
                                      <th>Set Priority:</th>
                                      <td>{{Form::select('priority',array(''=>'Set Priority','LOW' => 'LOW','MEDIUM' => 'MEDIUM','HIGH'=>'HIGH'),$jobObj->priority, ['id'=>'priority', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr> 
                                     <tr>
                                      <th>Location:</th>
                                      <td>{{Form::text('location',$jobObj->location, ['id'=>'location', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr>  
                                    <tr>
                                      <th>Generator Size:</th>
                                      <td>{{Form::text('genSize',$jobObj->generator_size, ['id'=>'genSize', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr> 
                                     <tr>
                                      <th>Task:</th>
                                      <td>{{ Form::textarea('task',$jobObj->task,['id'=>'task','class'=>'form-control']) }}</td>
                                    </tr> 
                                    <tr>
                                      <th>Sub Task:</th>
                                      <td>{{ Form::textarea('taskSub',$jobObj->sub_task,['id'=>'taskSub','class'=>'form-control']) }}</td>
                                    </tr>  
                                    </tbody>
                                    </table><br/>
                                    {{Form::button("Update Job", array('id'=>'submit_update_frm','class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
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
                                              <h4 class="modal-title">ADD NEW JOB CATEGORY</h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group">
                                             {{Form::label('cname', 'Job Category Name*:', array('class' => 'control-label col-md-2'))}}
                                              <div class="col-md-6">
                                                {{ Form::text('cname','', array('class' => 'form-control dpd1', 'id' => 'cname', 'required')) }}
                                              </div>
                                              </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                           {{Form::submit('Submit', array('class' => 'btn btn-success', 'id'=>'submit_job_category_name','data-dismiss'=>'modal'))}}
                                           {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <!-- modal -->
<script type="text/javascript">
      $('#submit_job_category_name').click(function(){
        var cname = $('#cname').val();
        if(cname == ''){
          alert('Please fill info, Job Category Name is Required!');  
        }else{
          $.ajax({
            url: "{{URL('ajax/addJobCategory')}}",
              data: {
               'name' : cname
              },
            success: function (data) {
              if(data == 1){
                alert('Successfully New Job Category Created!');
                location.reload();
              }
            },
          });
        }
      });

   $('#submit_update_frm').click(function(){
    $('#update_job_form').submit();
   });   
</script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>
@stop