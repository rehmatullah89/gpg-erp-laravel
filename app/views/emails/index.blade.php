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
                 ATTACHED EMAILS
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b>Search by:</b><i> Sent Date/ Email / Subject Or Sender Filter </i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('emails/index'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                     <th>
                                      {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                     <th>
                                        {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                     <th> <b>Email </b></th>
                                      <th><b>Filter</b></th>
                                      <th><b>Filter Value</b></th>
                                      <th><b>Mailbox</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr>
                                  <td data-title="Start Date:">
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'start_date')) }}
                                  </td><td data-title="End Date:">
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'end_date')) }}
                                  </td>
                                  <td data-title="Emails:">
                                    {{Form::select('email_filter', array(''=>'Please Select'), null, ['id' => 'email_filter', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Filter:">
                                    {{Form::select('Filter', array(''=>'Select Filter','sender' => 'Sender', 'subject' => 'Subject', 'job_num' => 'Attached Job No.'), null, ['id' => 'emp_time_chng', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Filter Value:">
                                  {{ Form::text('FVal','', array('class' => 'form-control', 'id' => 'filter_value')) }}
                                  </td>
                                  <td data-title="MailBox:">
                                    {{Form::select('mailbox', array('I'=>'Inbox','S'=>'Sentbox'), null, ['id' => 'mailbox', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                    </tr></tbody></table>
                                    <br/>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
                                  </section>
                               {{ Form::close() }}
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
     <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th style="text-align:center;">Email</th>
                                          <th style="text-align:center;">Receiver</th>
                                          <th style="text-align:center;">   Subject   </th>
                                          <th style="text-align:center;">Attached Job</th>
                                          <th style="text-align:center;">Date</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <?php
                                          $counter_email = 0 ;
                                          $job_num = "" ;
                                          $list_link = "" ;
                                      ?> 
                                        @foreach($data_arr as $row)
                                          <?php
                                              $list_link = "" ; 
                                              $sent_dtime = explode(" ",$row["sent_date"]) ;
                                              $sent_date = explode("-",$sent_dtime[0]) ;
                                              $sent_actual_date = $sent_date[1]."/".$sent_date[2]."/".$sent_date[0] ; 
                                              $sent_time = substr($sent_dtime[1],0,strlen($sent_dtime[1]) - 3) ;
                                              $sender_name = ($mailbox == "I") ? $row["from_name"] : $row["to_name"];
                                              $sender_name = (strlen($sender_name) > 28) ? substr($sender_name,0,28)."..." : $sender_name ;
                                              $subject_string = (strlen($row["email_subject"]) > 70) ? substr($row["email_subject"],0,70)."..." : $row["email_subject"] ;
                                              $subject_string = ($subject_string == "") ? "(no subject)" : $subject_string ; 
                                              $job_num = ($row["gpg_attach_job_num"] != "") ? $row["gpg_attach_job_num"] : "" ;
                                              $att_count = $row["count_attachment"];
                                              ?>
                                          <tr>
                                            <td>{{$row["email_add"]}}</td>
                                            <td>{{htmlentities($sender_name)}}</td>
                                            <td>
                                               {{ HTML::link('#myModal', $subject_string , array('data-toggle'=>'modal','id'=>$row['email_id'],'name'=>'emailModal'))}}  
                                            </td>
                                            <td>
                                            <?php 
                                              if($job_num != ""){
                                                 if (preg_match("/GPG/i",$job_num)) { ?>
                                                  {{ HTML::link('job/elec_job_list', $job_num , array('target'=>'_blank','class'=>'btn btn-link'))}} 
                                                <?php }
                                                 elseif (preg_match("/RNT/i",$job_num)) { ?>
                                                  {{ HTML::link('invoice', $job_num , array('target'=>'_blank','class'=>'btn btn-link'))}}
                                                 <?php }
                                                 elseif (preg_match("/IG/i",$job_num)) { ?>
                                                    {{ HTML::link('job/grassivyJobList', $job_num , array('target'=>'_blank','class'=>'btn btn-link'))}}
                                                 <?php }
                                                 elseif (preg_match("/LK/i",$job_num)) { ?>
                                                   {{ HTML::link('job/specialProjectJobList', $job_num , array('target'=>'_blank','class'=>'btn btn-link'))}}
                                                 <?php }
                                                 else{ ?>
                                                    {{ HTML::link('job/service_job_list', $job_num , array('target'=>'_blank','class'=>'btn btn-link'))}}
                                                <?php }
                                               }
                                            ?>
                                            </td>
                                            <td><?php echo $sent_actual_date . " " . $sent_time ;?></td>
                                          </tr>
                                          <?php $counter_email++ ; ?>
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
               <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Email</h4>
                                          </div>
                                          <div class="modal-body">
                                              <section id="no-more-tables">
                                              <table class="table table-bordered table-striped table-condensed cf">
                                              <tbody>
                                                <tr><th><b>From</b></th><td>{{ Form::text('from_user','', array('class' => 'form-control', 'id' => 'from_user','readOnly')) }}</td></tr>
                                                <tr><th><b>To</b></th><td>{{ Form::text('to_user','', array('class' => 'form-control', 'id' => 'to_user','readOnly')) }}</td></tr>
                                                <tr><th><b>Date</b></th><td>{{ Form::text('to_date','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'to_date','readOnly')) }}</td></tr>
                                                <tr><th><b>Subject</b></th><td>{{ Form::text('subject_email','', array('class' => 'form-control', 'id' => 'subject_email','readOnly')) }}</td></tr>
                                                <tr><th><b>Content</b></th><td>{{ Form::textArea('content_email','', array('class' => 'form-control', 'id' => 'content_email','readOnly')) }}</td></tr>
                                              </tbody>  
                                              </table>
                                            </section>                                        
                                         </div>
                                       <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                        </div>   
                                  </div>
                              </div>
                          </div>
                        <!-- modal -->
       <script>
       $(document).ready(function(){
            $('#reset_search_form').click(function(){
              $('#start_date').val("");
              $('#end_date').val("");
              $('#email_filter').val("");
              $('#filter_value').val(""); 
              $('#mailbox').val(""); 
            });

           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });

       });   

  $('a[name=emailModal]').click(function(){
     var id = $(this).attr('id');
     $.ajax({
        url: "{{URL('ajax/getEmailDetails')}}",
          data: {
          'id' : id
        },
          success: function (data) {
          $('#from_user').val(data.from_user);
          $('#to_user').val(data.to_user);
          $('#to_date').val(data.to_date);
          $('#subject_email').val(data.subject_email);
          $('#content_email').html(data.content_email);
        },
    });
  });
  </script>
@stop