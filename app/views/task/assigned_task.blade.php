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
                  ASSIGNED TASK MANAGMENT
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('task/assigned_task'), 'files'=>true, 'method' => 'post')) }}
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
                                  {{Form::label('Department', 'Department:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('taskcompleteddetails', 'Task Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                </th>
                                <th>
                                  {{Form::label('Employee', 'Employee:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
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
                                  <td data-title="Department">
                                    {{ Form::select('searchDepartment',array(''=>'All Departments')+$depts,'', array('class' => 'form-control', 'id' => 'searchDepartment')) }}
                                  </td>
                                  <td data-title="Task Status">
                                    {{ Form::select('taskcompleteddetails',array(''=>'All Tasks','completed'=>'Completed','open'=>'Open'),'', array('class' => 'form-control', 'id' => 'taskcompleteddetails')) }}
                                  </td>
                                  <td data-title="Employee">
                                    {{ Form::select('searchEmployee',array(''=>'All Employees')+$emps,'', array('class' => 'form-control', 'id' => 'searchEmployee')) }}
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
                  <th>Dept</th>
                  <th>Resp. Person</th>
                  <th>Customer</th>
                  <th>Ongoing/ 1-Time </th>
                  <th>Start Date </th>
                  <th>Expec. E.Date </th>
                  <th>Days From Est.</th>
                  <th>Date Completed</th>
                  <th>Comp. Task Notes</th>
                  <th>Job #</th>
                  <th>Task Details</th>
                  <th>Status</th>
                  <th>Comp. In</th>
                  <th>Notes</th>
                  <th>Action</th>
              </tr>
              </thead>
              <tbody class="cf">
                @foreach($query_data as $row)
                  <tr>
                    <td>{{$row['task_id']}}</td>
                    <td>{{$row['dept_name']}}</td>
                    <td>{{$row['emp_name']}}</td>
                    <td title="{{$row['cus_name']}}">{{substr($row['cus_name'],0,20).'...'}}</td>
                    <td><?php echo $row['task_type'].($row['task_type']=='Ongoing'?' (<b>'.$row['recuring'].'</b>)':''); ?></td>
                    <td><?php echo date('m/d/Y',strtotime($row['start_date'])) ?></td>
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
                        }else{
                          echo "<strong><span style=\"color:#FF0000\">".$row['now_diff']."</strong></span>";
                        }
                    }?> 
                    </td>
                    <td><?php if (!empty($row['completion_date'])) echo date('m/d/Y',strtotime($row['completion_date'])) ?></td>
                    <td title="{{$row['completion_note']}}">{{substr($row['completion_note'],0,20).'...'}}</td>
                    <td>{{$row['job_num']}}</td>
                    <td title="{{$row['task_detail']}}">{{substr($row['task_detail'],0,20).'...'}}</td>
                    <td><?php echo (empty($row['status'])?"Not Assigned":($row['owned']==1?"Owned":"Assigned")); ?></td>
                    <td><? if($row['completion_date']!=''){ echo $row['comp_date']+1; }else echo '-';?></td>
                    <td title="{{$row['task_note']}}">{{substr($row['task_note'],0,20).'...'}}</td>
                    <td>
                      <a href="{{URL::route('task.edit', array('id'=>$row['id']))}}" style="display:inline;">
                      {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                      </a> 
                      {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('task/deleteAssignedTask', $row['id'])))}}
                      {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}
                      {{ Form::close() }}<br/>
                      <a href="#myModal2" class="btn btn-warning btn-xs" data-toggle="modal" name="add_followup" id="{{$row['id']}}" taskDetail="{{$row['task_detail']}}" taskEnd="{{$row['expected_end_date']}}" deptName="{{$row['dept_name']}}"><i title="Add Follow-up" class="fa fa-wechat"></i></a> 
                      <a href="#myModal3" class="btn btn-primary btn-xs" data-toggle="modal" name="followup_list" id="{{$row['id']}}"><i title="Follow Up List" class="fa fa-list"></i></a> 
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
       <!-- Modal#2 -->
      {{ Form::open(array('before' => 'csrf' ,'url'=>route('task/updateFollowUp'),'id'=>'update_follow_up_form' ,'files'=>true, 'method' => 'post')) }}
      <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
              <h4 class="modal-title">Followup Question</h4>
              </div>
            <div class="modal-body">
                 <section id="no-more-tables" >
                    <table class="table table-bordered table-striped table-condensed cf" >
                      <tbody>
                      <input type="hidden" name="gpgtask_id" id="gpgtask_id" value="">
                      <tr><th>Department Name:</th><td><b id="dept_name"></b></td></tr>
                      <tr><th>Task Detail:</th><td><b id="det_task"></b></td></tr>
                      <tr><th>End Date:</th><td><b id="dete_end"></b></td></tr>
                      <tr><th>Followup Question</th><td>{{ Form::textarea('followup','', array('class' => 'form-control dpd1', 'id' => 'followup','required')) }}</td></tr>
                      </tbody>
                    </table>
                 </section>     
              <div class="btn-group" style="padding:20px;">
                {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'save_folowup_info'))}}
                {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
              </div>
            </div>
          </div>
        </div>
       </div>
    {{Form::close()}}
      <!-- modal#2 end -->
      <!-- Modal#3 -->
      <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
              <h4 class="modal-title">Followup List</h4>
              </div>
            <div class="modal-body">
                 <section id="no-more-tables" >
                    <table class="table table-bordered table-striped table-condensed cf" >
                      <thead>
                        <tr>
                          <th>Questions</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="follow_up_question_body">
                      </tbody>
                    </table>
                 </section>     
              <div class="btn-group" style="padding:20px;">
               {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
              </div>
            </div>
          </div>
        </div>
       </div>
    {{Form::close()}}
      <!-- modal#3 end -->
       <!-- Modal#4 -->
      {{ Form::open(array('before' => 'csrf' ,'url'=>route('task/answerFollowUp'),'id'=>'answer_follow_up_form' ,'files'=>true, 'method' => 'post')) }}
      <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
              <h4 class="modal-title">Followup Question</h4>
              </div>
            <div class="modal-body">
                 <section id="no-more-tables" >
                    <table class="table table-bordered table-striped table-condensed cf" >
                      <tbody>
                      <input type="hidden" name="gpg_task_id" id="gpg_task_id" value="">
                      <input type="hidden" name="rowId" id="rowId" value="">
                      <tr><th>Question:</th><td><b id="fquestion"></b></td></tr>
                      <tr><th>Followup Asnwer:</th><td>{{ Form::textarea('followupReply','', array('class' => 'form-control dpd1', 'id' => 'followupReply','required')) }}</td></tr>
                      </tbody>
                    </table>
                 </section>     
              <div class="btn-group" style="padding:20px;">
                {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'save_answer_folowup'))}}
                {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
              </div>
            </div>
          </div>
        </div>
       </div>
    {{Form::close()}}
      <!-- modal#4 end -->
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
          $('#searchDepartment').val("");
          $('#taskcompleteddetails').val("");
          $('#searchEmployee').val("");
      });
  $('a[name=add_followup]').click(function(){
    var id = $(this).attr('id');
    var taskDetail = $(this).attr('taskDetail');
    var taskEnd = $(this).attr('taskEnd');
    var deptName = $(this).attr('deptName');
    $('#det_task').html(taskDetail);
    $('#dete_end').html(taskEnd);
    $('#dept_name').html(deptName);
    $('#gpgtask_id').val(id);
  });

  $('a[name=followup_list]').click(function(){
    var id = $(this).attr('id');
    $.ajax({
            url: "{{URL('ajax/getFollowupList')}}",
            data: {
                id: id
        },
        success: function (data) {
          $('#follow_up_question_body').html(data);   
          $('a[name=followup_answer]').click(function(){
            var id = $(this).attr('id'); 
            var question = $(this).attr('question'); 
            var rowId = $(this).attr('rowId'); 
            $('#gpg_task_id').val(id);
            $('#fquestion').html(question);
            $('#rowId').val(rowId);
            $('#save_answer_folowup').click(function(){
              if($('#followupReply').val() == ''){
                alert('PLease Fill Required Fields!');
                return false;
              }else{
                $('#answer_follow_up_form').submit();
              }
            });
          });
        },
      });
  });
</script>
<script src="{{asset('js/jquery.nicescroll.js') }}"></script>
<script src="{{asset('js/common-scripts.js') }}"></script>
@stop