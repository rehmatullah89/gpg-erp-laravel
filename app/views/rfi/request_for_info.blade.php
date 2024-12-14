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
                 REQUEST FOR INFORMATION  
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                      <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Discussion for requested information</i></b>
                      </header>
                </section> 
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
                    <div class="timeline-messages">
                                  <!-- Comment -->
                                  <div class="msg-time-chat">
                                       <div class="message-body msg-in">
                                          <span class="arrow"></span>
                                          <div class="text">
                                              <p class="attribution"><a href="#">Req. By: {{isset($res_data['full_name'])?$res_data['full_name']:"-"}} | Req. To: {{isset($res_data['emp_name'])?$res_data['emp_name']:"-"}}</a> at {{date("F d, Y l",strtotime($res_data['created_on']))}}</p>
                                              <p><b>{{$res_data['title']}}</b></p>
                                          </div>
                                      </div>
                                  </div>
                                  <!-- /comment -->
                                @foreach($comments  as $com)
                                   <div class="msg-time-chat">
                                      <div class="message-body msg-out">
                                          <span class="arrow"></span>
                                          <div class="text">
                                              <p class="attribution"><a href="#">Req. By: {{isset($com['full_name'])?$com['full_name']:"-"}} | Req. To: {{isset($com['emp_name'])?$com['emp_name']:"-"}}</a> at {{date("F d, Y l",strtotime($com['created_on']))}}</p>
                                              <p>{{$com['rfi_message']}}</p>
                                          </div>
                                      </div>
                                  </div>
                                @endforeach  
                          </div>
                          </div>
                          @if($res_data['status'] != '1')
                          {{Form::button('Close Discussion', array('class' => 'btn btn-warning', 'id'=>'close_discussion','rfi'=>$res_data['id']))}}
                          {{HTML::link('#myModal','Reply To Discussion', array('class' => 'btn btn-success','data-toggle'=>'modal','id'=>$res_data['id'],'name'=>'modalInfo'))}} 
                          @endif
                          {{Form::button('Delete Discussion', array('class' => 'btn btn-danger', 'id'=>'delete_discussion','rfi'=>$res_data['id']))}}
                          
              </section>
              </div>
              </div>
              <!-- page end-->
                  <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                   {{ Form::open(array('before' => 'csrf','id'=>'reply_form' ,'url'=>route('rfi/saveReply'), 'files'=>true, 'method' => 'post')) }}
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Reply to Discussion!<b id="JobNum"></b></h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group">
                                                <section id="no-more-tables">
                                                <table class="table table-bordered table-striped table-condensed cf" align="center">
                                                  <tbody>
                                                    <tr>
                                                      <input type="hidden" name="rfi_id" value="{{$res_data['id']}}">
                                                      <input type="hidden" name="gpg_requested_by_id" value="{{$res_data['gpg_requested_by_id']}}">
                                                      <td>{{ Form::textarea('new_rfi', null, ['size' => '30x5','id'=>'new_rfi','class'=>'form-control']) }}</td>
                                                    </tr>
                                                    <tr>
                                                      <td>Add Attachment:{{ Form::file('fileToUpload','', array('class' => 'form-control', 'id' => 'fileToUpload')) }}</td>
                                                    </tr>
                                                  </tbody>   
                                                </table>  
                                             </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                            {{Form::button('Save Reply', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'save_reply'))}}  
                                            {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                          </div>
                                  </div>
                                  {{Form::close()}}
                              </div>
                          </div>
                        <!-- modal -->

  <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
  <script src="{{asset('js/common-scripts.js')}}"></script> 
  <script type="text/javascript">
  $('#close_discussion').click(function(){
      var id = $(this).attr('rfi');
      var conf = confirm('Are You sure, you want to close this discussion.'); 
      if(conf){
        $.ajax({
            url: "{{URL('ajax/closeDiscussion')}}",
              data: {
               'id' : id
              },
            success: function (data) {
              location.reload();
            },
        });
      }       
  });
  
  $('#delete_discussion').click(function(){
      var id = $(this).attr('rfi');
      var conf = confirm('Are You sure, you want to delete this discussion.'); 
      if(conf){
        $.ajax({
            url: "{{URL('ajax/deleteDiscussion')}}",
              data: {
               'id' : id
              },
            success: function (data) {
              location.reload();
            },
        });
      }       
  });
  $('#save_reply').click(function(){
    $('#reply_form').submit();
  });
  </script>
@stop