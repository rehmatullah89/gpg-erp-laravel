@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<?php
function convertTime($vtime){
     if ($vtime!="") {
       $ptr = ":";
       $v1 = explode($ptr,$vtime);
       $vtime = $v1[0]+($v1[1]/60);
     }
       return round($vtime,2);
  }
function get_time_difference( $start, $end )
  {
    
      $uts['start']      =    strtotime( $start );
      $uts['end']        =    strtotime( $end );
    if( $uts['start']!==-1 && $uts['end']!==-1 )
      {
      if( $uts['end'] >= $uts['start'] )
          {
              $diff    =    $uts['end'] - $uts['start'];
              if( $days=intval((floor($diff/86400))) )
                  $diff = $diff % 86400;
              if( $hours=intval((floor($diff/3600))) )
                  $diff = $diff % 3600;
              if( $minutes=intval((floor($diff/60))) )
                  $diff = $diff % 60;
              $diff    =    intval( $diff );            
              return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
          }
          else
          {
        
              $uts['start']      =    strtotime( $start );
          $uts['end']        =    strtotime( $end );
        $abc = $uts['end']+86400;
        $diff    =    ($abc - $uts['start']);
              if( $days=intval((floor($diff/86400))) )
                  $diff = $diff % 86400;
              if( $hours=intval((floor($diff/3600))) )
                  $diff = $diff % 3600;
              if( $minutes=intval((floor($diff/60))) )
                  $diff = $diff % 60;
              $diff    =    intval( $diff );            
              return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
          }
      }
      else
      {
          trigger_error( "Invalid date/time data detected", E_USER_WARNING );
      }
      return( false );
  }

