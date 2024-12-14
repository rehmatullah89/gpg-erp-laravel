                <?php $sDate = Input::get('SDate'); $eDate=Input::get('EDate');?>
                <span><b>Period Start Date:</b>{{!empty($sDate)?$sDate:date('Y-m-d')}}</span>
                <span style="margin-left:20px;"><b>Period End Date:</b>{{!empty($eDate)?$eDate:date('Y-m-d')}}</span>
                <section id="flip-scroll" style="padding:10px;">
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th> Job Number</th>
                        <th>Job name/Location</th>
                        <th>Customer</th>
                        <?php 
                          $jobValue = '';
                          $showFlag = "";
                          $bgcolor_row_prevail="";
                          $jobHoursArray  = array();
                          $employee = Input::get("employee");
                          $employeeType = Input::get("employeeType");
                          $employee_in = Input::get("employee_in");
                          $jNum = Input::get("jNum");
                          $dbDateStart = Input::get("SDate");
                          $dbDateEnd = Input::get("EDate");
                          $reportView = Input::get('reportView');
                          $DayInfo = '';
                          for ($i = 1; $i <= $tDays; $i++) {
                            $DayInfo_s = DB::select(DB::raw("select ADDDATE('" .(!empty($dbDateStart)?$dbDateStart:date('Y-m-d')). "', INTERVAL " . ($i - 1) . " DAY) as int_days"));
                            if (!empty($DayInfo_s) && isset($DayInfo_s[0]->int_days)){
                              $DayInfo = $DayInfo_s[0]->int_days;
                            }
                            ?>
                            <th align="center" nowrap="nowrap" bgcolor="<?php echo (date("D", strtotime($DayInfo)) == "Sat" || date("D", strtotime($DayInfo)) == "Sun" ? "#FFFFCC" : "#FFFFFF"); ?>" ><strong>
                            <?php
                                $Dates[] = $DayInfo;
                                echo date('Y-m-d', strtotime($DayInfo));
                            ?></strong></th><?php } ?>
                        <th>Total Hours</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $curRow = 1;
                      $totalsArray = array();
                      $breakDownArray = array();
                      foreach ($query_data as $key => $EmployeeJob_row){
                        $timetype = DB::table('gpg_timetype')->where('id','=',$EmployeeJob_row['timetypeId'])->pluck('name');
                        $THurs =0;
                        if ($reportView=="summary1" && isset($partialArray) && !empty($partialArray)) {
                          reset($partialArray);
                          while(list($key,$value) = each($partialArray)) {
                              $THurs=0;
                        ?>
                        <tr>
                          <td nowrap="nowrap" bgcolor="#F0F0F0"><font <?php echo ($EmployeeJob_row['empStatus']=='B'?"style=\"color:#F00;\"":"");?>>{{$EmployeeJob_row['empName']}}</font></td>
                          <td bgcolor="#F0F0F0" >{{"Summary"}}</td>
                          <td bgcolor="#F0F0F0">{{$key}}</td>
                          <td bgcolor="#F0F0F0">&nbsp;</td>
                          <td bgcolor="#F0F0F0">&nbsp;</td>
                          <?php for ($i=0; $i<count($Dates); $i++) {  ?>
                          <td align="center" nowrap="nowrap" style="padding-bottom:10px" bgcolor="<?php  echo $color = (date("D",strtotime($Dates[$i]))=="Sat" || date("D",strtotime($Dates[$i]))=="Sun"?"#FFFFCC":"#FFFFFF"); ?>" >&nbsp;&nbsp;<?php  
                            $partialSummaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']]['LeaveHours'] += $holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]+$leavesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                            unset($holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                            unset($leavesArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                            $Hurs = $partialSummaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']][$key];
                            if ($Hurs!="") {
                              echo number_format($Hurs,2);
                              @$totalsArray[$Dates[$i]] += number_format($Hurs,2);
                              $THurs+= round($Hurs,2);
                            } else {
                              echo "-";
                            }?>&nbsp;&nbsp;</td>
                            <?php  } ?>
                            <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">{{($THurs!=0?number_format($THurs,2)."H":"-")}}</td>
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
                                  $chkHurs = @$summaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']]+@$holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]+@$leavesArr[$Dates[$i]][$EmployeeJob_row['empId']];              
                                  $offDay = @$summaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']];
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
                          ?>
                          <tr>
                            <td nowrap="nowrap" bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>"><font <?php echo ($EmployeeJob_row['empStatus']=='B'?"style=\"color:#F00;\"":"");?>>{{$EmployeeJob_row['empName']}}</font></td>
                            <td bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" ><?php   
                            if ($reportView == "summary2")  {   
                              echo "Summary";
                            } else  {
                             if ($EmployeeJob_row['prevail']==1) { 
                                    echo "<strong>PREVAILING/".$timetype."</strong>"; 
                              }
                              else{ 
                                echo $timetype;
                              }
                            }?></td>
                            <td bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>"><?php  
                            if ($reportView=="summary2" || $reportView=="summary3") {
                                echo "All Jobs";
                            } else { 
                               echo $EmployeeJob_row['JobNum'];
                            } 
                            ?></td>
                            <td bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" >{{$EmployeeJob_row['location']}}</td>
                            <td bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" >{{$EmployeeJob_row['customer_name']}}</td>
                            <?php   for ($i=0; $i<count($Dates); $i++) {  ?>
                            <td align="center" nowrap="nowrap" bgcolor="<?php  echo $color = ((date("D",strtotime($Dates[$i]))=="Sat" || date("D",strtotime($Dates[$i]))=="Sun"?"#FFFFCC":($bgcolor_row_prevail?$bgcolor_row_prevail:"#FFFFFF"))); ?>" >&nbsp;&nbsp;<?php  
                            switch ($reportView) {
                              case "summary2":
                              case "summary3":
                                $Hurs = @$summaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']]+@$holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]+@$leavesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                                unset($holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                                unset($leavesArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                                if ($Hurs!="") {
                                  echo number_format($Hurs,2);
                                  @$totalsArray[$Dates[$i]] += number_format($Hurs,2);
                                  $THurs+= round($Hurs,2);
                               } else {
                                $offDay = @$summaryDatesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                                if (!empty($offDay)) {
                                  echo $offDay;
                                } else {
                                  echo "-";
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
                                }      
                                if ($jobType==5 && !preg_match('/P000/i',$EmployeeJob_row['JobNum']) && $timeType!=5 && $timeType!=6 && $timeType!=7 && !preg_match('/SHOP/i',$EmployeeJob_row['JobNum']) && !preg_match('/Vacation/',$EmployeeJob_row['JobNum']) && !preg_match('/Sick/i',$EmployeeJob_row['JobNum'])) {
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['ELECTRICAL'] +=$Hurs;
                                  @$breakDownArray['BreakDown']['ELECTRICAL'] +=$Hurs;
                                }    
                                if ($timeType==5 && empty($EmployeeJob_row['JobNum'])) { 
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['OTHER'] +=$Hurs;
                                  @$breakDownArray['BreakDown']['OTHER'] +=$Hurs;
                                }    
                                if ($timeType==6 && empty($EmployeeJob_row['JobNum'])) { 
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['HOLIDAY'] +=$Hurs;
                                  @$breakDownArray['BreakDown']['HOLIDAY'] +=$Hurs;
                                }    
                                if ($timeType==7 && empty($EmployeeJob_row['JobNum'])) { 
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['VACATION'] +=$Hurs;
                                  @$breakDownArray['BreakDown']['VACATION'] +=$Hurs;
                                }    
                                if (preg_match('/SHOP/i',$EmployeeJob_row['JobNum'])) { 
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['SHOP'] +=$Hurs;
                                  @$breakDownArray['BreakDown']['SHOP'] +=$Hurs;
                                }    
                                if (preg_match('/Vacation/i',$EmployeeJob_row['JobNum'])) {  
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['VACATION'] +=$Hurs;
                                  @$breakDownArray['BreakDown']['VACATION'] +=$Hurs;
                                }    
                                if (preg_match('/Sick/i',$EmployeeJob_row['JobNum'])) { 
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['SICK'] +=$Hurs;
                                  @$breakDownArray['BreakDown']['SICK'] +=$Hurs;
                                }
                                if (preg_match('/P000/',$EmployeeJob_row['JobNum'])) {
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['PJOBS'] +=$Hurs; 
                                  @$breakDownArray['BreakDown']['PJOBS'] +=$Hurs; 
                                }      
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['TOTALHOURS'] +=$Hurs;
                                  @$jobHoursArray[(empty($EmployeeJob_row['JobNum'])?" ":$EmployeeJob_row['JobNum'])] += $Hurs ;
                                if ($Hurs!="") {      
                                  echo number_format($Hurs,2);
                                  @$totalsArray[$Dates[$i]] += number_format($Hurs,2);
                                  $THurs+= round($Hurs,2);      
                             } else {
                                
                              if ($timeType==8 && $jobValue=='') 
                                 {
                                    echo '<b>Off</b>';   
                                 }
                                else { 
                                if (isset($holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]) && $holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]=="") {
                                  $LeaveHurs = $leavesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                                  unset($leavesArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                                  echo ($LeaveHurs!=""?"<strong>".number_format($LeaveHurs,2)."H</strong>":"-");
                                  $totalsArray[$Dates[$i]] += number_format($LeaveHurs,2);
                                  $breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['SICK'] +=$LeaveHurs;
                                  $breakDownArray['BreakDown']['SICK'] +=$LeaveHurs;
                                  $THurs+= round($LeaveHurs,2);
                                 } else {
                                  $HolidayHurs = @$holidayArr[$Dates[$i]][$EmployeeJob_row['empId']];
                                  unset($holidayArr[$Dates[$i]][$EmployeeJob_row['empId']]);
                                  echo ($HolidayHurs!=""?"<strong>".number_format($HolidayHurs,2)."H</strong>":"-");
                                  @$totalsArray[$Dates[$i]] += number_format($HolidayHurs,2);
                                  @$breakDownArray['BreakDown'][$EmployeeJob_row['empId']]['HOLIDAY'] +=$HolidayHurs;
                                  @$breakDownArray['BreakDown']['HOLIDAY'] +=$HolidayHurs;
                                  $THurs+= round($HolidayHurs,2);
                              }
                                 
                             }
                             unset($timeType);
                            }
                          }?>&nbsp;&nbsp;</td>
                          <?php    }  ?>
                          <td nowrap="nowrap" bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" align="center">{{($THurs!=0?number_format($THurs,2)."H":"-")}}</td>
                        </tr>
                          <?php  } } 
                            $curRow++;
                          }  
                          $GTOTAL = 0;
                          ?>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" nowrap="nowrap"><strong>T O T A L S</strong></td>
                          <?php  for ($i=0; $i<count($Dates); $i++) {  ?>
                          <td align="center" nowrap="nowrap" bgcolor="<?php  echo $color = (date("D",strtotime($Dates[$i]))=="Sat" || date("D",strtotime($Dates[$i]))=="Sun"?"#FFFFCC":"#FFFFFF"); ?>" ><strong>&nbsp;&nbsp;
                          <?php  echo (!empty($totalsArray[$Dates[$i]])?round($totalsArray[$Dates[$i]],2):'');
                          $GTOTAL += @$totalsArray[$Dates[$i]]; ?> &nbsp;&nbsp;</strong></td>
                          <?php  } ?>
                          <td nowrap="nowrap" bgcolor="<?php echo ($bgcolor_row_prevail?$bgcolor_row_prevail:"#F0F0F0")?>" align="center"><strong><?php  echo round($GTOTAL,2);  ?>H</strong></td>
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
                      while (list($empId,$val)= @each($breakDownArray['BreakDown'])) { 
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