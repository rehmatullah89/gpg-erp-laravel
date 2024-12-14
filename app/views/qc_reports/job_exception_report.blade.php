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
                JOBS EXCEPTION REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Job Closing Date</i></b>
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
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Job Type:">
                                    {{Form::label('job_type', 'Job Type:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::select('job_type',$job_types,'', array('class' => 'form-control', 'id' => 'job_type')) }}
                                  </td>
                                  <td data-title="Start Date:">
                                    {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td>
                                  <td data-title="End Date:">
                                    {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Job Closed:">
                                    {{Form::label('date_diff', 'Job Closed:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('date_diff','0', array('class' => 'form-control', 'id' => 'date_diff')) }}<sub>:days after completion.</sub>
                                  </td>
                                  <td data-title="Closed Not Completed Only:">
                                    {{Form::label('chkclose', 'Closed Not Completed Only:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::checkbox('chkclose', 1, null, ['class' => 'form-control', 'id' => 'chkclose']) }}
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
                  <th>SR #</th>
                  <th>Job Number </th>
                  <th>Name of Job</th>
                  <th>Customer</th>
                  <th>Complete</th>
                  <th>Closing Date </th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php $colcount=0;?>
                @foreach($query_data as $row)
                  @if($row['has_pos'] >0 or $row['has_timesheets'] > 0 or $row['has_job_costs'] > 0 or $row['has_invoices'] > 0)
                    <tr bgcolor="<?php echo ($colcount%2==0?"#FFFFFF":"#FFFFCC")?>">
                      <?php $colcount++;?>
                      <td align="center" ><strong>{{$colcount}}&nbsp;&nbsp;&nbsp;{{Form::button('<i class="fa fa-plus"></i>', array('class' => 'btn btn-success btn-xs', 'onclick'=>'toggleCustomerInfo('.$colcount.')'))}}</strong></td>
                      <td height="30" ><?php $temp_job = substr($row['job_num'],0,3); ?>
                      @if($temp_job=="GPG")
                        {{ HTML::link('job/elec_job_list', $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                      @elseif(preg_match("/IG/i",$row['job_num']))
                        {{ HTML::link('job/grassivyJobList', $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                      @elseif(preg_match("/LK/i",$row['job_num']))
                        {{ HTML::link('job/specialProjectJobList', $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                      @elseif(substr($temp_job,0,2)=="SH")
                        {{ HTML::link('job/shopWorkJobList', $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                      @else
                        {{ HTML::link('job/service_job_list', $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                      @endif
                      </td>
                      <td title="{{$row['task']}}">{{substr($row['task'],0,30)."..."}}</td>
                      <td >{{DB::table('gpg_customer')->where('id','=',$row['GPG_customer_id'])->pluck('name')}}</td>
                      <td align="center" ><strong>{{($row['complete']==1?($row['date_completion']!=""?date('m/d/Y',strtotime($row['date_completion'])):"Completed"):"&nbsp;")}}</strong></td>
                      <td align="center"><strong>{{date('m/d/Y',strtotime($row['closing_date']))}}</strong></td>
                    </tr>
                    <tr id="hideme_{{$colcount}}" bgcolor="#FFFFCC"><td colspan="6">
                    <table class="table table-bordered table-striped table-condensed cf">
                      <thead>
                        <tr>
                          <th>Invoices</th><th>Purchase Orders</th><th>Job Costs</th><th>Time Cards</th>
                        </tr>
                      </thead>
                       <tbody>
                         <tr>
                            <td>{{($row['has_invoices']>0?HTML::link('#myModal',$row['has_invoices'], array('data-toggle'=>'modal','type'=>'Invoices','job_num'=>$row['job_num'],'closing_date'=>$row['closing_date'],'name'=>'modalInfo', 'id'=>$row['id'])):0)}}</td> 
                            <td>{{($row['has_pos']>0?HTML::link('#myModal',$row['has_pos'], array('data-toggle'=>'modal','type'=>'Purchase_orders','job_num'=>$row['job_num'],'closing_date'=>$row['closing_date'],'name'=>'modalInfo', 'id'=>$row['id'])):0)}}</td>
                            <td>{{($row['has_job_costs']>0?HTML::link('#myModal',$row['has_job_costs'], array('data-toggle'=>'modal','type'=>'Job_Costs','job_num'=>$row['job_num'],'closing_date'=>$row['closing_date'],'name'=>'modalInfo', 'id'=>$row['id'])):0)}}</td>
                            <td>{{($row['has_timesheets']>0?HTML::link('#myModal',$row['has_timesheets'], array('data-toggle'=>'modal','type'=>'Time_Cards','job_num'=>$row['job_num'],'closing_date'=>$row['closing_date'],'name'=>'modalInfo', 'id'=>$row['id'])):0)}}</td>
                         </tr>
                       </tbody>
                    </table>
                    </td></tr>
                  @endif
                 @endforeach
                </tbody>
              </table>
              {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
            </section>
           </div>
        </div>      
      </div>
      <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
              {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
              <h4 class="modal-title">Details Info</h4>
              </div>
            <div class="modal-body">
             <section id="no-more-tables" >
              <table class="table table-bordered table-striped table-condensed cf" >
                <thead class="cf" id="table_head">
                </thead>
                <tbody id="table_body">
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
          $('#job_type').val("");
          $('#SDate').val("");
          $('#EDate').val("");
          $('#date_diff').val("0");
          $('#chkclose').val("0");
      });
        $( document ).ready(function() {
        var cnt = 100;  
        var icnt = 1;
        while(icnt <= cnt){
            $('#hideme_'+icnt).hide();
            icnt = parseInt(icnt) + parseInt("1");
         }
    });    
    function toggleCustomerInfo(id){
      $('#hideme_'+id).toggle();
    }
    $('a[name=modalInfo]').click(function(){
      var id =  $(this).attr('id');
      var type =  $(this).attr('type');
      var job_num =  $(this).attr('job_num');
      var closing_date =  $(this).attr('closing_date');
      $.ajax({
            url: "{{URL('ajax/getDetailsInfo')}}",
              data: {
               'id' : $(this).attr('id'),
               'type' : $(this).attr('type'),
               'job_num' : $(this).attr('job_num'),
               'closing_date' : $(this).attr('closing_date')
              },
            success: function (data) {
              $('#table_head').html(data.thead);
              $('#table_body').html(data.tbody);
            },
      });
    });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop