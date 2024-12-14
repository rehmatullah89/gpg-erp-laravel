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
                 GENERAL REPORT/MISSING HOURS REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH by:</b> Dates / Employee Type</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/missing_hour_report'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                     <th>
                                      {{Form::label('SDateCreatedMissing', 'Created Date Start :*', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                     <th>
                                        {{Form::label('EDateCreatedMissing', 'Created Date End:*', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                      <th><b>Employee Type</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr>
                                  <td data-title="Job Time Start Date:">
                                    {{ Form::text('SDateCreatedMissing','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDateCreatedMissing','required')) }}
                                   </td><td data-title="End Date:">
                                   {{ Form::text('EDateCreatedMissing','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDateCreatedMissing','required')) }}
                                   </td>
                                    <td data-title="Employee Type:">
                                   <div>
                                      {{Form::select('optEmployeeType[]',array(''=>'ALL')+$emp_types,'', ['id' => 'optEmployeeType', 'class'=>'form-control m-bot15','multiple'])}}
                                    </div>
                                    </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <br/>
                                  {{Form::submit('Generate Report', array('class' => 'btn btn-success', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
                                  </section>
                               {{ Form::close() }}
              </section>
                <section id="no-more-tables" style="padding:10px;">
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Employee Name</th>
                        <th>Employee Type </th>
                        <th>Missing Hours Date</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php
                      $SDateCreated = Input::get("SDateCreatedMissing");
                      $EDateCreated = Input::get("EDateCreatedMissing");
                      $dbDateStart = date('Y-m-d',strtotime($SDateCreated));
                      $dbDateEnd = date('Y-m-d',strtotime($EDateCreated));
                      $colcount=0;
                      foreach ($query_data as $key => $EmployeeJob_row){
                          $THurs =0;
                          $missingDates = array();
                          for ($i=1; $i<=$tDays; $i++) {  
                              $DayInfo_arr = DB::select(DB::raw("select ADDDATE('".$dbDateStart."', INTERVAL ".($i-1)." DAY) as t_day"));
                              $DayInfo = '';
                              if (!empty($DayInfo_arr) && isset($DayInfo_arr[0]->t_day))
                                $DayInfo = $DayInfo_arr[0]->t_day;
                              if (date("D",strtotime($DayInfo))!="Sat" && date("D",strtotime($DayInfo))!="Sun") {
                                $chkHurs = @$summaryDatesArr[$DayInfo][$EmployeeJob_row['empId']]+@$holidayArr[$DayInfo][$EmployeeJob_row['empId']]+@$leavesArr[$DayInfo][$EmployeeJob_row['empId']];              
                                $offDay = @$summaryDatesArr[$DayInfo][$EmployeeJob_row['empId']];
                                if ($chkHurs=='' && empty($offDay) && $DayInfo >= $EmployeeJob_row['empCreatedDate'])   
                                  $missingDates[] = date('m/d/Y',strtotime($DayInfo)); 
                              }
                          } 
                          if (count($missingDates)>0) {
                             $colcount++;
                          ?>
                          <tr  bgcolor="<?php echo ($colcount%2==0?"#FFFFCC":"#FFFFFF"); ?>">
                          <td height="30" valign="top" >&nbsp;<?php echo $EmployeeJob_row['empName'] ?></td>
                          <td valign="top" >&nbsp;<?php echo $EmployeeJob_row['emp_type'] ?></td>
                          <td ><font color="#c10000" style="font-weight:bold;"><?php 
                                  for ($feC =0; $feC<count($missingDates); $feC++) {
                                    echo "<p>".$missingDates[$feC]."</p>"; 
                                  }
                          ?></font>
                          </td>
                      </tr>
                      <?php 
                        unset($missingDates);
                        } 
              }
              ?>
                    </tbody>
                  </table>
                  {{ HTML::link("reports/excelMissingHourReportExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
                </section>     
              </section>
              </div>
              </div>
              <!-- page end-->
       <script>
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
          
          $('#reset_search_form').click(function(){
              $('#SDateCreatedMissing').val("");
              $('#EDateCreatedMissing').val("");
              $('#optEmployeeType').val(null);
          });
        </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop