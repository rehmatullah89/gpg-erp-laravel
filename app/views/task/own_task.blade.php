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
                  OWN TASK MANAGMENT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Start Date/ End Date/ Filter</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('task/own_task'), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <thead>
                              <tr>
                                <th>
                                   {{Form::label('expectedSDate', 'Expected E.Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                </th>
                                <th>
                                  {{Form::label('expectedEDate', 'Expected E.Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('searchEstimate', 'Days From Estimate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('taskcompleteddetails', 'Task Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  Actions
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                  <td data-title="Start Date:">
                                    {{ Form::text('expectedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'expectedSDate')) }}
                                  </td>
                                  <td data-title="End Date:">
                                    {{ Form::text('expectedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'expectedEDate')) }}
                                  </td>
                                   <td data-title="searchEstimate">
                                    {{ Form::text('searchEstimate','', array('class' => 'form-control', 'id' => 'searchEstimate')) }}
                                  </td>
                                  </td>
                                  <td data-title="Task Status">
                                    {{ Form::select('taskcompleteddetails',array(''=>'All Tasks','completed'=>'Completed','open'=>'Open'),'', array('class' => 'form-control', 'id' => 'taskcompleteddetails')) }}
                                  </td>
                                  <td>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                </td>
                                </tr>
                              </tbody>
                          </table>
                      </section>
                  {{ Form::close() }}
              </section>
              </section>
              </div>
              <div class="row">
              <div class="col-sm-12">
              <section class="panel">
              <div class="panel-body">
              <section id="no-more-tables" >
              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>#</th>
                  <th>Customer</th>
                  <th>Ongoing/ 1-Time </th>
                  <th>Start Date </th>
                  <th>Expec. E.Date </th>
                  <th>Days From Est.</th>
                  <th>Date Completed</th>
                  <th>Comp. Task Notes</th>
                  <th>Job #</th>
                  <th>Task Details</th>
                  <th>Comp. In</th>
                  <th>Notes</th>
                  <th>Action</th>
              </tr>
              </thead>
              <tbody class="cf">
               @foreach($query_data as $row)
                <tr>
                  <td>{{$row['task_id']}}</td>
                  <td>{{$row['cus_name']}}</td>
                  <td><?php echo $row['task_type'].($row['task_type']=='Ongoing'?' (<b>'.$row['recuring'].'</b>)':'') ?></td>
                  <td>{{date('m/d/Y',strtotime($row['start_date']))}}</td>
                  <td><?php if($row['expected_end_date']!='')  { echo date('m/d/Y',strtotime($row['expected_end_date']));} ?></td>
                  <td><?php      
                    if(empty($row['completion_date'])){
                      if($row['now_diff']>=0){
                        echo "<strong><span style=\"color:#669966\">".$row['now_diff']."</strong></span>";
                      }else{
                        echo "<strong><span style=\"color:#FF0000\">".$row['now_diff']."</strong></span>";
                      }
                    }else{       
                     if($row['comp_diff']>=0){
                      echo "<strong><span style=\"color:#669966\">".$row['comp_diff']."</strong></span>";
                     }
                     else{
                      echo "<strong><span style=\"color:#FF0000\">".$row['now_diff']."</strong></span>";
                    } } ?>
                  </td>
                  <td><?php if (!empty($row['completion_date'])) echo date('m/d/Y',strtotime($row['completion_date'])) ?></td>
                  <td>{{substr($row['completion_note'],0,20).'...'}}</td>
                  <td>{{$row['job_num']}}</td>
                  <td>{{substr($row['task_detail'],0,20).'...'}}</td>
                  <td><?php if($row['completion_date']!=''){ echo $row['comp_date']+1; } ?></td>
                  <td>{{substr($row['task_note'],0,20).'...'}}</td>
                  <td>
                    <a href="{{URL::route('task.edit', array('id'=>$row['id']))}}" style="display:inline;">
                      {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                      </a> 
                      {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('task/deleteOwnedTask', $row['id'])))}}
                      {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}
                      {{ Form::close() }}<br/>
                  </td>
                </tr>
               @endforeach
              </tbody>
              </table>
             <!-- links here -->
            </section>
            </div>  
          </section>
        </div>
        </div>      
      </div>
       
      <!-- page end-->
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

      $('#reset_search_form').click(function(){
          $('#expectedSDate').val("");
          $('#expectedEDate').val("");
          $('#searchEstimate').val("");
          $('#taskcompleteddetails').val("");
      });
  
</script>
<script src="{{asset('js/jquery.nicescroll.js') }}"></script>
<script src="{{asset('js/common-scripts.js') }}"></script>
@stop