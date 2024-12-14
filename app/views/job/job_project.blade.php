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
                PROJECT MANAGEMENT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Start Date/ End Date & Filters</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/job_project'), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <thead>
                              <tr>
                                <th>
                                   {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                </th>
                                <th>
                                  {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('Filter', 'Filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('FVal', 'Filter Value:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('ProStatus', 'Project Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  Actions
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                  <td data-title="Start Date:">
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td>
                                  <td data-title="End Date:">
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Filter:">
                                    {{Form::select('Filter', array(''=>'Select Filter','concat(pcode_1,'.',pcode_2,'.',pcode_3)'=>'Project ID','title'=>'Project Title','days'=>'Days','GPG_job_num'=>'Job Number'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                   <td data-title="Filter Value:" id="filter_val">
                                    {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'FVal')) }}
                                  </td>
                                  <td data-title="ProStatus:">
                                    {{Form::select('ProStatus', array('all'=>'All','0'=>'Open','1'=>'Complete'), null, ['id' => 'ProStatus', 'class'=>'form-control m-bot15'])}}
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
              <?php
                $preJobNum='';
                $rowCount=0;
                $rowChk = 0;
                $row_span="";
                ?>
                <section id="no-more-tables" >
                @foreach($query_data as $projectRow)
                  <table class="table table-bordered table-striped table-condensed cf">
                   <?php
                      $rowCount++;
                      if($preJobNum!=$projectRow['job_num'])
                      {
                   ?>
                   <thead>
                    <tr>
                      <th colspan="15"><strong style="font-size:16px;"><?php echo $projectRow['pcode_1'].'.'.$projectRow['pcode_2']; ?>&nbsp;<?php echo $projectRow['project_title']; ?>&nbsp;[<?php echo $projectRow['GPG_job_num']; ?>]</strong>&nbsp;&nbsp;&nbsp;{{HTML::link('#myModal6', 'Create New Task' , array('class' => 'btn btn-danger btn-xs','data-toggle'=>'modal','pro_title'=>$projectRow['project_title'],'job_id'=>$projectRow['GPG_job_id'],'job_num'=>$projectRow['GPG_job_num'],'name'=>'create_new_task'))}}</th>
                    </tr>
                    <tr>
                      <th style="width:6.6%;">Action</th>
                      <th style="width:6.6%;">Comp.</th>
                      <th style="width:6.6%;">Comp. Date</th>
                      <th style="width:6.6%;">ID</th>
                      <th style="width:6.6%;">Task Title</th>
                      <th style="width:6.6%;">Technician</th>
                      <th style="width:6.6%;">Parent Task</th>
                      <th style="width:6.6%;">Subcontractor</th>
                      <th style="width:6.6%;">Task Detail</th>
                      <th style="width:6.6%;">Include Days (Sat/Sun)</th>
                      <th style="width:6.6%;">Days</th>
                      <th style="width:6.6%;">Start</th>
                      <th style="width:6.6%;">End</th>
                      <th style="width:6.6%;">Resource Forcasted</th>
                      <th style="width:6.6%;">Resource Used </th>
                    </tr>
                   </thead>
                     <?php 
                        $rowChk = 1;
                      }
                     ?>
                    <tbody class="cf">
                      <tr>
                        <?php
                          $owner_str = "";
                          if(isset($projectRow['GPG_employee_id']) && !empty($projectRow['GPG_employee_id']) && $projectRow['GPG_employee_id']!=',')
                          {
                            $owner_name = @DB::select(DB::raw("SELECT name FROM gpg_employee WHERE id IN (".rtrim($projectRow['GPG_employee_id'],',').") order by name"));
                            if(count($owner_name)>0)
                            {
                              foreach ($owner_name as $key => $owner_obj)
                              {
                                $owner_str .= $owner_obj->name.", ";
                              }
                              $owner_str = substr($owner_str,0,strlen($owner_str)-2);
                            }
                          }
                        ?>
                        <td style="width:6.6%;">
                          {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$projectRow['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('job/deleteJobProjTasks', $projectRow['id']))) }}
                          {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$projectRow['id'].'").submit()')) }}
                          {{ Form::close() }}
                          <a href="#myModal7" class='btn btn-primary btn-xs' data-toggle='modal' job_id = "{{$projectRow['GPG_job_id']}}" job_num ="{{$projectRow['GPG_job_num']}}" task_id_for_job="{{$projectRow['id']}}" proj_title="{{$projectRow['project_title']}}" name='task_id_for_update_job_modal'><i class="fa fa-pencil-square-o"></i></a>
                        </td>
                        <td style="width:6.6%;"><?php if($projectRow['completed']==1) echo "Yes"; else echo "No"; ?></td>
                        <td style="width:6.6%;"><?php echo (!empty($projectRow['completed_date'])?date('m/d/Y',strtotime($projectRow['completed_date'])):'-'); ?></td>
                        <td style="width:6.6%;">{{HTML::link('#myModal9', $projectRow['pcode_1'].'.'.$projectRow['pcode_2'].'.'.$projectRow['pcode_3'] , array('class' => 'btn btn-link btn-xs','data-toggle'=>'modal','id_for_job_task'=>$projectRow['id'],'job_id'=>$projectRow['GPG_job_id'],'job_num'=>$projectRow['GPG_job_num'],'name'=>'show_tasks_of_project'))}}</td>
                        <td style="width:6.6%;" title="{{$projectRow['title']}}"><?php echo @$projectRow['project_activity_id']."-".substr($projectRow['title'],0,20).'..'; ?></td>
                        <td style="width:6.6%;">{{!empty($owner_str)?$owner_str:'-'}}</td>
                        <td style="width:6.6%;">{{isset($projectRow['parentTask'])?$projectRow['parentTask']:'-'}}</td>
                        <td style="width:6.6%;">{{!empty($projectRow['subcontractor'])?$projectRow['subcontractor']:'-'}}</td>
                        <td style="width:6.6%;" title="{{$projectRow['notes']}}">{{substr($projectRow['notes'],0,15).'...'}}</td>
                        <td style="width:6.6%;"><?php if($projectRow['include_days']==1) echo "Yes"; else echo "No"; ?></td>
                        <td style="width:6.6%;">{{$projectRow['days']}}</td>
                        <td style="width:6.6%;"><?php echo ($projectRow['start_date']=='0000-00-00')?'-':date('m/d/Y',strtotime($projectRow['start_date'])); ?></td>
                        <td style="width:6.6%;"><?php echo ($projectRow['end_date']=='0000-00-00')?'-':date('m/d/Y',strtotime($projectRow['end_date'])); ?></td>
                        <td style="width:6.6%;">{{number_format($projectRow['resource_hours'],2)}}</td>
                        <td style="width:6.6%;">{{number_format($projectRow['timesheet'],2)}}</td>
                      </tr>
                    </tbody>
                    <?php $preJobNum=$projectRow['job_num']; ?>
                  </table>
               @endforeach
              </section>
              {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
            </div>  
          </section>
        </div>
        </div>      
      </div>
   <!-- Modal#7 -->
    <div class="modal fade" id="myModal7" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
          <h4 class="modal-title">Create/Update Project Task(s):</h4>
          </div>
        <div class="modal-body">
        {{ Form::open(array('before' => 'csrf' ,'id'=>'jobTaskProjFrmUpdate','url'=>route('job/updateProjTaskJob'),'files'=>true, 'method' => 'post')) }} {{Form::hidden('jobId3','',array('id'=>'jobId3'))}} {{Form::hidden('jobNum3','',array('id'=>'jobNum3'))}} {{ Form::hidden('task_hidden_id','', array('id' => 'task_hidden_id')) }}                      
        <div class="form-group">
          <section id="no-more-tables"  style="padding:10px;">
            <table class="table table-bordered table-striped table-condensed cf">
              <tbody class="cf">
                <tr><th>Project Title:</th><td>{{ Form::text('projectTitle3','', array('class' => 'form-control', 'id' => 'projectTitle3','readOnly')) }}</td></tr>
                <tr><th>Task Title:</th><td>{{ Form::text('projectTaskTitle3','', array('class' => 'form-control', 'id' => 'projectTaskTitle3','required')) }}</td></tr>
                <tr><th>Start Date:</th><td>{{ Form::text('projectStartDate3','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'projectStartDate3')) }}</td></tr>
                <tr><th>Days Needed:</th><td>{{ Form::text('projectDays3','', array('class' => 'form-control', 'id' => 'projectDays3','required')) }}</td></tr>
                <tr><th>Resouce Forecast:</th><td>{{ Form::text('projectResourceForecast3','', array('class' => 'form-control', 'id' => 'projectResourceForecast3')) }}</td></tr>
                <tr><th>Subcontractor:</th><td>{{ Form::text('subcontractor3','', array('class' => 'form-control', 'id' => 'subcontractor3')) }}</td></tr>
                <tr><th>Activity ID:</th><td>{{ Form::text('project_activity_id3','', array('class' => 'form-control', 'id' => 'project_activity_id3')) }}</td></tr>
                <tr><th>Employee Type:</th><td>{{Form::select('selectEmpType3', $empTypeArr, null, ['class'=>'form-control','id'=>'selectEmpType3'])}}</td></tr>
                <tr><th>Technician:</th><td>{{Form::select('projectOwnerLeft3[]', $sal_emps_arr, null, ['multiple','class'=>'form-control','id'=>'projectOwnerLeft3'])}} </td></tr>
                <tr><th>Task Detail:</th><td>{{ Form::textarea('notes3', null, ['class' => 'form-control','cols' => '20', 'rows'=>'3','id'=>'notes3']) }}</td></tr>
                <tr><th>Parent Task: </th><td>{{Form::select('parentTask3',array(''=>'Select Parent Task'), null, ['class'=>'form-control','id'=>'parentTask3'])}} </td></tr>
                <tr><th>Include Days(Sat/Sun):</th><td>{{ Form::checkbox('projectIncludeDays3', '',null, ['class' => 'form-control','id'=>'projectIncludeDays3']) }}</td></tr>
                <tr><th>Completed:</th><td>{{ Form::checkbox('projectCompleted3', '',null, ['class' => 'form-control','id'=>'projectCompleted3']) }}</td></tr>
              </tbody>
          </table>
        </section> 
      </div>
      <div class="btn-group" style="padding:20px;">
        {{Form::submit('Save', array('class' => 'btn btn-success', 'id'=>'create_update_taskForProjUpdate','data-dismiss'=>'modal'))}}
        {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
      </div>
      {{Form::close()}}
      </div>
     </div>
    </div>
  </div>
  <!-- modal#7 end--> 
   <!-- Modal#6 -->
           <div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                  <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Create/Update Project Task(s):</h4>
                                          </div>
                                          <div class="modal-body">
                 {{ Form::open(array('before' => 'csrf' ,'id'=>'jobTaskProjFrm','url'=>route('job/createProjTaskJob'),'files'=>true, 'method' => 'post')) }} {{Form::hidden('jobId2','',array('id'=>'jobId2'))}} {{Form::hidden('jobNum2','',array('id'=>'jobNum2'))}}                        
                                             <div class="form-group">
                                                <!-- ...code here.... -->
                                              <section id="no-more-tables"  style="padding:10px;">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                <tbody class="cf">
                                                <tr><th>Project Title:</th><td>{{ Form::text('projectTitle2','', array('class' => 'form-control', 'id' => 'projectTitle2','readOnly')) }}</td></tr>
                                                <tr><th>Task Title:</th><td>{{ Form::text('projectTaskTitle2','', array('class' => 'form-control', 'id' => 'projectTaskTitle2','required')) }}</td></tr>
                                                <tr><th>Start Date:</th><td>{{ Form::text('projectStartDate2','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'projectStartDate2')) }}</td></tr>
                                                <tr><th>Days Needed:</th><td>{{ Form::text('projectDays2','', array('class' => 'form-control', 'id' => 'projectDays2','required')) }}</td></tr>
                                                <tr><th>Resouce Forecast:</th><td>{{ Form::text('projectResourceForecast2','', array('class' => 'form-control', 'id' => 'projectResourceForecast2')) }}</td></tr>
                                                <tr><th>Subcontractor:</th><td>{{ Form::text('subcontractor2','', array('class' => 'form-control', 'id' => 'subcontractor2')) }}</td></tr>
                                                 <tr><th>Activity ID:</th><td>{{ Form::text('project_activity_id2','', array('class' => 'form-control', 'id' => 'project_activity_id2')) }}</td></tr>
                                                <tr><th>Employee Type:</th><td>{{Form::select('selectEmpType2', $empTypeArr, null, ['class'=>'form-control','id'=>'selectEmpType2'])}}</td></tr>
                                                <tr><th>Technician:</th><td>{{Form::select('projectOwnerLeft2[]', $sal_emps_arr, null, ['multiple','class'=>'form-control','id'=>'projectOwnerLeft2'])}} </td></tr>
                                                <tr><th>Task Detail:</th><td>{{ Form::textarea('notes2', null, ['class' => 'form-control','cols' => '20', 'rows'=>'3','id'=>'notes2']) }}</td></tr>
                                                <tr><th>Parent Task: </th><td>{{Form::select('parentTask2',array(''=>'Select Parent Task'), null, ['class'=>'form-control','id'=>'parentTask2'])}} </td></tr>
                                                <tr><th>Include Days(Sat/Sun):</th><td>{{ Form::checkbox('projectIncludeDays2', '',null, ['class' => 'form-control','id'=>'projectIncludeDays2']) }}</td></tr>
                                                <tr><th>Completed:</th><td>{{ Form::checkbox('projectCompleted2', '',null, ['class' => 'form-control','id'=>'projectCompleted2']) }}</td></tr>
                                                  </tbody>
                                                </table>
                                              </section> 
                                              </div>
                                        <div class="btn-group" style="padding:20px;">
                                          {{Form::submit('Save', array('class' => 'btn btn-success', 'id'=>'create_update_taskForProj','data-dismiss'=>'modal'))}}
                                         {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                        </div>
                                        {{Form::close()}}
                                      </div>
                                  </div>
                              </div>
                          </div>
        <!-- modal#6 end-->  
         <!-- Modal#9 -->
           <div class="modal fade" id="myModal9" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                  <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Project Tasks Managment:</h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group">
                                              <section id="no-more-tables"  style="padding:2px;">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                <thead class="cf">
                                                <tr><th>Task Title</th><th>Electrician</th><th>Status</th><th>Projected</th><th>Actual</th><th>Completed</th><th>Add Task</th></tr>
                                                </thead>
                                                <tbody class="cf">
                                                <tr><td data-title="Task:">{{ Form::text('project_task','', array('class' => 'form-control', 'id' => 'project_task','required')) }}</td>
                                                <td data-title="Electrician:">{{Form::select('electrician', $sal_emps_arr, null, ['class'=>'form-control','id'=>'electrician'])}}</td>
                                                <td data-title="Status:">{{ Form::checkbox('task_status','0','', array('id'=>'task_status','class' => 'input-group','style'=>'display:inline;')) }}</td>
                                                <td data-title="Projected:">{{ Form::text('projected','', array('class' => 'form-control', 'id' => 'projected','required')) }}</td>
                                                <td data-title="Actual:">{{ Form::text('actual','', array('class' => 'form-control', 'id' => 'actual','required')) }}</td>
                                                <td data-title="Completed:">{{ Form::checkbox('completed_task','0','', array('id'=>'completed_task','class' => 'input-group','style'=>'display:inline;')) }}</td>
                                                <td data-title="Add Task:">{{Form::button('Add Task', array('class' => 'btn btn-primary btn-xs','id'=>'create_quick_task'))}}</td></tr>
                                                </tbody>
                                                </table>
                                              </section>
                                              <div id="display_job_tasks_data"></div> 
                                              </div>
                                        <div class="btn-group" style="padding:20px;">
                                         {{Form::button('Cancel', array('class' => 'btn btn-warning','data-dismiss'=>'modal'))}}
                                        </div>
                                      </div>
                                    </div>
                              </div>
                          </div>
        <!-- modal#9 end-->  
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
          $('#SDate').val("");
          $('#EDate').val("");
          $('#Filter').val("");
          $('#FVal').val("");
          $('#ProStatus').val("");
      });
      $('a[name=task_id_for_update_job_modal]').click(function(){
      var task_id = $(this).attr('task_id_for_job');
      var proj_title = $(this).attr('proj_title');
      var job_id = $(this).attr('job_id');
      var job_num = $(this).attr('job_num');
      $('#jobId3').val(job_id); 
      $('#jobNum3').val(job_num); 
      $('#projectTitle3').val(proj_title); 
      $('#task_hidden_id').val(task_id); 
      $.ajax({
                      url: "{{URL('ajax/getJobTaskInfo')}}",
                        data: {
                          'task_id' : task_id,
                        },
                        success: function (data) {
                          $('#projectTaskTitle3').val(data.title);    
                          $('#projectStartDate3').val(data.start_date);    
                          $('#projectDays3').val(data.days);    
                          $('#projectResourceForecast3').val(data.resource_hours);    
                          $('#subcontractor3').val(data.subcontractor);    
                          $('#project_activity_id3').val(data.project_activity_id);    
                          $('#selectEmpType3').val(data.task_type);    
                          $('#projectOwnerLeft3').val(data.GPG_employee_id);    
                          $('#notes3').val(data.notes);    
                          $('#parentTask3').val(data.parent_task);    
                          $('#projectIncludeDays3').val(data.include_days);
                      },
            });

    });
  $('#create_update_taskForProjUpdate').click(function(){
      if($('#projectTitle3').val() != '' && $('#projectStartDate3').val() != '' && $('#projectTaskTitle3').val() != '' && $('#projectDays3').val() != ''){
        $('#jobTaskProjFrmUpdate').submit();
      }
      else
        alert("Please Fill all required Fields!");
  });
 $('a[name=create_new_task]').click(function(){
    var job_num = $(this).attr('job_num');
    var job_id = $(this).attr('job_id');
    var pro_title = $(this).attr('pro_title');
    $('#projectTitle2').val(pro_title); 
    $('#jobId2').val(job_id); 
    $('#jobNum2').val(job_num); 

 });
 $('#create_update_taskForProj').click(function(){
      if($('#projectTitle2').val() != '' && $('#projectStartDate2').val() != '' && $('#projectTaskTitle2').val() != '' && $('#projectDays2').val() != ''){
        $('#jobTaskProjFrm').submit();
      }
      else
        alert("Please Fill all required Fields!");
    });

   $("a[name='show_tasks_of_project']").click(function(){
      var job_proj_id = $(this).attr('id_for_job_task');
      var job_id = $(this).attr('job_id');
      var job_num = $(this).attr('job_num');
      if (job_proj_id != ''){
        $.ajax({
          url: "{{URL('ajax/displayJobTasks')}}",
            data: {
              'id' : job_proj_id
            },
              success: function (data) {
               $('#display_job_tasks_data').html(data);
                $("button[name=up_job_task]").click(function(){
                    var id = $(this).attr('up_id');
                     $.ajax({
                          url: "{{URL('ajax/updateJobProjectTask')}}",
                            data: {
                              'id' : id,
                              'job_id' : job_id,
                              'job_num' : job_num
                            },
                              success: function (data) {
                                  alert("Updated Successfully.");
                                  location.reload();
                              },
                      });
                });

                $("button[name=del_job_task]").click(function(){
                    var id = $(this).attr('del_id'); 
                    var result = confirm("Are you sure? You want to delete this ...?");
                    if(result){
                         $.ajax({
                          url: "{{URL('ajax/deleteJobProjectTask')}}",
                            data: {
                              'id' : id,
                              'job_id' : job_id,
                              'job_num' : job_num
                            },
                              success: function (data) {
                                  alert("Deleted Successfully.");
                                  location.reload();
                              },
                        });
                    }
                });

                $('#create_quick_task').click(function(){
                  var proj_task = $('#project_task').val();
                  var electrician = $('#electrician').val();
                  var actual = $('#actual').val();
                  var task_status = document.getElementById("task_status").checked; //status check
                  if (task_status == 1)
                    task_status =1;
                  else
                    task_status =0;
                  var projected = $('#projected').val();
                  var completed_task = document.getElementById("completed_task").checked; //complete check
                  if (completed_task == 1)
                    completed_task =1;
                  else
                    completed_task =0;
                    if(proj_task != '' && electrician!='' && projected != ''){
                        $.ajax({
                            url: "{{URL('ajax/createJobProjectTask')}}",
                              data: {
                                'job_proj_id' : job_proj_id,
                                'proj_task': proj_task,
                                'electrician': electrician,
                                'task_status': task_status,
                                'projected': projected,
                                'completed_task': completed_task  
                              },
                                success: function (data) {
                                    alert("Created Successfully.");
                                    //location.reload();
                                },
                        });
                    }else{
                      alert('Pleas, fill required fields!');
                    }
                });

              },
        });
      }
  });
</script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script> 
@stop