?>
              <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
               EMPLOYEE REPORT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH by:</b> Dates / Filter / Employee Type</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/emp_reportopt'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                     <th>
                                      {{Form::label('SDate', 'Start Date:*', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                     <th>
                                        {{Form::label('EDate', 'End Date:*', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                     </th>
                                      <th><b>Filter</b></th>
                                      <th><b>Employee Type</b></th>
                                    </tr>
                                  </thead>
                                  <tbody><tr>
                                  <td data-title="Job Time Start Date:">
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate','required')) }}
                                   </td><td data-title="End Date:">
                                   {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate','required')) }}
                                   </td>
                                    <td data-title="employee:">
                                      {{Form::select('employee',$salesp_arr,'', ['id' => 'employee', 'class'=>'form-control m-bot15','required'])}}
                                    </td>
                                    <td data-title="employee_type:">
                                      {{Form::select('employee_type',array(''=>'Select Employee Type')+$emp_type,'', ['id' => 'employee_type', 'class'=>'form-control m-bot15'])}}
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
                <?php $sDate = Input::get('SDate'); $eDate=Input::get('EDate');?>
                <span><b>Employee Name:</b>{{$getEmpInfo[0]->name}}</span><br/>
                <span><b>Period Start Date:</b>{{!empty($sDate)?$sDate:date('Y-m-d')}}</span>
                <span style="margin-left:20px;"><b>Period End Date:</b>{{!empty($eDate)?$eDate:date('Y-m-d')}}</span>
                <span style="margin-left:40px;"><b>Employee Email:</b>{{$getEmpInfo[0]->email}}</span>
                <span style="margin-left:60px;"><b>Employee Phone:</b>{{$getEmpInfo[0]->phone}}</span>
                <section id="flip-scroll" style="padding:10px;">
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Day</th>
                        <th>Date</th>
                        <th>Job Type</th>
                        <th>Job Number</th>
                        <th>Job name/Location</th>
                        <th>Customer</th>
                        <th> Time In</th>
                        <th>Time Out</th>
                        <th>Sick</th>
                        <th>Vacation</th>
                        <th>Holiday</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php       
                        $curRow = 4;
                        $prev="";
                        $srow = 3;
                        $scol = 0;
                        $employee = Input::get("employee");
                        $employee_type = Input::get("employee_type"); 
                        $start = Input::get("SDate");
                        $end = Input::get("EDate");
                        $dbDateStart = date('Y-m-d',strtotime($start));
                        $dbDateEnd = date('Y-m-d',strtotime($end));
                        for ($ii=0; $ii<=$tDays; $ii++) {
                          $THurs =0;
                          $DayInfo = '';
                          $DayInfo_s = DB::select(DB::raw("select ADDDATE('".$dbDateStart."', INTERVAL ".($ii-1)." DAY) as d_info"));
                          if (!empty($DayInfo_s) && isset($DayInfo_s[0]->d_info)) {
                            $DayInfo = $DayInfo_s[0]->d_info;
                          }
                          $wagesQuery = DB::select(DB::raw("select *,(select name from gpg_customer where id = c.gpg_customer_id) as customer_name,c.location, if(b.job_number!='NULL',1,0) as prevail from gpg_timesheet_detail a 
                              LEFT JOIN gpg_job_rates b on (a.gpg_task_type = b.gpg_task_type and a.job_num = b.job_number and b.status = 'A' and b.GPG_employee_type_id = '".$employee."')
                              INNER JOIN gpg_job c on (a.gpg_job_id = c.id) where GPG_timesheet_id = '".@$datesArr[$DayInfo]['t_id']."'  order by a.time_in"));
                          $NumRec  = count($wagesQuery);
                          if($NumRec > 0 && $num_records_emp > 0) {
                          ?>
                          <tr>
                            <td nowrap="nowrap" bgcolor="#CCCCCC" colspan="2"><strong>{{date("l",strtotime($DayInfo))}}</strong></td>
                            <td height="25" nowrap="nowrap" bgcolor="#F0F0F0"><strong>{{date('m/d/Y',strtotime($DayInfo))}}</strong></td>
                            <td bgcolor="#999999" nowrap="nowrap">&nbsp;</td>                     
                            <td nowrap="nowrap" bgcolor="#999999" align="center">&nbsp;</td>
                            <td bgcolor="#999999" align="center">&nbsp;</td>
                            <td bgcolor="#999999" align="center">&nbsp;</td>
                            <td bgcolor="#999999" nowrap="nowrap">&nbsp;</td>
                            <td align="center"  bgcolor="#999999" >&nbsp;</td>
                            <td nowrap="nowrap" bgcolor="#999999" align="center">&nbsp;</td>
                            <td nowrap="nowrap" bgcolor="#999999" align="center">&nbsp;</td>
                            <td nowrap="nowrap" bgcolor="#999999" align="center">&nbsp;</td>
                            <td width="50" align="center" nowrap="nowrap" bgcolor="#F0F0F0"><strong><?php
                            echo convertTime($datesArr[$DayInfo]['t_jobs_time'])."H";
                            if ($prev!=""){
                              echo "-";
                            }
                            $prev = $srow+$curRow;?></strong></td>
                        </tr>
                        <?php 
                          $preTimesheetId ='';
                          foreach ($wagesQuery as $key => $value4){
                            $wagesRow = (array)$value4;
                            $curRow++; 
                            $rowChk = 1;
                        ?>
                      <tr>
                        <td  nowrap="nowrap" bgcolor="#CCCCCC">{{date("l",strtotime($DayInfo))}}</td>
                        <td  height="25" nowrap="nowrap" bgcolor="#F0F0F0">{{date('m/d/Y',strtotime($DayInfo))}}</td>
                        <td align="center" nowrap="nowrap" bgcolor="#F0F0F0">{{DB::table('gpg_timetype')->where('id','=',$wagesRow['GPG_timetype_id'])->pluck('name')}}</td>
                        <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">{{$wagesRow['job_num']}}</td>
                        <td bgcolor="#F0F0F0" align="center"><?php if($wagesRow['job_num']!=''){ echo $wagesRow['location'];}?></td>
                        <td bgcolor="#F0F0F0" align="center"><?php if($wagesRow['job_num']!='') { echo $wagesRow['customer_name']; }?></td>
                        <td align="center" nowrap="nowrap" bgcolor="#F0F0F0"><?php echo ($wagesRow['GPG_timetype_id']=='8')?'':date('H:i',strtotime($wagesRow['time_in'])); ?></td>
                        <td align="center" nowrap="nowrap" bgcolor="#F0F0F0" ><?php echo ($wagesRow['GPG_timetype_id']=='8')?'':date('H:i',strtotime($wagesRow['time_out'])); ?></td>
                        <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                        <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                        <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                        <td align="center" nowrap="nowrap" bgcolor="#F0F0F0"><?php  $timearray = get_time_difference($wagesRow['time_in'], $wagesRow['time_out']);
                        echo convertTime($timearray['hours'].":".$timearray['minutes'])."H"; ?></td>
                      </tr>
                      <?php }  
                        $curRow++;
                       } // end if NumRec
                      } // end for loop dates ?>
                    </tbody>
                  </table>
                   {{ HTML::link("reports/excelEmpRepExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
                  <br/>
                  {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
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
              $('#SDate').val("");
              $('#EDate').val("");
              $('#jNum').val("");
              $('#employee').val(null);
          });
        </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop