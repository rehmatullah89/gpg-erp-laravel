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
                  TASK MANAGMENT
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('task/index'), 'files'=>true, 'method' => 'post')) }}
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
                                    {{Form::select('Filter', array(''=>'Select Filter','Department'=>'Department'), null, ['id' => 'Filter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                   <td data-title="Filter Value:" id="filter_val">
                                    {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'FVal')) }}
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
              <?php
                $dept_str = ''; 
                foreach ($depts as $key => $value) {
                  $dept_str .= '<option value='.$key.'>'.$value.'</option>';
                }
              ?>
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
                  <th>Task Details </th>
                  <th >Start Date </th>
                  <th >Exp. End Date </th>
                  <th >Task Type</th>
                  <th >Task Creator</th>
                  <th >Action</th>
              </tr>
              </thead>
              <tbody class="cf">
                @foreach($query_data as $row)
                  <tr>
                    <td>{{$row['id']}}</td>
                    <td>{{$row['dept_name']}}</td>
                    <td title="{{$row['task_detail']}}">{{substr($row['task_detail'],0,20).'...'}}</td>
                    <td>{{date('m/d/Y',strtotime($row['start_date']))}}</td>
                    <td><?php if($row['expected_end_date']!='')  { echo date('m/d/Y',strtotime($row['expected_end_date']));} ?></td>
                    <td><?php echo $row['task_type'].($row['task_type']=='Ongoing'?' ('.$row['recuring'].')':'')?></td>
                    <td>{{$row['task_creator'].' ('.$row['task_creator_type'].')'}}</td>
                    <td> 
                       <a href="#myModal" class="btn btn-success btn-xs" data-toggle="modal" name="assign_task" id="{{$row['id']}}"><i title="Assign Task" class="fa fa-user-md"></i></a> 
                       {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('task.destroy', $row['id']))) }}
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
          <!-- Modal -->
          {{ Form::open(array('before' => 'csrf' ,'url'=>route('task/updateInfoModal'),'id'=>'update_info_modal_form' ,'files'=>true, 'method' => 'post')) }}
          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                  {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                  <h4 class="modal-title">ASSIGN TASK</h4>
                  </div>
                  <div class="modal-body">
                    <section id="no-more-tables" >
                      <table class="table table-bordered table-striped table-condensed cf" >
                      <tbody>
                          <input type="hidden" name="task_id" id="task_id" value="">
                          <tr>
                          <th style="text-align:center;">Select Customer:</th>
                          <td>{{ Form::select('customer',array(''=>'Select Cusotomer')+$customers,'', array('class' => 'form-control dpd1', 'id' => 'customer')) }}</td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Assign To:</th>
                          <td id="td_to_assign"></td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Task Type:</th>
                          <td>{{ Form::select('tasktype',array('Onetime'=>'One Time','Ongoing'=>'On Going'),'', array('class' => 'form-control dpd1', 'id' => 'tasktype','onChange'=>"check()")) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Recurring:</th>
                            <td>{{ Form::select('recuring',array('Daily'=>'Daily','Weekly'=>'Weekly','Monthly'=>'Monthly'),'', array('class' => 'form-control dpd1', 'id' => 'recuring','readOnly')) }}</td>
                          </tr> 
                          <tr>
                            <th style="text-align:center;">Priority:</th>
                            <td>{{ Form::select('priority',array('High'=>'High','Medium'=>'Medium','Low'=>'Low'),'Medium', array('class' => 'form-control dpd1', 'id' => 'priority')) }}</td>
                          </tr> 
                          <tr>
                            <th style="text-align:center;" class="type_ksize">Expected End Date</th>
                            <td>{{ Form::text('endDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'endDate')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Job Number:</th>
                            <td>{{ Form::text('POjobNum','', array('class' => 'form-control dpd1', 'id' => 'POjobNum')) }}</td>
                          </tr>
                          <tr>
                            <th style="text-align:center;">Task Details*:</th>
                            <td>{{ Form::textarea('task_detail','', array('class' => 'form-control dpd1', 'id' => 'task_detail','required')) }}</td>
                          </tr>
                      </tbody>
                    </table>
                  </section>  
                </div>
              <div class="btn-group" style="padding:20px;">
              {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'submit_emp_modal'))}}  
              {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
            </div>
          </div>
         </div>
        </div>
        {{Form::close()}}
      <!-- modal -->
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
          $('#SDate').val("");
          $('#EDate').val("");
          $('#Filter').val("");
          $('#FVal').val("");
      });
      $('#Filter').change(function(){
        var vl = $(this).val();
        if(vl == 'Department'){
          $('#filter_val').html('<select class="form-control" name="Department">{{$dept_str}}</select>');
        }else
          $('#filter_val').html('<input type="text" value="" name="FVal" id="FVal" class="form-control">');
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

  $('a[name=assign_task]').click(function(){
    var id = $(this).attr('id');
    $.ajax({
      url: "{{URL('ajax/getModalInfo')}}",
        data: {
          'id' : id
        },
        success: function (data) {
          $('#task_id').val(data.id);
          $('#customer').val(data.customer);
          $('#tasktype').val(data.tasktype);
          $('#recuring').val(data.recuring);
          $('#priority').val(data.priority);
          $('#endDate').val(data.endDate);
          $('#POjobNum').val(data.POjobNum);
          $('#task_detail').val(data.task_detail);
          $('#td_to_assign').html(data.assignTo);
          $('#submit_emp_modal').click(function(){
            if($('#assignTo').val()== ''){
              alert('Please Select Employee first!');
              return false;
            }else{
              $('#update_info_modal_form').submit();
            }
          });
        },
      });
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

  $('#save_folowup_info').click(function(){
      if($('#followup').val() == ''){
        alert('Please fille required Info!');
        return false;
      }else{
        $('#update_follow_up_form').submit();
      }
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
@stop