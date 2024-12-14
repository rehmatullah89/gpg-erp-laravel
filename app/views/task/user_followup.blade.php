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
                USER TASK MANAGEMENT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Post/Update/Delete Follow-Up Answers. </i></b>
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">Dept</th>
                            <th style="text-align:center;">Task Details </th>
                            <th style="text-align:center;">Start Date </th>
                            <th style="text-align:center;">End Date</th>
                            <th style="text-align:center;">Followup Qustion</th>
                            <th style="text-align:center;">Followup Answer</th>
                            <th style="text-align:center;">Action</th>
                          </tr>
                        </thead>
                      <tbody>
                      @foreach($query_data as $row)
                        <tr>
                          <td>{{$row['dept_name'] }}</td>
                          <td title="{{$row['task_detail']}}">{{substr($row['task_detail'],0,20).'...'}}</td>
                          <td>{{date('m/d/Y',strtotime($row['start_date']))}}</td>
                          <td><? if($row['expected_end_date']!='')  { echo date('m/d/Y',strtotime($row['expected_end_date']));} else { echo $row['recuring']; }?></td>
                          <td title="{{$row['followup_question']}}">{{substr($row['followup_question'],0,30).'...'}}</td>
                          <td title="{{$row['followup_answer']}}">{{substr($row['followup_answer'],0,30).'...'}}</td>
                          <td>
                           <a href="#myModal2" class="btn btn-success btn-xs" data-toggle="modal" name="add_followup" fquestion="{{$row['followup_question']}}" fanswer="{{$row['followup_answer']}}" id="{{$row['id']}}" taskDetail="{{$row['task_detail']}}" taskEnd="{{$row['expected_end_date']}}" deptName="{{$row['dept_name']}}">
                           @if(empty($row['followup_answer']))
                            <i title="Post/Update Answer" class="fa fa-mail-reply"></i>
                           @else
                            <i title="Post/Update Answer" class="fa fa-edit"></i>
                           @endif 
                           </a> 
                           {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('task/deleteFollowUp', $row['id']))) }}
                           {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}
                           {{ Form::close() }}
                          </td>
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
               <!-- Modal#2 -->
      {{ Form::open(array('before' => 'csrf' ,'url'=>route('task/answerFollowUp'),'id'=>'update_follow_up_form' ,'files'=>true, 'method' => 'post')) }}
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
                      <input type="hidden" name="gpg_task_id" id="gpg_task_id" value="">
                      <tr><th>Department Name:</th><td><b id="dept_name"></b></td></tr>
                      <tr><th>Task Detail:</th><td><b id="det_task"></b></td></tr>
                      <tr><th>End Date:</th><td><b id="dete_end"></b></td></tr>
                      <tr><th>Followup Question</th><td><b id="follow_quest"></b></td></tr>
                      <tr><th>Followup Reply</th><td>{{ Form::textarea('followupReply','', array('class' => 'form-control dpd1', 'id' => 'followupReply','required')) }}</td></tr>
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
              <!-- page end-->
<script type="text/javascript">
    $('a[name=add_followup]').click(function(){
    var id = $(this).attr('id');
    var taskDetail = $(this).attr('taskDetail');
    var taskEnd = $(this).attr('taskEnd');
    var deptName = $(this).attr('deptName');
    var fquestion = $(this).attr('fquestion');
    var fanswer = $(this).attr('fanswer');
    $('#det_task').html(taskDetail);
    $('#dete_end').html(taskEnd);
    $('#dept_name').html(deptName);
    $('#gpg_task_id').val(id);
    $('#follow_quest').html(fquestion);
    $('#followupReply').val(fanswer);
  });

  $('#save_folowup_info').click(function(){
      if($('#followupReply').val() == ''){
        alert('Please fille required Info!');
        return false;
      }else{
        $('#update_follow_up_form').submit();
      }
  });
</script>
 <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop