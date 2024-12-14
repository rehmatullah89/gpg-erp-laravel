                <?php $sDate = Input::get('SDate'); $eDate=Input::get('EDate');?>
                <span><b>Period Start Date:</b>{{!empty($sDate)?$sDate:date('Y-m-d')}}</span>
                <span style="margin-left:20px;"><b>Period End Date:</b>{{!empty($eDate)?$eDate:date('Y-m-d')}}</span>
                <section id="no-more-tables" style="padding:10px;">
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th> Type</th>
                        <th>Job Number</th>
                        <th>Job name/Location</th>
                        <th>Customer</th>
                        <th>Total Hours</th>
                        <th>Daily Cost</th>
                        <th>Net Invoice Ammount</th>
                        <th>Labour Cost</th>
                        <th>Material Cost</th>
                        <th>Total Cost</th>
                        <th>Margin($)</th>
                        <th>Margin(%)</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php  
                    $GTOTAL = 0;
                    $jobValue ="";
                    $showFlag = "";
                    $jobHoursArray  = array(); 
                    $display_hrs_total = 0;  
                    $employee = Input::get("employee");
                    $employeeType = Input::get("employeeType");
                    $employee_in = Input::get("employee_in");
                    $start = Input::get("SDate");
                    $end = Input::get("EDate");
                    $jNum = Input::get("jNum");
                    $tech_filter = Input::get("tech_flag");
                    $dbDateStart = $start;
                    $dbDateEnd = $end;
                    $reportView = Input::get('reportView');
                    $curRow = 1;
                    $totalsArray = array();
                    $breakDownArray = array();
                    $Dates = array();
                    for ($i = 1; $i <= $tDays; $i++) {
                      $DayInfo_s = DB::select(DB::raw("select ADDDATE('" .(!empty($dbDateStart)?$dbDateStart:date('Y-m-d')). "', INTERVAL " . ($i - 1) . " DAY) as int_days"));
                      if (!empty($DayInfo_s) && isset($DayInfo_s[0]->int_days)){
                        $DayInfo = $DayInfo_s[0]->int_days;
                      }
                      $Dates[] = $DayInfo;
                    }
                    foreach ($query_data as $key => $EmployeeJob_row){
                      $timetype = DB::table('gpg_timetype')->where('id','=',$EmployeeJob_row['timetypeId'])->pluck('name');
                      $THurs =0;
                      if ($reportView=="summary1") {
                        reset($partialArray);
                        while(list($key,$value) = each($partialArray)) {
                        $THurs=0;
                       ?>
                      <tr>
                        <td>{{$EmployeeJob_row['display_date']}}</td>
                        <td width="80" nowrap="nowrap" bgcolor="#F0F0F0"><font <?php echo ($EmployeeJob_row['empStatus']=='B'?"style=\"color:#F00;\"":"");?>>{{$EmployeeJob_row['empName']}}</font></td>
                        <td width="80" height="25" bgcolor="#F0F0F0" >{{"Summary"}}</td>
                        <td width="80" bgcolor="#F0F0F0">{{$key}}</td>
                        <td width="80" bgcolor="#F0F0F0">&nbsp;</td>
                        <td bgcolor="#F0F0F0">&nbsp;</td>
                        <?php for ($i=0; $i<count($Dates); $i++) {  ?>
                        <td align="center" nowrap="nowrap" bgcolor="<?php echo $color = (date("D",strtotime($Dates[$i]))=="Sat" || date("D",strtotime($Dates[$i]))=="Sun"?"#FFFFCC":"#FFFFFF"); ?>" >&nbsp;&nbsp;<?php 
                        $partialSummaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']]['LeaveHours'] += $holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]+$leavesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                        unset($holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                        unset($leavesArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                        $Hurs = $partialSummaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']][$key];
                        if ($Hurs!="") {
                          echo number_format($Hurs,2);
                          $totalsArray[$Dates[$i]] += number_format($Hurs,2);
                          $THurs+= round($Hurs,2);
                        } else {
                            echo '';
                        }?></td>
                        <?php } ?>
                        <td width="100" nowrap="nowrap" bgcolor="#F0F0F0" align="center"><?php echo ($THurs!=0?number_format($THurs,2)."H":"-");?></td>
                      </tr>
                      <?php
                        $curRow++;
                      } //end inner while loop
                        $curRow--;
                      } else {      
                      if ($reportView == "summary3") {
                        $showFlag = 0;
                        for ($i=0; $i<count($Dates); $i++) {  
                            if (date("D",strtotime($Dates[$i]))!="Sat" && date("D",strtotime($Dates[$i]))!="Sun"){
                            $chkHurs = $summaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']]+$holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]+$leavesArr[$Dates[$i]][$EmployeeJob_row['empId']];              
                            $offDay = $summaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                            if ($chkHurs=='' && empty($offDay))   $showFlag = 1; 
                          }
                        }
                      }
                      if (($showFlag==1 && $reportView == "summary3") || $reportView != "summary3") { 
                        $bgcolor_row_prevail="";
                        if($EmployeeJob_row['prevail']==1)
                          $bgcolor_row_prevail="#e6f4ff";
                        elseif($EmployeeJob_row['JobNum']=="" && ($EmployeeJob_row['timetypeId']==1 or $EmployeeJob_row['timetypeId']==2))
                          $bgcolor_row_prevail="#FFC1C1";
                        $jobEmpCount = 1;
                        $multipleTechHighlightRowColor = "";
                        if(!empty($techMultipleJobArray[$EmployeeJob_row['empId']])){ 
                          $cnt_array = array_count_values($techMultipleJobArray[$EmployeeJob_row['empId']]);
                          $jobEmpCount = $cnt_array[$EmployeeJob_row['JobNum']];  
                        }
                        $excel_highlight = false;
                        if($jobEmpCount >1){
                          $multipleTechHighlightRowColor = 'class = "highlight-data"';
                          $excel_highlight = true;  
                        }
                      ?>
                      <tr <?php echo $multipleTechHighlightRowColor?>>
                        <td bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>">
                        <?php $job_number = $EmployeeJob_row['JobNum'];
                        echo $EmployeeJob_row['display_date'];?>&nbsp;&nbsp;</td>
                        <td width="80" nowrap="nowrap" bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>"><font <?php echo ($EmployeeJob_row['empStatus']=='B'?"style=\"color:#F00;\"":"")?>>{{$EmployeeJob_row['empName']}}</font></td>
                        <td width="80" height="25" bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" ><?php  
                        if ($reportView == "summary2"){   
                            echo "Summary";
                        } else  {
                          if ($EmployeeJob_row['prevail']==1) { 
                                echo "<strong>PREVAILING/".$timetype."</strong>"; 
                          }
                          else{ 
                            echo $timetype;
                          }
                        }?></td>
                        <td width="80" bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>"><?php 
                        if ($reportView=="summary2" || $reportView=="summary3") {
                          echo "All Jobs";
                        } else { 
                          echo $EmployeeJob_row['JobNum'];
                        }?></td>
                        <td width="80" bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" >{{$EmployeeJob_row['location']}}</td>
                        <td bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" >{{$EmployeeJob_row['customer_name']}}</td>
                        <?php 
                        for ($i=0; $i<count($Dates); $i++) {  
                              switch ($reportView) {
                                case "summary2":
                                case "summary3":
                              $Hurs = $summaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']]+$holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]+$leavesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                              $display_hrs_total = 0;
                              unset($holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                              unset($leavesArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                              if ($Hurs!="") {
                                $totalsArray[$Dates[$i]] += number_format($Hurs,2);
                                $display_hrs = $Hurs;
                                $display_hrs_total += $Hurs;
                              if ($color=="#FFFFFF"){
                                $THurs+= round($Hurs,2);
                              }
                            } else {
                              $offDay = $summaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                              if (!empty($offDay)) {
                                $display_hrs = $offDay;
                              } else {
                                echo '';
                              }
                            }
                        break;
                        default: 
                        $Hurs = @$datesArr[$Dates[$i]][$EmployeeJob_row['empId']][$EmployeeJob_row['JobNum']][$EmployeeJob_row['time_type']]['hours'];
                        $timeType = @$datesArr[$Dates[$i]][$EmployeeJob_row['empId']][$EmployeeJob_row['JobNum']]['time_type'];
                        $jobType = @$datesArr[$Dates[$i]][$EmployeeJob_row['empId']][$EmployeeJob_row['JobNum']]['job_type'];
                        if ($jobType==4 && !preg_match('/P000/i',$EmployeeJob_row['JobNum']) && $timeType!=5 && $timeType!=6 && $timeType!=7 && !preg_match('/SHOP/i',$EmployeeJob_row['JobNum']) && !preg_match('/Vacation/',$EmployeeJob_row['JobNum']) && !preg_match('/Sick/',$EmployeeJob_row['JobNum'])) {
                          @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['SERVICE'] +=$Hurs;
                          @$breakDownArray['BreakDown']['SERVICE'] +=$Hurs;
                          $display_hrs = $Hurs;
                          $display_hrs_total += $Hurs;
                        }      
                        if ($jobType==5 && !preg_match('/P000/i',$EmployeeJob_row['JobNum']) && $timeType!=5 && $timeType!=6 && $timeType!=7 && !preg_match('/SHOP/i',$EmployeeJob_row['JobNum']) && !preg_match('/Vacation/',$EmployeeJob_row['JobNum']) && !preg_match('/Sick/i',$EmployeeJob_row['JobNum'])) {
                          @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['ELECTRICAL'] +=$Hurs;
                          @$breakDownArray['BreakDown']['ELECTRICAL'] +=$Hurs;
                          $display_hrs = $Hurs;
                        }    
                        if ($timeType==5 && empty($EmployeeJob_row['JobNum'])) { 
                          @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['OTHER'] +=$Hurs;
                          @$breakDownArray['BreakDown']['OTHER'] +=$Hurs;
                          $display_hrs = $Hurs;
                          $display_hrs_total += $Hurs;
                        }    
                        if ($timeType==6 && empty($EmployeeJob_row['JobNum'])) { 
                          @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['HOLIDAY'] +=$Hurs;
                          @$breakDownArray['BreakDown']['HOLIDAY'] +=$Hurs;
                          $display_hrs = $Hurs;
                          $display_hrs_total += $Hurs;
                        }    
                        if ($timeType==7 && empty($EmployeeJob_row['JobNum'])) { 
                          @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['VACATION'] +=$Hurs;
                          @$breakDownArray['BreakDown']['VACATION'] +=$Hurs;
                          $display_hrs = $Hurs;
                          $display_hrs_total += $Hurs;
                        }    
                        if (preg_match('/SHOP/i',$EmployeeJob_row['JobNum'])) { 
                          @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['SHOP'] +=$Hurs;
                          @$breakDownArray['BreakDown']['SHOP'] +=$Hurs;
                          $display_hrs = $Hurs;
                          $display_hrs_total += $Hurs;
                        }    
                        if (preg_match('/Vacation/i',$EmployeeJob_row['JobNum'])) {  
                          @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['VACATION'] +=$Hurs;
                          @$breakDownArray['BreakDown']['VACATION'] +=$Hurs;
                          $display_hrs = $Hurs;
                          $display_hrs_total += $Hurs;
                        }    
                        if (preg_match('/Sick/i',$EmployeeJob_row['JobNum'])) { 
                          @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['SICK'] +=$Hurs;
                          @$breakDownArray['BreakDown']['SICK'] +=$Hurs;
                          $display_hrs = $Hurs;
                          $display_hrs_total += $Hurs;
                        }
                        if (preg_match('/P000/',$EmployeeJob_row['JobNum'])) {
                          @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['PJOBS'] +=$Hurs; 
                          @$breakDownArray['BreakDown']['PJOBS'] +=$Hurs; 
                          $display_hrs = $Hurs;
                          $display_hrs_total += $Hurs;
                        }      
                        @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['TOTALHOURS'] +=$Hurs;
                        @$jobHoursArray[(empty($EmployeeJob_row['JobNum'])?" ":$EmployeeJob_row['JobNum'])] += $Hurs ;
                        if ($Hurs!="") {      
                          $display_hrs = $Hurs;
                          $display_hrs_total += $Hurs;
                          $THurs+= round($Hurs,2);      
                        } else {
                        if ($timeType==8 && $jobValue==''){
                          echo '';
                          $display_hrs = 'Off';
                        }else { 
                          if (isset($holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]) && $holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]=="") {
                            $LeaveHurs = $leavesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                            unset($leavesArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                            $totalsArray[$Dates[$i]] += number_format($LeaveHurs,2);
                            @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['SICK'] +=$LeaveHurs;
                            @$breakDownArray['BreakDown']['SICK'] +=$LeaveHurs;
                            echo '';
                            $THurs+= round($LeaveHurs,2);
                          } else {
                            @$HolidayHurs = $holidayArr[$Dates[$i]][$EmployeeJob_row['empId']];
                            unset($holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                            @$totalsArray[$Dates[$i]] += number_format($HolidayHurs,2);
                            @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['HOLIDAY'] +=$HolidayHurs;
                            @$breakDownArray['BreakDown']['HOLIDAY'] +=$HolidayHurs;
                            echo '';
                            $THurs+= round($HolidayHurs,2);
                          }
                        }
                        unset($timeType);
                      }
                    } // end swtich 
                    ?>
                  <?php   }  ?>
                      <td width="100" nowrap="nowrap" bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" align="center"><?php
                      $daily_cost = 0;
                      if($display_hrs != "Off" && $display_hrs !=0){
                        $display_hrs = number_format($display_hrs,2)."H";
                        if($EmployeeJob_row['labor_rate'] != ""){
                          $daily_cost = $display_hrs * $EmployeeJob_row['labor_rate'];
                        }
                      } else if($display_hrs == "Off") {
                        $display_hrs = $display_hrs; 
                      } else {
                        $display_hrs = "0";
                      }
                      echo $display_hrs;
                      ?></td>
                      <td width="100" nowrap="nowrap" bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" align="center">{{"$".number_format($daily_cost,2)}}</td>
                      <?php
                      $labour_cost = 0;
                      $material_cost = 0;
                      $total_cost = 0;
                      $margin = 0;
                      $margin = 0;
                      $margin_percentage = 0;
                      if($jobType == 5){
                      $costingQuery = DB::select(DB::raw("SELECT gpg_sales_tracking.material_cost, gpg_sales_tracking.labor_cost 
                                FROM gpg_job 
                                INNER JOIN gpg_sales_tracking_job ON gpg_job.id = gpg_sales_tracking_job.gpg_job_id
                                INNER JOIN gpg_sales_tracking ON gpg_sales_tracking_job.gpg_sales_tracking_id = gpg_sales_tracking.id
                                WHERE gpg_job.job_num ='$job_number'")); 
                        foreach ($costingQuery as $key => $value5){
                          $getJobCiostRow = (array)$value5;
                          $material_cost = $getJobCiostRow['material_cost']; 
                          $labour_cost = $getJobCiostRow['labor_cost']; 
                        }
                      }?>
                      <td  bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" >
                      <?php
                      $invoiceData = explode("#~#",$EmployeeJob_row['invoice_data']);
                      $invoice_amount = 0;  
                      if(isset($invoiceData[1])){  
                        $invoice_amount = $invoiceData[1];
                      }else {
                        $invoice_amount = 0;
                      }
                      echo  '$'.number_format($invoice_amount,2);
                      ?></td>
                      <td bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" >
                      <?php $labor_cost = $EmployeeJob_row['labor_cost'];
                      echo  '$'.number_format($labor_cost,2);
                      ?></td>
                      <td bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" >
                      <?php $material_cost0 = DB::select(DB::raw("SELECT SUM(IFNULL(amount,0)) as t_sum FROM gpg_job_cost WHERE job_num = '".$job_number."'"));
                         $material_cost =  @$material_cost0[0]->t_sum;
                      echo  '$'.number_format($material_cost,2);
                      ?></td>
                      <td  bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" ><?php
                        $total_cost = $labor_cost + $material_cost;
                        echo  '$'.number_format($total_cost,2);
                      ?></td>
                      <td  bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" ><?php
                      $invAmt = 0;
                      if(isset($invoiceData[1]) && isset($invoiceData[3])){
                        $invAmt = ($invoiceData[1] - $invoiceData[3]);  
                      }
                      $net_margin = 0;
                      if($invAmt > 0){
                        $net_margin = $invAmt-$total_cost;
                      }
                      echo '$'.number_format(($net_margin),2);
                      ?></td>
                      <td  bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" ><?php
                      $net_margin_percent = "0";
                      if($net_margin >0){
                        $net_margin_percent = ($net_margin/100);
                      }
                      echo number_format($net_margin_percent,2)."%";
                      ?></td>
                    </tr>
                      <?php
                      }  // end summary3 check
                    } 
                      $curRow++;
                    }?>
                    <tr>
                      <td bgcolor="#FFFFCC" colspan="7" nowrap="nowrap" height="25px"><strong>T O T A L S</strong></td>
                      <td width="100" nowrap="nowrap" bgcolor="#FFFFCC"  align="center"><strong><?php echo round($display_hrs_total,2);  ?>H</strong></td>
                      <td colspan="6" nowrap="nowrap" bgcolor="#FFFFCC"  align="center"></td>
                    </tr>
                  </tbody>
                </table>
              </section>
              <section id="no-more-tables">
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable1" align="center">
                    <thead>
                      <tr>
                        <th colspan="9" bgcolor="#FFFFCC">Job Hour Stats <strong>T O T A L S</strong></th>
                      </tr>
                      <tr>
                         <th align="center" bgcolor="#FFFFCC">Total Hours </th>
                         <th align="center" bgcolor="#FFFFCC">Electrical Work</th>
                         <th align="center" bgcolor="#FFFFCC">Service Work</th>
                         <th align="center" bgcolor="#FFFFCC">Shop</th>
                         <th align="center" bgcolor="#FFFFCC">P's</th>
                         <th align="center" bgcolor="#FFFFCC">Sick Leave</th>
                         <th align="center" bgcolor="#FFFFCC">Vacation </th>
                         <th align="center" bgcolor="#FFFFCC">Holiday </th>
                         <th align="center" bgcolor="#FFFFCC">Other</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                       <td height="20"  style="padding-bottom:10.5px" align="center" bgcolor="#FFFFFF"><strong>{{round($GTOTAL,2)}}</strong></td>
                       <td align="center" style="padding-bottom:10.5px" bgcolor="#FFFFFF"><strong>{{round(@$breakDownArray['BreakDown']['ELECTRICAL'],2)}}</strong></td>
                       <td align="center" style="padding-bottom:10.5px" bgcolor="#FFFFFF"><strong>{{round(@$breakDownArray['BreakDown']['SERVICE'],2)}}</strong></td>
                       <td align="center" style="padding-bottom:10.5px" bgcolor="#FFFFFF"><strong>{{round(@$breakDownArray['BreakDown']['SHOP'],2)}}</strong></td>
                       <td align="center" style="padding-bottom:10.5px" bgcolor="#FFFFFF"><strong>{{round(@$breakDownArray['BreakDown']['PJOBS'],2)}}</strong></td>
                       <td align="center" style="padding-bottom:10.5px" bgcolor="#FFFFFF"><strong>{{round(@$breakDownArray['BreakDown']['SICK'],2)}}</strong></td>
                       <td align="center" style="padding-bottom:10.5px" bgcolor="#FFFFFF"><strong>{{round(@$breakDownArray['BreakDown']['VACATION'],2)}}</strong></td>
                       <td align="center" style="padding-bottom:10.5px" bgcolor="#FFFFFF"><strong>{{round(@$breakDownArray['BreakDown']['HOLIDAY'],2)}}</strong></td>
                       <td align="center" style="padding-bottom:10.5px" bgcolor="#FFFFFF"><strong>{{round(@$breakDownArray['BreakDown']['OTHER'],2)}}</strong></td>
                     </tr>
                    </tbody>
                  </table>
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable1" align="center">
                    <thead>
                      <tr>
                        <th colspan="18" bgcolor="#FFFFCC"><strong>Break Down Calculation</strong></th>
                      </tr>
                      <tr>
                        <th height="20" align="center" bgcolor="#FFFFCC">Employee</th>
                        <th align="center" bgcolor="#FFFFCC">P's</th>
                        <th align="center" bgcolor="#FFFFCC">Rate </th>
                        <th align="center" bgcolor="#FFFFCC">Cost</th>
                        <th align="center" bgcolor="#FFFFCC">% Break Down </th>
                        <th align="center" bgcolor="#FFFFCC">Billable</th>
                        <th align="center" bgcolor="#FFFFCC">Margin</th>
                        <th align="center" bgcolor="#FFFFCC">Margin % </th>
                        <th align="center" bgcolor="#FFFFCC">Rate</th>
                        <th align="center" bgcolor="#FFFFCC">Burden</th>
                        <th align="center" bgcolor="#FFFFCC">Total</th>
                        <th align="center" bgcolor="#FFFFCC">Employee</th>
                        <th align="center" bgcolor="#FFFFCC">Hrs Worked</th>
                        <th align="center" bgcolor="#FFFFCC">% Billable</th>
                        <th align="center" bgcolor="#FFFFCC">Non Billable Hrs</th>
                        <th align="center" bgcolor="#FFFFCC">New Burden</th>
                        <th align="center" bgcolor="#FFFFCC">Burden Diff</th>
                        <th align="center" bgcolor="#FFFFCC">Increase Burden %</th>
                       </tr>
                    </thead>
                    <tbody>
                     <?php
                      $tRate =0;
                      $tCost =0;
                      $sumBurden =0;
                      $sumTHrs =0;
                      $sumTotalBurden =0;
                      $tBillable = 0;
                      $precBillable = 0;
                      $sumNewBurden = 0;
                      $sumDiffBurden = 0;
                      $breakRowStart = $curRow+4;
                      $breakRowEnd = $curRow+4+count(@$breakDownArray['BreakDown']);
                      while (list($empId,$val)=@each($breakDownArray['BreakDown'])) { 
                        if (intval($empId)) {
                          $empRate = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$empId)->where('type','=','h')->where('start_date','<=',date('Y-m-d',strtotime($dbDateEnd)))->orderBy('start_date','DESC')->pluck('rate');
                          $empBurden = DB::table('gpg_employee_burden')->where('gpg_employee_id','=',$empId)->where('start_date','<=',date('Y-m-d',strtotime($dbDateEnd)))->orderBy('start_date','DESC')->pluck('burden');
                          $empName = DB::table('gpg_employee')->where('id','=',$empId)->pluck('name');
                          $empStatus = DB::table('gpg_employee')->where('id','=',$empId)->pluck('status');
                          $ps = @$val['PJOBS'];
                          $tHrs= @$val['TOTALHOURS'] ;
                          $totalBurden = $empRate+$empBurden;
                          $precBillable = @(($tHrs - $ps)/$tHrs);
                          $newBurden = @($totalBurden/$precBillable);
                          $diffBurden = $newBurden-$totalBurden;
                          $cost = $ps*$empRate; 
                          $breakDown = @($ps/$breakDownArray['BreakDown']['PJOBS'])*100;
                          $billable = $ps*85;
                          $tRate += $empRate;
                          $tCost += $cost;
                          $tBillable += $billable;
                          $sumBurden += $empBurden;
                          $sumTHrs += $tHrs;
                          $sumTotalBurden += $totalBurden;
                          $sumNewBurden += $newBurden;
                          $sumDiffBurden += $diffBurden;
                          $curRow++;
                        ?>
                      <tr>
                        <td align="center"  height="20" bgcolor="#FFFFCC"><font color="<?php echo ( $empStatus=='B'?"#FF0000":"")?>"><strong>{{$empName}}</strong></font></td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($ps,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($empRate,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{'$'.number_format($cost,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($breakDown,2)}}%</td>
                        <td align="center" bgcolor="#FFFFFF">{{'$'.number_format($billable,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($empRate,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($empBurden,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($totalBurden,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF"><font color="<?php echo ( $empStatus=='B'?"#FF0000":"")?>"><strong>{{$empName}}</strong></font></td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($tHrs,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format(100*$precBillable,2)}}%</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($ps,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($newBurden,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format($diffBurden,2)}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format(100*@($diffBurden/$totalBurden),2)}}%</td>
                      </tr>
                      <?php 
                        }
                      }?>
                      <tr>
                         <td align="center"  height="20" bgcolor="#FFFFCC"><strong>T O T A L S</strong></td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format(@$breakDownArray['BreakDown']['PJOBS'],2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format($tRate,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format($tCost,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                         <td align="center" bgcolor="#FFFFFF">{{'$'.number_format($tBillable,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">{{'$'.number_format($tBillable-$tCost,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">{{@number_format(($tBillable-$tCost)/$tBillable*100,2)}}%</td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format($tRate,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format($sumBurden,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format($sumTotalBurden,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format($sumTHrs,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format(@$breakDownArray['BreakDown']['PJOBS'],2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format($sumNewBurden,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">{{number_format($sumNewBurden,2)}}</td>
                         <td align="center" bgcolor="#FFFFFF">&nbsp;</td>    
                      </tr>
                    </tbody>
                  </table>
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable1" align="center">
                    <thead>
                      <tr>
                        <th colspan="3" bgcolor="#FFFFCC"><strong>Job Total Hours</strong></th>
                      </tr>
                      <tr>
                         <th align="center" bgcolor="#FFFFCC">Job Number </th>
                         <th align="center" bgcolor="#FFFFCC">Hours</th>
                         <th bgcolor="#FFFFCC">%age</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $totalJobHours =0;
                      $breakRowStart = $curRow+4;
                      $breakRowEnd = $curRow+4+count($jobHoursArray);
                       while (list($jobNum,$val)=@each($jobHoursArray)) { 
                        if ($jobNum) {
                          $totalJobHours += $val;
                          $curRow++;
                      ?>
                      <tr>
                        <td align="center"  height="20" bgcolor="#FFFFCC"><strong>{{$jobNum}}</strong></td>
                        <td align="center" bgcolor="#FFFFFF">{{$val}}</td>
                        <td align="center" bgcolor="#FFFFFF">{{number_format((100*@($val/$GTOTAL)),2)}}%</td>
                      </tr>
                      <?php 
                      }
                    }?>
                    <tr>
                      <td align="center"  height="20" bgcolor="#FFFFCC"><strong>T O T A L S</strong></td>
                      <td align="center" bgcolor="#FFFFFF">{{number_format($totalJobHours,2)}}</td>
                      <td  bgcolor="#FFFFFF">&nbsp;</td>
                    </tr>
                   </tbody>
                  </table>
                </section>