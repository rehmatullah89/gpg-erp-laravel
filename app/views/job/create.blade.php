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
               ADD A NEW JOB
            
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                        <i>  Add New job's Information: </i>
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
                            {{ Form::open(array('before' => 'csrf' ,'url'=>route('quote.store'), 'files'=>true, 'method' => 'post')) }}
                            <section id="no-more-tables" style="padding:10px;">
                                <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <tbody>
                                    <tr>
                                      <th>Select Job Cat.*:</th>
                                      <td>{{Form::select('jobCat',$job_type_arr,'', ['id'=>'jobCat', 'class'=>'form-control m-bot12','style'=>'width:50%; display:inline;'])}}&nbsp;&nbsp;{{ HTML::link('#myModal', 'Add New Job Category', array('data-toggle'=>'modal','class'=>'btn btn-info','style'=>'display:inline;'))}}</td>
                                    </tr>  
                                    <tr>
                                      <th>Select Job Plan.*:</th>
                                      <td>{{Form::select('jobPlan',array(''=>'Select Job Plan','1' => '$12/hour [Regular Plan]','2' => '$15/hour [Govt. Job Plan]'),'', ['id'=>'jobPlan', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr> 
                                    <tr>
                                      <th>Select Customer.*:</th>
                                      <td>{{Form::select('customer',$customer_arr,'', ['id'=>'customer', 'class'=>'form-control m-bot12','style'=>'width:50%; display:inline;'])}}&nbsp;&nbsp;{{ HTML::link('customers/create', 'Add New Customer', array('class'=>'btn btn-info','style'=>'display:inline;'))}}</td>
                                    </tr>  
                                    <tr>
                                      <th>Add Job Number.*:</th>
                                      <td>{{Form::text('job_num','', ['id'=>'job_num', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr>  
                                    <tr>
                                      <th>Assign to:</th>
                                      <td>{{Form::select('assignTo',$employee_arr,'', ['id'=>'assignTo', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr>  
                                    <tr>
                                      <th>Set Priority:</th>
                                      <td>{{Form::select('priority',array(''=>'Set Priority','LOW' => 'LOW','MEDIUM' => 'MEDIUM','HIGH'=>'HIGH'),'MEDIUM', ['id'=>'priority', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr> 
                                     <tr>
                                      <th>Location:</th>
                                      <td>{{Form::text('location','', ['id'=>'location', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr>  
                                    <tr>
                                      <th>Generator Size:</th>
                                      <td>{{Form::text('genSize','', ['id'=>'genSize', 'class'=>'form-control m-bot12'])}}</td>
                                    </tr> 
                                     <tr>
                                      <th>Task:</th>
                                      <td>{{ Form::textarea('task','',['id'=>'task','class'=>'form-control']) }}</td>
                                    </tr> 
                                    <tr>
                                      <th>Sub Task:</th>
                                      <td>{{ Form::textarea('taskSub','',['id'=>'taskSub','class'=>'form-control']) }}</td>
                                    </tr>  
                                    </tbody>
                                    </table><br/>
                                    {{Form::submit("Create Job", array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                                  </section>
                            {{ Form::close() }}
                            </section>
             <!-- ////////////////////////////////////////// -->
           
              </section>
              </div>
              </div>
              <!-- page end-->
              <!-- Modal -->
                              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:100%;">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">ADD NEW JOB CATEGORY</h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group">
                                              <div class="col-md-3">
                                                {{Form::label('cname', 'Job Category Name*:', array('class' => 'control-label col-md-2'))}}
                                              </div>
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
          return false;
        }else{
          $.ajax({
            url: "{{URL('ajax/addJobCategory')}}",
              data: {
               'name' : cname
              },
            success: function (data) {
              if(data == 1){
                alert('Successfully New Job Category Created!');
                $('#cname').val('');
                location.reload();
              }else
                alert('Validation Error! Please Provide valid value.');
            },
          });
        }
      });
</script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>
@stop