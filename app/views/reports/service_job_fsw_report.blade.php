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
               SERVICE JOB FSW REPORT
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
                                  <td data-title="Date Quoted Start:">
                                    {{Form::label('QuotedSDate', 'Date Quoted Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('QuotedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'QuotedSDate')) }}
                                  </td>
                                  <td data-title="Date Quoted End:">
                                    {{Form::label('QuotedEDate', 'Date Quoted End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('QuotedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'QuotedEDate')) }}
                                  </td>
                                  <td data-title="Date Won Start:">
                                    {{Form::label('WonSDate', 'Date Won Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('WonSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'WonSDate')) }}
                                  </td>
                                  <td data-title="Date Won End:">
                                    {{Form::label('WonEDate', 'Date Won End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('WonEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'WonEDate')) }}
                                  </td>
                                  <td data-title="Parts Ordered SDate:">
                                    {{Form::label('PartsOrderedSDate', 'Parts Ordered SDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('PartsOrderedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsOrderedSDate')) }}
                                  </td>
                                  <td data-title="Parts Ordered EDate:">
                                    {{Form::label('PartsOrderedEDate', 'Parts Ordered EDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('PartsOrderedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsOrderedEDate')) }}
                                  </td>
                                  <td data-title="Parts Recieved SDate:">
                                    {{Form::label('PartsRecievedSDate', 'Parts Recieved SDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('PartsRecievedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsRecievedSDate')) }}
                                  </td>
                                  <td data-title="Parts Recieved EDate:">
                                    {{Form::label('PartsRecievedEDate', 'Parts Recieved EDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('PartsRecievedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsRecievedEDate')) }}
                                  </td>
                                </tr>
                                <tr>
                                  <td data-title="Date Parts Scheduled Start:">
                                    {{Form::label('PartsScheduledSDate', 'Date Parts Scheduled Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('PartsScheduledSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsScheduledSDate')) }}
                                  </td>
                                  <td data-title="Date Parts Scheduled End:">
                                    {{Form::label('PartsScheduledEDate', 'Date Parts Scheduled End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('PartsScheduledEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsScheduledEDate')) }}
                                  </td>
                                  <td data-title="Job Num Start:">
                                    {{Form::label('SJobNumber', 'Job Num Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SJobNumber','', array('class' => 'form-control', 'id' => 'SJobNumber')) }}
                                  </td>
                                  <td data-title="Job Num End:">
                                    {{Form::label('EJobNumber', 'Job Num End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EJobNumber','', array('class' => 'form-control', 'id' => 'EJobNumber')) }}
                                  </td>
                                  <td data-title="Fbomb  Num Start:">
                                    {{Form::label('SFSWNumber', 'Fbomb Num Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SFSWNumber','', array('class' => 'form-control', 'id' => 'SFSWNumber')) }}
                                  </td>
                                  <td data-title="Fbomb  Num End:">
                                    {{Form::label('EFSWNumber', 'Fbomb Num End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EFSWNumber','', array('class' => 'form-control', 'id' => 'EFSWNumber')) }}
                                  </td>
                                  <td data-title="Sales Person:">
                                    {{Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('optEmployee', array(''=>'Select Sales Person')+$salesp_arr,'', ['id' => 'optEmployee', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Customer:">
                                    {{Form::label('optCustomer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('optCustomer', array(''=>'Select Customer')+$customer_arr,'', ['id' => 'optCustomer', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                </tr>
                                <tr>  
                                  <td data-title="Job Status:">
                                    {{Form::label('optJobStatus', 'Job Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('optJobStatus',array(''=>'ALL',"completed"=>"Have been Completed","notcompleted"=>"Jobs Not Completed"),'', ['id' => 'optJobStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="FSW Status:">
                                    {{Form::label('FSWStatus', 'FSW Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('FSWStatus',$FSWStatusArray,'', ['id' => 'FSWStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Zone Index:">
                                    {{Form::label('zone_index_val', 'Zone Index:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('zone_index_val',$zone_indexes,'', ['id' => 'zone_index_val', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Sort by:">
                                    {{Form::label('order_by', 'Sort by:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('order_by', array("fwsStatus"=>"FSW Status","jobNum"=>"Job Number", "jobEmployee"=>"Sales Person","fNum"=>"Fbomb","jobCustomer"=>"Customer","fQouteDate"=>"Date Quoted","fDateWon"=>"Date Won","zone_index"=>"Zone Index"),'', ['id' => 'order_by', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Order Type:">
                                    {{Form::label('orderby_type', 'Order Type:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('orderby_type', array("ASC"=>"Ascending","DESC"=>"Descending"),'', ['id' => 'orderby_type', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Completed Date:">
                                    {{Form::label('ignore_date', 'Completed Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('ignore_date',array("0"=>"Must Have Complete Date","1"=>"Ignore Complete Date"),'', ['id' => 'ignore_date', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td colspan="2">
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
                  <th>Job Number</th>
                  <th>Zone Index</th>
                  <th>FSW Status</th>
                  <th >Customer</th>
                  <th >Jobsite</th>
                  <th >Salesman</th>
                  <th >Fbomb</th>
                  <th >Quoted Amount</th>
                  <th >Date Quoted</th>
                  <th >Date Won</th>
                  <th >Date Parts Ordered</th>
                  <th >Days Ordered</th>
                  <th >Date Parts Received</th>
                  <th >Days Received</th>
                  <th >Date job Scheduled</th>
                  <th >Notes</th>
              </tr>
              </thead>
              <tbody class="cf">
               @foreach($query_data as $row)
                <tr>
                  <td>{{ HTML::link('job/service_job_list', $row['jobNum'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <td><?php
                  if(isset($row['zone_index_id'])){
                    $zi_query = DB::table('gpg_settings')->where('id','=',$row['zone_index_id'])->pluck('value');
                    echo @$zi_query;
                  }else{
                    echo "-";
                  }
                  ?></td>
                  <td>{{(isset($FSWStatusArray[$row['fwsStatus']])?$FSWStatusArray[$row['fwsStatus']]:'-')}}</td>
                  <td>{{(isset($row['jobCustomer'])?$row['jobCustomer']:'-')}}</td>
                  <td>{{(isset($row['jobSite'])?$row['jobSite']:'-')}}</td>
                  <td>{{(isset($row['jobEmployee'])?$row['jobEmployee']:'-')}}</td>
                  <td>{{ HTML::link('job/field_service_work_list', $row['fNum'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <td>{{'$'.number_format($row['fQoueAmt'],2)}}</td>
                  <td>{{(!empty($row['fQouteDate'])?date('m/d/Y',strtotime($row['fQouteDate'])):'-')}}</td>
                  <td>{{(!empty($row['fDateWon'])?date('m/d/Y',strtotime($row['fDateWon'])):'-')}}</td>
                  <td>{{(!empty($row['jobDatePartsOrderd'])?date('m/d/Y',strtotime($row['jobDatePartsOrderd'])):'-')}}</td>
                  <td>
                    <?php if(!empty($row['jobDatePartsOrderd']) && !empty($row['jobDatePartsRecieved'])) {
                            $datetime1 = new DateTime($row['jobDatePartsOrderd']);
                            $datetime2 = new DateTime($row['jobDatePartsRecieved']);
                            $interval = $datetime1->diff($datetime2);
                            echo $interval->format('%R%a days');
                      } elseif(!empty($row['jobDatePartsOrderd']) && empty($row['jobDatePartsRecieved'])) {
                            $datetime1 = new DateTime($row['jobDatePartsOrderd']);
                            $datetime2 = new DateTime(date('Y-m-d'));
                            $interval = $datetime1->diff($datetime2);
                            echo $interval->format('%R%a days');
                      }?>
                  </td>
                  <td>{{(!empty($row['jobDatePartsRecieved'])?date('m/d/Y',strtotime($row['jobDatePartsRecieved'])):'-')}}</td>
                  <td><?php if(!empty($row['jobDatePartsRecieved'])) {
                          $datetime1 = new DateTime($row['jobDatePartsRecieved']);
                          $datetime2 = new DateTime(date('Y-m-d'));
                          $interval = $datetime1->diff($datetime2);
                          echo $interval->format('%R%a days');
                      }else echo "-"; 
                      ?>
                  </td>
                  <td>{{(!empty($row['jobDateSchduled'])?date('m/d/Y',strtotime($row['jobDateSchduled'])):'-')}}</td>
                  <td>
                    <?php $notesRs = DB::select(DB::raw("SELECT *,(select name from gpg_employee where id = entered_by) as enterdBy FROM gpg_job_note WHERE gpg_job_id = '".$row['jobID']."' ORDER BY dated"));
                          foreach ($notesRs as $key => $value) {
                            $notesRow = (array)$value;
                            echo (!empty($notesRow['dated'])?date('m/d/Y',strtotime($notesRow['dated'])):'')."@".$notesRow['enterdBy'].": ".$notesRow['notes']."<br>";                         
                          }
                          if (empty($notesRs)) echo "-";
                    ?>
                  </td>
                </tr>
               @endforeach
              </tbody>
              </table><br/>
              <h4>GRAND TOTALS</h4>
              <table class="table table-bordered table-striped table-condensed cf" align="center">
                <thead>
                  <tr><th>Quoted Amount</th></tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{'$'.number_format($totalsData,2)}}</td>
                  </tr>
                </tbody>
              </table>
              {{ HTML::link("reports/servJobFSWRepExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
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
          $('#QuotedSDate').val("");
          $('#QuotedEDate').val("");
          $('#WonSDate').val("");
          $('#WonEDate').val("");
          $('#PartsOrderedSDate').val("");
          $('#PartsOrderedEDate').val("");
          $('#PartsRecievedSDate').val("");
          $('#PartsRecievedEDate').val("");
          $('#PartsScheduledSDate').val("");
          $('#PartsScheduledEDate').val("");
          $('#SJobNumber').val("");
          $('#EJobNumber').val("");
          $('#SFSWNumber').val("");
          $('#EFSWNumber').val("");
          $('#optEmployee').val("");
          $('#optCustomer').val("");
          $('#optJobStatus').val("");
          $('#FSWStatus').val("");
          $('#zone_index_val').val("");
          $('#order_by').val("");
          $('#orderby_type').val("");
          $('#ignore_date').val("");
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop