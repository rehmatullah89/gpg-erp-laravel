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
               TROUBLE CALLS 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Dates / Filters</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Date Opened Start:">
                                    {{Form::label('OpenedSDate', 'Date Opened Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('OpenedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'OpenedSDate')) }}
                                  </td>
                                  <td data-title="Date Opened End:">
                                    {{Form::label('OpenedEDate', 'Date Opened End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('OpenedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'OpenedEDate')) }}
                                  </td>
                                  <td data-title="Date Scheduled Start:">
                                    {{Form::label('ScheduledSDate', 'Date Scheduled Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('ScheduledSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ScheduledSDate')) }}
                                  </td>
                                  <td data-title="Date Scheduled End:">
                                    {{Form::label('ScheduledEDate', 'Date Scheduled End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('ScheduledEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ScheduledEDate')) }}
                                  </td>
                                  <td data-title="Job Num Start:">
                                    {{Form::label('SJobNumber', 'Job Num Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SJobNumber','', array('class' => 'form-control', 'id' => 'SJobNumber')) }}
                                  </td>
                                  <td data-title="Job Num End:">
                                    {{Form::label('EJobNumber', 'Job Num End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EJobNumber','', array('class' => 'form-control', 'id' => 'EJobNumber')) }}
                                  </td>
                                  <td data-title="Contract Number Start:">
                                    {{Form::label('SContractNumber', 'Contract Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SContractNumber','', array('class' => 'form-control', 'id' => 'SContractNumber')) }}
                                  </td>
                                </tr>
                                <tr>
                                  <td data-title="Contract Num End:">
                                    {{Form::label('EContractNumber', 'Contract Num End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EContractNumber','', array('class' => 'form-control', 'id' => 'EContractNumber')) }}
                                  </td>
                                  <td data-title="Customer:">
                                    {{Form::label('Customer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('optCustomer', array(''=>'Select Customer')+$customer_arr,'', ['id' => 'optCustomer', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Sort by:">
                                    {{Form::label('order_by', 'Sort by:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('order_by', array("created_on"=>"Date Opened","job_num"=>"Job Number", "schedule_date"=>"Scheduled Date","cus_name"=>"Customer Name","count_days"=>"Count Days"),'', ['id' => 'order_by', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Order Type:">
                                    {{Form::label('orderby_type', 'Order Type:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('orderby_type', array("ASC"=>"Ascending","DESC"=>"Descending"),'', ['id' => 'orderby_type', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Reasons:">
                                    {{Form::label('tasks', 'Reasons:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('tasks', array(''=>'Select Reason')+$arrtasks, null, ['id' => 'tasks', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Job Status:">
                                    {{Form::label('optJobStatus', 'Job Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('optJobStatus', array("1"=>"ALL","completed"=>"Complete","notcompleted"=>"Incomplete"), null, ['id' => 'optJobStatus', 'class'=>'form-control m-bot15'])}}
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
                  <th>Date Opened</th>
                  <th>Job Number</th>
                  <th>Contract Number</th>
                  <th >Reason</th>
                  <th >Reschedule Date</th>
                  <th >Customer</th>
                  <th >Notes</th>
                  <th >Count Days</th>
              </tr>
              </thead>
              <tbody class="cf">
                @foreach($query_data as $row)
                  <tr>
                    <td data-title="Date Opened:">{{($row['created_on']!=""?date('m/d/Y',strtotime($row['created_on'])):"-")}}</td>
                    <td data-title="Job Number:">{{ HTML::link('job/service_job_list', $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link'))}} </td>
                    <td data-title="Contract Number:">{{ HTML::link('job/service_job_list', $row['contract_number']!=""?$row['contract_number']:"-" , array('target'=>'_blank','class'=>'btn btn-link'))}} </td>
                    <td data-title="Reason:" title="{{$row['sub_task']}}">{{$row['task']}}</td>
                    <td data-title="Reschedule Date:">{{($row['schedule_date']!=""?date('m/d/Y',strtotime($row['schedule_date'])):"-")}}</td>
                    <td data-title="Customer:">{{$row['cus_name']}}</td>
                    <td data-title="Notes:">
                      <?php $notesRs = DB::select(DB::raw("SELECT *,(select name from gpg_employee where id = entered_by) as enterdBy FROM gpg_job_note WHERE gpg_job_id = '".$row['jobID']."' ORDER BY dated"));
                        foreach ($notesRs as $key => $notesRow) {
                          echo (!empty($notesRow->dated)?date('m/d/Y',strtotime($notesRow->dated)):'')."@".$notesRow->enterdBy.": ".$notesRow->notes."<br>";
                        }
                      ?>
                    </td>
                    <td data-title="Count Days:">{{$row['count_days']}}</td>
                  </tr>
                @endforeach
              </tbody>
              </table>
              {{ HTML::link("reports/excelTCJReportExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
              <br/>
             {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
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
          $('#OpenedSDate').val("");
          $('#OpenedEDate').val("");
          $('#ScheduledSDate').val("");
          $('#ScheduledEDate').val("");
          $('#SJobNumber').val("");
          $('#EJobNumber').val("");
          $('#SContractNumber').val("");
          $('#EContractNumber').val("");
          $('#optCustomer').val("");
          $('#order_by').val("");
          $('#orderby_type').val("");
          $('#tasks').val("");
          $('#optJobStatus').val("");
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop