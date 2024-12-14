<?php
$color ='';
    $prevJob = "";
    $wageRate = '';
    $employee = Input::get("employee");
    $start = Input::get("SDate");
    $end = Input::get("EDate");
    $jNum = Input::get("jNum");
    $dbDateStart = date('Y-m-d', strtotime($start));
    $dbDateEnd = date('Y-m-d', strtotime($end));
function get_total_labor_cost($emp_id,$start_date,$end_date,$job_num=""){
    $filter_job_num_query  = ($job_num != "") ? " AND `b`.`job_num`='".$job_num."'" : "";
    $laborDataQuery = DB::select(DB::raw("SELECT 
                SUM(reg_hrs) AS tot_reg_hours ,SUM(ot_hrs) AS tot_ot_hours ,SUM(dt_hrs) AS tot_dt_hours ,SUM(reg_wage) AS tot_reg_wage ,SUM(ot_wage) AS tot_ot_wage ,SUM(dt_wage) AS tot_dt_wage,pw_flag 
              FROM 
                gpg_timesheet a , 
                gpg_timesheet_detail b 
              WHERE 
                a.id = b.GPG_timesheet_id and 
                a.GPG_employee_id = '$emp_id' 
                    AND (`a`.`date` >= '$start_date' AND `a`.`date` <= '$end_date')
                $filter_job_num_query
                  GROUP BY pw_flag  
                ORDER BY `a`.`date` ASC "));
      $pw_total_reg_hours = "" ;
      $pw_total_ot_hours = "" ;
      $pw_total_dt_hours = "" ;
      $pw_total_reg_wages = "" ;
      $pw_total_ot_wages = "" ;
      $pw_total_dt_wages = "" ;
      $total_reg_hours = "" ;
      $total_ot_hours = "" ;
      $total_dt_hours = "" ;
      $total_reg_wages = "" ;
      $total_ot_wages = "" ;
      $total_dt_wages = "" ;
      foreach ($laborDataQuery as $key => $value)
      {
        $laborDataRow = (array)$value;
          if($laborDataRow["pw_flag"] == 1){
            $pw_total_reg_hours = $laborDataRow["tot_reg_hours"] ;
            $pw_total_ot_hours = $laborDataRow["tot_ot_hours"] ;
            $pw_total_dt_hours = $laborDataRow["tot_dt_hours"] ;
            $pw_total_reg_wages = $laborDataRow["tot_reg_wage"] ;
            $pw_total_ot_wages = $laborDataRow["tot_ot_wage"] ;
            $pw_total_dt_wages = $laborDataRow["tot_dt_wage"] ;
          }else{
            $total_reg_hours = $laborDataRow["tot_reg_hours"] ;
            $total_ot_hours = $laborDataRow["tot_ot_hours"] ;
            $total_dt_hours = $laborDataRow["tot_dt_hours"] ;
            $total_reg_wages = $laborDataRow["tot_reg_wage"] ;
            $total_ot_wages = $laborDataRow["tot_ot_wage"] ;
            $total_dt_wages = $laborDataRow["tot_dt_wage"] ;
          }
      }//while
      $hours_array["pw_reg_hours"] =  $pw_total_reg_hours ;
      $hours_array["pw_ot_hours"] =  $pw_total_ot_hours ;
      $hours_array["pw_dt_hours"] =  $pw_total_dt_hours ;
      $hours_array["reg_pw_wages"] = $pw_total_reg_wages ;
      $hours_array["ot_pw_wages"] = $pw_total_ot_wages ;
      $hours_array["dt_pw_wages"] = $pw_total_dt_wages ;
      $hours_array["reg_hours"] =  $total_reg_hours ;
      $hours_array["ot_hours"] =  $total_ot_hours ;
      $hours_array["dt_hours"] =  $total_dt_hours ;
      $hours_array["reg_wages"] =  $total_reg_wages ;
      $hours_array["ot_wages"] =  $total_ot_wages ;
      $hours_array["dt_wages"] =  $total_dt_wages ;
      return $hours_array ;
  }
?>
<?php $sDate = Input::get('SDate'); $eDate=Input::get('EDate');?>
 <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>pw Type</th>
                        <th>Type</th>
                        <?php
                        $totals_array = array();
                        for ($i = 1; $i <= $tDays; $i++) {
                            $DayInfo_s = DB::select(DB::raw("select ADDDATE('" .(!empty($sDate)?$sDate:date('Y-m-d')). "', INTERVAL " . ($i - 1) . " DAY) as int_days"));
                            if (!empty($DayInfo_s) && isset($DayInfo_s[0]->int_days)) {
                              $DayInfo = $DayInfo_s[0]->int_days;
                            }
                            ?>
                        <th align="center" nowrap="nowrap"><strong>
                        <?php
                            $Dates[] = $DayInfo;
                            $totals_array[$DayInfo] = "";
                            echo date('Y-m-d', strtotime($DayInfo));
                          ?>
                        </strong></th><?php } 
                          $totals_array["Total Hours"] = "";
                          $totals_array["Reg"] = "";
                          $totals_array["OT"] = "";
                          $totals_array["DT"] = "";                                                        
                          $totals_array["Wage Rate"] = "";                                                        
                          $totals_array["Total Deduction"]="";
                          $totals_array["Reg Wages"] = "";
                          $totals_array["OT Wages"] = "";
                          $totals_array["DT Wages"] = "";
                          $totals_array["Adjustment"] = "";
                          $totals_array["Total Amount"] = "";  
                        ?>
                        <th>Total Hours</th>
                        <th>Reg</th>
                        <th>OT</th>
                        <th>DT</th>
                        <th>Wage Rate</th>
                        <th>Deductions</th>
                        <th>Reg Wages</th>
                        <th>OT Wages  </th>
                        <th>DT Wages</th>
                        <th>Adjustment</th>
                        <th>Total Wages</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $emp_id = 0;
                      $curRow = 1;
                      $prevEmp = '';
                      $gtotal = 0;
                      $type = 1000;
                      $employee_job_new_arr = array();
                      $temp_emp_id_check = "";
                      foreach ($query_data as $key => $EmployeeJob_row)  {
                          $THurs = 0;
                          $curCol = 0;
                          if ( $EmployeeJob_row['wage_type'] > 0 && $EmployeeJob_row['pw_flag'] == 1 )
                              $EmployeeJob_row['prevail'] = 1;
                          $emp_id = $EmployeeJob_row['empId'];
                        if($temp_emp_id_check != $emp_id){
                          $employee_job_new_arr = get_total_labor_cost($emp_id,$dbDateStart,$dbDateEnd,$jNum);
                          $temp_emp_id_check = $emp_id ;
                        }
                        $deduction_sum = DB::table('gpg_employee_deduction')->where('gpg_employee_id','=',$emp_id)->sum('rate');
                        if ( $prevJob != $EmployeeJob_row['wage_type'] . '_' . $EmployeeJob_row['empId'] ) { ?>
                        <tr>
                          <td >{{$EmployeeJob_row['empName']}}</td>
                          <td ><?php
                            if ( $EmployeeJob_row['wage_type'] > 0 && $EmployeeJob_row['prevail'] == 1 ) {
                                if ( $EmployeeJob_row['wage_type'] == 1 ) {
                                    echo '<strong>&lt;$500k</strong>';
                                }
                                if ( $EmployeeJob_row['wage_type'] == 2 ) {
                                echo '<strong>&gt;$500k</strong>';
                                
                            }
                            } else if ( $EmployeeJob_row['salaried'] == 1 ) {
                                echo 'Salary';
                                
                            } else {
                                echo 'Hourly';
                                
                            }?>
                            </td>
                            <td><?php
                            if ( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1 ) {
                                echo "<strong>PREVAILING</strong>";
                            } else {
                                echo "REGULAR";
                            }
                            $rH = 0;
                            $otH = 0;
                            $dtH = 0;
                            $totalRegWage = 0;
                            $totalOtWage = 0;
                            $totalDtWage = 0;
                            $regHours = 0;
                            $otHours = 0;
                            $dtHours = 0;
                            $pw_reg = 0;
                            $pw_ot = 0;
                            $pw_dt = 0;
                            $perHourLabor = 0;
                            for ( $i = 0; $i < count($Dates); $i++ ) {?>
                              <td ><?php
                              $empRec = @$datesArr[$Dates[$i]][$EmployeeJob_row['empId']][$EmployeeJob_row['pw_flag']];
                                if ( $EmployeeJob_row['salaried'] != 1 ) {
                                  if ( isset($datesArr[$Dates[$i]]) && is_array($datesArr[$Dates[$i]]) ) {
                                    $simTotal = 0;
                                    $pwTotal = 0;
                                    $totalHours = 0;
                                    $pwType = 0;
                                    @reset($empRec);
                                    while ( @list($ky, $vl) = @each($empRec) ) {
                                    $totalHours += $vl['hours'];
                                    if ( $vl['is_pw'] == 0 ) {
                                        $timeType = $vl['time_type'];
                                        $jobValue = $ky;
                                        $simTotal += $vl['hours'];
                                    }
                                    if ( $vl['is_pw'] == 1 ) {
                                      $pwTotal += $vl['hours'];
                                      $compare_job_num = strtolower(substr($EmployeeJob_row['JobNum'],0,2));
                                      $pw_wage_type = DB::table('gpg_job_rates')->where('job_number','=',$EmployeeJob_row['JobNum'])->orWhere('job_number','=',$compare_job_num)->pluck('wage_type'); 
                                      if ( $vl['pw_type'] == $pw_wage_type ) {
                                        $pwType += $vl['hours'];
                                        $pw_reg = $vl['pw_reg'];
                                        $pw_ot = ($vl['pw_ot'] > 0 ? $vl['pw_ot'] : ($vl['pw_reg'] * 1.5));
                                        $pw_dt = ($vl['pw_dt'] > 0 ? $vl['pw_dt'] : ($vl['pw_reg'] * 2));
                                      }
                                    }
                                      $perHourLabor = $vl['emp_reg'];
                                      $Hurs = $vl['hours'];
                                  }
                                  if ( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1 ) {
                                    $regHours = $employee_job_new_arr["pw_reg_hours"];
                                    $otHours = $employee_job_new_arr["pw_ot_hours"];
                                    $dtHours = $employee_job_new_arr["pw_dt_hours"];
                                  }else{
                                    $regHours = $employee_job_new_arr["reg_hours"];
                                    $otHours = $employee_job_new_arr["ot_hours"];
                                    $dtHours = $employee_job_new_arr["dt_hours"];
                                  }
                                  $regWage = @round($rH * ($EmployeeJob_row['prevail'] == 1 ? $pw_reg - $deduction_sum   : $perHourLabor), 2);
                                  $otWage = @round($otH * ($EmployeeJob_row['prevail'] == 1 ? (($pw_ot - $deduction_sum) * 1.5) : ($perHourLabor * 1.5)), 2);
                                  $dtWage = @round($dtH * ($EmployeeJob_row['prevail'] == 1 ? (($pw_dt - $deduction_sum)* 2) : ($perHourLabor * 2)), 2); 
                                  if ( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1 ) {
                                    $totalRegWage = $employee_job_new_arr["reg_pw_wages"];
                                    $totalOtWage = $employee_job_new_arr["ot_pw_wages"];
                                    $totalDtWage = $employee_job_new_arr["dt_pw_wages"];
                                  }else{
                                    $totalRegWage = $employee_job_new_arr["reg_wages"];
                                    $totalOtWage = $employee_job_new_arr["ot_wages"];
                                    $totalDtWage = $employee_job_new_arr["dt_wages"];
                                  }
                                  if ( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1 ) {
                                    if ( $pwType > 0 ) {
                                      echo number_format($pwType, 2);
                                      $totals_array[$Dates[$i]] += $pwType;
                                    } else {
                                      echo '';
                                    }
                                    $THurs+= round($pwType, 2);
                                    $wageRate = $pw_reg;
                                  } else {
                                  if ( $simTotal > 0 ) {
                                    if ( ($timeType == 6 || $timeType == 7) && $jobValue == '' ) {
                                      $totals_array[$Dates[$i]] += $pwType;
                                      echo number_format($simTotal, 2);
                                    } else {
                                      echo number_format($simTotal, 2);
                                      $totals_array[$Dates[$i]] += $simTotal;
                                      if ( $color == "#FFFFFF" )
                                        echo '';
                                      else
                                        echo '';
                                    }
                                    } else {
                                      if ( $color == "#FFFFFF" )
                                        echo '';
                                      else
                                        echo '';
                                    }
                                    if ( $timeType == 8 && $jobValue == '' ) {
                                      if ( $color == "#FFFFFF" )
                                        echo '';
                                      else
                                      echo '';
                                    }
                                    unset($timeType);
                                    $THurs+= round($simTotal, 2);
                                    $wageRate = $perHourLabor;
                                  }
                                        // not Apply
                                    }
                              } else {
                                    if ( $color == "#FFFFFF" ) {
                                        echo "<font color='#c10000'>N/A</font>";
                                    } else
                                        echo '';
                                }?></td>
                              <?php
                                  $curCol++;
                              }
                              if ( $EmployeeJob_row['salaried'] ) {
                                  $grossSal = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$EmployeeJob_row['empId'])->where('type','=','s')->where('start_date','<=',$dbDateEnd)->orderBy('start_date')->pluck('rate');
                                  $totalRegWage = ($grossSal / 24) * @ceil(count($Dates) / 16);
                              }?>
                              <td ><?php
                                echo ($THurs != 0 ? number_format($THurs, 2) : "-");
                                  $totals_array["Total Hours"] += $THurs;
                              ?></td>
                              <td><?php
                              if ( $regHours > 0 ) {
                                echo number_format($regHours, 2);
                                $totals_array["Reg"] += $regHours;
                              } else {
                                echo '';
                              }
                              ?></td>
                              <td ><?php
                              if ( $otHours > 0 ) {
                                echo number_format($otHours, 2);
                                $totals_array["OT"] += $otHours;
                              } else {
                                echo '';
                              }
                              ?></td>
                              <td ><?php
                              if ( $dtHours > 0 ) {
                                echo number_format($dtHours, 2);
                                $totals_array["DT"] += $dtHours;
                              } else {
                                echo '';
                              }
                              ?></td>
                              <td ><strong><?php
                              if ( $wageRate > 0 ) {
                                if ( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1 ) {
                                    echo '-';                                               
                                } else {
                                echo '$' . number_format($wageRate, 2);
                                $totals_array["Wage Rate"] += $wageRate;                                              
                              }
                              } else {
                                echo '';
                              }?></strong></td>
                              <td >
                              <?php
                                if( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1 ){
                                  echo '$' . number_format($deduction_sum, 2);
                                  $totals_array["Total Deduction"] += $deduction_sum;
                                }else{
                                  $totals_array["Total Deduction"] = '-';
                                echo '';
                              }
                              ?>
                              </td>
                              <td ><strong><?php
                              if ( $totalRegWage > 0 ) {
                                echo '$' . number_format($totalRegWage, 2);
                                $totals_array["Reg Wages"] += $totalRegWage;
                              } else {
                                echo '';
                              }
                              ?></strong></td>
                              <td ><strong><?php
                              if ( $totalOtWage > 0 ) {
                                echo '$' . number_format($totalOtWage, 2);
                                $totals_array["OT Wages"] += $totalOtWage;
                              } else {
                                echo '';
                              }
                              ?></strong></td>
                              <td ><strong><?php
                              if ( $totalDtWage > 0 ) {
                                echo '$' . number_format($totalDtWage, 2);
                                $totals_array["DT Wages"] += $totalDtWage;
                              } else {
                                echo '';
                              }
                              ?></strong></td>
                              <td >
                              <?php
                              if ( $EmployeeJob_row['adjustment'] )
                                echo '';
                              else
                                $totals_array["Adjustment"] += $EmployeeJob_row['adjustment'];
                              ?></td>
                              <td >
                              <?php
                              if ( $totalRegWage + $totalOtWage + $totalDtWage > 0 ) {
                                echo '$' . number_format($totalRegWage + $totalOtWage + $totalDtWage, 2);
                                $totals_array["Total Amount"] += $totalRegWage + $totalOtWage + $totalDtWage + $EmployeeJob_row['adjustment'];
                              } else {
                                echo '';
                              }?>
                              </td>
                              </tr>
                              <?php
                                  $curRow++;
                                }
                                $prevJob = $EmployeeJob_row['wage_type'] . '_' . $EmployeeJob_row['empId'];
                                $type = @$EmployeeJob_row['prevail'];
                              }?>
                              <tr > 
                                <td ><strong>TOTALS</strong></td>
                              <?php
                                $curCol = 0;
                                $count = 0;   
                                foreach ( $totals_array as $key => $value ) {
                                  if ( strpos($key, "-") ) {
                                    echo '<td>' . number_format((double)$value, 2) . '</td>';
                                  } elseif ( $key == 'Adjustment' ) {
                                    echo '<td ><div id="adjustmentDiv"></div></td>';
                                  } else {
                                    $count++;
                                    if ( $count > 4 ) {
                                      echo '<td  ><strong><div id="' . ($key == 'Total Amount' ? 'total_amountDiv' : $key) . '">' . '$' . number_format((double)$value, 2) . '</div></strong></td>';
                                    } else {
                                      echo '<td  ><strong>' . number_format((double)$value, 2) . '</strong></td>';
                                    }
                                  }
                                }
                              ?>
                            </tr>
                    </tbody>
                  </table>