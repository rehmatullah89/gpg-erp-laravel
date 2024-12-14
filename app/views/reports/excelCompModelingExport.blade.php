 <?php
                                        $year_display = 20;
                                        $thisyear = Input::get('year');
                                        if (empty($thisyear))
                                          $thisyear = date('Y');
                                        for($year = $thisyear - $year_display; $year <= $thisyear + $year_display; $year++){
                                          $selected = ($year == $thisyear) ? " selected" : "";
                                          echo("<option value=\"$year\"$selected>".date('Y', mktime(0,0,0,1,1,$year))."</option>");
                                        }
                                        ?>
                  <?php $reportYear = Input::get("year");
                        if (!isset($reportYear))
                          $reportYear = date('Y');
                        $start = "01/01/" . $reportYear;
                        $end = "12/31/" . $reportYear;
                        $dbDateStart = date('Y-m-d', strtotime($start));
                        $dbDateEnd = date('Y-m-d', strtotime($end));
                        for ( $i = 1; $i <= $tDays; $i++ ) {
                            $DayInfo0 = DB::select(DB::raw("select ADDDATE('" . $dbDateStart . "', INTERVAL " . ($i - 1) . " DAY) as dayInfo"));
                            $Dates[] = @$DayInfo0[0]->dayInfo;
                        }
                        $THursTotal = 0;
                        $wageRate  = 0;
                        $TRegHursTotal=0;
                        $TOtHursTotal=0;
                        $TTaxTotal =0;
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
                            $TRegWageTotal =0;
                            $TRegHursTotal =0;
                            $TDtHursTotal =0;
                            $TOtWageTotal =0;
                            $TOtHursTotal =0;
                            $TDtWageTotal=0;
                            $gTotalFields =0;
                            $TWageTotal =0;
                            $perHourLabor = 0;
                        $gTotalFields = '';
                        $glCodeTotalFields = '';
                        $gTotalDiffFields = '';
                        $colsARR = array();
                        $glColumns = DB::select(DB::raw("select * from gpg_gl_expense_columns where report_year =  '$reportYear' order by id asc"));
                        foreach ($glColumns as $key => $value2){
                          $glColRow = (array)$value2;
                          $colsARR[] = $glColRow;
                          $glExp0 = DB::select(DB::raw("select sum((select sum(ifnull(amount,0)) as gExp from gpg_gl_expense where gpg_expense_gl_code_id = a.id and year(date) = '$reportYear')) as amount from gpg_expense_gl_code a LEFT JOIN gpg_expense_gl_code b on (a.parent_id = b.id) where a.id in (" . implode(",", unserialize($glColRow['gpg_gl_expense_ids'])) . ") or (b.id is not null and b.id in (" . implode(",", unserialize($glColRow['gpg_gl_expense_ids'])) . ")) "));
                          $glExp = @$glExp0[0]->gExp;
                          $gTotalFields.= '<td bgcolor="#F0F0F0" align="center"><b><DIV id="gTotalDIV_' . $glColRow['id'] . '"></DIV></b></td>';
                          $glCodeTotalFields.= '<td bgcolor="#F0F0F0" align="center"><b><DIV id="glCodeTotalDIV_' . $glColRow['id'] . '">' . '$' . number_format($glExp, 2) . '</DIV></b><input type="hidden" name="glCodeTotalVal_' . $glColRow['id'] . '" id="glCodeTotalVal_' . $glColRow['id'] . '" value="' . round($glExp, 2) . '"></td>';
                          $gTotalDiffFields.= '<td bgcolor="#F0F0F0" align="center"><b><DIV id="TotalDiffDIV_' . $glColRow['id'] . '"></DIV></b><script>addColValues(' . $glColRow['id'] . ');</script></td>';                                               
                        }
                  ?>
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>pw Type</th>
                        <th>Type</th>
                        <th>Total Hours</th>
                        <th>Reg</th>
                        <th>OT</th>
                        <th>DT</th>
                        <th>Wage Rate</th>
                        <th>Deductions</th>
                        <th>Reg Wages</th>
                        <th>OT Wages  </th>
                        <th>DT Wages</th>
                        <th>Total Salary</th>
                        <th>Payroll Taxes
                        <br>Rate:<input type="text" class="textRed" style="width:25px" value="{{_DefaultTaxRateSalary}}" maxlength="5" onchange="calculatePayTax(this.value); return false;" name="yearlyPayrollTax" id="yearlyPayrollTax">%
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $prevJob = '';
                        $curRow = 1;
                        $prevEmp = '';
                        $gtotal = 0;
                        foreach ($query_data as $key => $EmployeeJob_row){
                          $THurs = 0;
                          $curCol = 0;
                          if ( $EmployeeJob_row['wage_type'] > 0 )
                            $EmployeeJob_row['prevail'] = 1;
                          if ( $prevJob != $EmployeeJob_row['wage_type'] . '_' . $EmployeeJob_row['empId'] ) {
                      ?>  <tr>
                            <td bgcolor="#F0F0F0" nowrap="nowrap">{{$EmployeeJob_row['empName']}}</td>
                            <td bgcolor="#F0F0F0" nowrap="nowrap">&nbsp;<?php
                            if ( $EmployeeJob_row['wage_type'] > 0 ) {
                            if ( $EmployeeJob_row['wage_type'] == 1 ) {
                                echo '<strong><$500k</strong>';
                            }
                            if ( $EmployeeJob_row['wage_type'] == 2 ) {
                                echo '<strong>>$500k</strong>';
                            }
                            } else if ( $EmployeeJob_row['salaried'] == 1 ) {
                                echo 'Salary';
                            } else {
                                echo 'Hourly';
                            }
                            ?></td>
                          <?php
                            $emp_id = $EmployeeJob_row['empId'];
                            $deduction_sum = DB::table('gpg_employee_deduction')->where('gpg_employee_id','=',$emp_id)->sum('rate'); 
                          ?>
                          <td bgcolor="#F0F0F0" height="25" nowrap="nowrap"><?php 
                            $currWType = "P";
                            if ( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1 ) {
                              echo "<strong>PREVAILING</strong>";
                            } else {
                              echo "REGULAR";
                              $currWType = "R";
                            }
                          ?><?php
                            for ( $i = 0; $i < count($Dates); $i++ ) {
                              $empRec = @$datesArr[$Dates[$i]][$EmployeeJob_row['empId']];
                              if (isset($datesArr[$Dates[$i]]) && is_array($datesArr[$Dates[$i]]) ) {
                                $simTotal = 0;
                                $pwTotal = 0;
                                $totalHours = 0;
                                $pwType = 0;
                                @reset($empRec);
                                while ( @list($ky, $vl) = @each($empRec) ) {
                                  $totalHours += $vl['hours'];
                                  if ( isset($vl['is_pw']) && $vl['is_pw'] == 0 ) {
                                    $timeType = $vl['time_type'];
                                    $jobValue = $ky;
                                    $simTotal += $vl['hours'];
                                  }
                                  if (isset($vl['is_pw']) &&  $vl['is_pw'] == 1 ) {
                                    $pwTotal += $vl['hours'];
                                    $pw_wage_type = DB::table('gpg_job_rates')->where('job_number','=',$EmployeeJob_row['JobNum'])->pluck('wage_type');
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
                                $rH = 0;
                                $otH = 0;
                                $dtH = 0;
                                $slab = array( );
                                $fTotal = floor($totalHours);
                                $dAmt = $totalHours - $fTotal;
                                for ( $jj = 1; $jj <= $fTotal; $jj++ ) {
                                    $slab[$jj] = ($jj <= 8 ? 'reg' : ($jj > 8 && $jj <= 12 ? 'ot' : ($jj > 12 ? 'dt' : '')));
                               }
                               if ( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1 ){
                                  if ( $pwType <= 8 ) {
                                    $rH = $pwType;
                                  } elseif ( $pwType > 8 && $pwType <= 12 ) {
                                    $rH = 8;
                                    $otH = $pwType - 8;
                                  } elseif ( $pwType > 12 ) {
                                  $rH = 8;
                                  $otH = 4;
                                  $dtH = $pwType - 12;
                                  }
                                } else {
                                  $fPwTotal = floor($pwTotal);
                                  $dpwAmt = $pwTotal - $fPwTotal;
                                  $fSimTotal = floor($simTotal);
                                  $dSimAmt = $simTotal - $fSimTotal;
                                  for ( $jj = $fPwTotal + 1; $jj <= $fTotal; $jj++ ) {
                                    if ( $slab[$jj] == "reg" )
                                      $rH++;
                                    elseif ( $slab[$jj] == "ot" )
                                      $otH++;
                                    elseif ( $slab[$jj] == "dt" )
                                      $dtH++;
                                  }
                                  if ( is_float($pwTotal) ) {
                                    if ( $pwTotal <= 8 )
                                      $rH -= $dpwAmt;
                                    elseif ( $pwTotal > 8 && $pwTotal <= 12 )
                                      $otH -= $dpwAmt;
                                    elseif ( $pwTotal > 12 )
                                      $dtH -= $dpwAmt;
                                    } elseif ( is_float($simTotal) ) {
                                      if ( $pwTotal + $simTotal <= 8 )
                                        $rH += $dSimAmt;
                                      elseif ( $pwTotal + $simTotal > 8 && $pwTotal + $simTotal <= 12 )
                                        $otH += $dSimAmt;
                                      elseif ( $pwTotal + $simTotal > 12 )
                                        $dtH += $dSimAmt;
                                    }
                                  } // end else
                                  $regHours += $rH;
                                  $otHours += $otH;
                                  $dtHours += $dtH;
                                  $regWage = @round($rH * ($EmployeeJob_row['prevail'] == 1 ? ($pw_reg - $deduction_sum) : $perHourLabor), 2);
                                  $otWage = @round($otH * ($EmployeeJob_row['prevail'] == 1 ? ($pw_ot - $deduction_sum) : ($perHourLabor * 1.5)), 2);
                                  $dtWage = @round($dtH * ($EmployeeJob_row['prevail'] == 1 ? ($pw_dt - $deduction_sum) : ($perHourLabor * 2)), 2);
                                  $totalRegWage += $regWage;
                                  $totalOtWage += $otWage;
                                  $totalDtWage += $dtWage;
                                  if ( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1 ){
                                    $THurs+= round($pwType, 2);
                                    $wageRate = $pw_reg;
                                  } else {
                                    unset($timeType);
                                    $THurs+= round($simTotal, 2);
                                    $wageRate = $perHourLabor;
                                  }
                                }?></td>
                                <?php
                                  $curCol++;
                              }
                              if ( $EmployeeJob_row['salaried'] ) {
                                $grossSal = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$EmployeeJob_row['empId'])->where('type','=','s')->where('start_date','<=',$dbDateEnd)->orderBy('start_date','DESC')->pluck('rate');
                                $totalRegWage = ($grossSal / 24) * @ceil(count($Dates) / 16);
                              }?>
                              <td width="100" nowrap="nowrap" bgcolor="#ffffff" align="center"><?php 
                                echo ($THurs != 0 ? number_format($THurs, 2) : "-");
                                $THursTotal += $THurs;
                              ?></td>
                              <td width="100" nowrap="nowrap" bgcolor="#ffffff" align="center"><?php 
                                if ( $regHours > 0 ) {
                                  echo number_format($regHours, 2);
                                  $TRegHursTotal += $regHours;
                                }
                                ?></td>
                                <td width="100" nowrap="nowrap" bgcolor="#ffffff" align="center"><?php 
                               if ( $otHours > 0 ) {
                                  echo number_format($otHours, 2);
                                  $TOtHursTotal += $otHours;
                                }
                               ?></td>
                                <td width="100" nowrap="nowrap" bgcolor="#ffffff" align="center"><?php 
                                if ( $dtHours > 0 ) {
                                  echo number_format($dtHours, 2);
                                  $TDtHursTotal += $dtHours;
                                }
                                ?></td>
                                <td width="100" nowrap="nowrap" bgcolor="#ffffff" align="center"><strong><?php 
                                if ( $wageRate > 0 ) {
                                  echo '$' . number_format($wageRate, 2);
                                }
                                ?></strong></td>
                                <td width="150" nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong><?php
                                if ( isset($EmployeeJob_row['prevail']) && $EmployeeJob_row['prevail'] == 1  && $deduction_sum > 0) {
                                    echo '$' . number_format($deduction_sum, 2);
                                } else {
                                    echo "-";
                                }
                                ?></strong>
                                </td>
                                <td width="100" nowrap="nowrap" bgcolor="#ffffff" align="center"><strong><?php 
                                if ( $totalRegWage > 0 ) {
                                  echo '$' . number_format($totalRegWage, 2);
                                  $TRegWageTotal += $totalRegWage;
                                }
                                ?></strong></td>
                                <td width="100" nowrap="nowrap" bgcolor="#ffffff" align="center"><strong><?php 
                                if ( $totalOtWage > 0 ) {
                                  echo '$' . number_format($totalOtWage, 2);
                                  $TOtWageTotal += $totalOtWage;
                                }
                                ?></strong></td>
                                <td width="100" nowrap="nowrap" bgcolor="#ffffff" align="center"><strong><?php 
                                if ( $totalDtWage > 0 ) {
                                  echo '$' . number_format($totalDtWage, 2);
                                  $TDtWageTotal += $totalDtWage;
                                }
                                ?></strong></td>
                                <td width="150" nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong>   
                                <?php
                                if ( $totalRegWage + $totalOtWage + $totalDtWage > 0 ) {
                                  $totalSal = $totalRegWage + $totalOtWage + $totalDtWage;
                                  echo '$' . number_format($totalSal, 2);
                                  $TWageTotal += $totalSal;
                                }
                                ?></strong></td> 
                                <td bgcolor="#FFFFFF" align="center"><?php
                                $taxCal = @($totalSal * _DefaultTaxRateSalary) / 100;
                                $TTaxTotal += $taxCal;
                                echo '$' . number_format($taxCal, 2); ?></div></td>   
                                <?php
                                if ( is_array($colsARR) ) {
                                  reset($colsARR);
                                  while ( list($colkey, $colval) = each($colsARR) ) {
                                  ?>
                                  <td bgcolor="#FFFFCC"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><?php  echo ($colval['rate_type'] == 'individual' && $colval['cal_type'] == 'calc' ? '<td width="30"><input type="text" class="textRed" style="width:30px" name="field_rate_' . $colval['id'] . '_' . $curRow . '" id="field_rate_' . $colval['id'] . '_' . $curRow . '" value="' . $DynamicColumnValues[$EmployeeJob_row['empId']][$colval['id']][$currWType]['field_rate'] . '" maxlength="5" onchange="findAndCalcColsByRow(' . $curRow . '); return false;"  ></td><td bgcolor="#FFC1C1" width="1" ><b>%</b></td>' : '') ?><td><input type="text" <?php  echo ($colval['cal_type'] == 'calc' ? 'readonly="readonly"' : '') ?> class="textYellow" name="field_value_<?php echo  $colval['id'] ?>_<?php echo  $curRow ?>" id="field_value_<?php echo  $colval['id'] ?>_<?php echo  $curRow ?>" value="<?php echo  $DynamicColumnValues[$EmployeeJob_row['empId']][$colval['id']][$currWType]['field_value'] ?>" onchange="findAndCalcColsByRow(<?php echo  $curRow ?>); return false;"></td></tr></table></td>
                                  <?php 
                                  }
                                }?>
                                </tr>
                                <?php 
                                  $totalSal = 0.00;
                                  $curRow++;
                                }
                                $prevJob = $EmployeeJob_row['wage_type'] . '_' . $EmployeeJob_row['empId'];
                              }?>
                              <tr>
                                <td height="25" colspan="3" align="center" nowrap="nowrap" bgcolor="#F0F0F0"><input type="hidden" name="totalTblRows" id="totalTblRows" value="<?php echo  $curRow; ?>"><strong>TOTAL </strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong>
                                <?php echo $THursTotal; ?></strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong>
                                <?php echo $TRegHursTotal; ?></strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong>
                                <?php echo $TOtHursTotal; ?></strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong>
                                <?php echo $TDtHursTotal; ?></strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td bgcolor="#F0F0F0"></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong>                                                            
                                <?php echo '$' . number_format($TRegWageTotal, 2); ?></strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong>
                                <?php echo '$' . number_format($TOtWageTotal, 2); ?></strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong>
                                <?php echo  '$' . number_format($TDtWageTotal, 2) ?></strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center"><strong>
                                <?php echo  '$' . number_format($TWageTotal, 2) ?></strong></td>
                                <td bgcolor="#F0F0F0" align="center"><strong><DIV id="gTotalDIV_Tax">
                                <?php echo  '$' . number_format($TTaxTotal, 2) ?></DIV></strong></td>
                                <?php echo  $gTotalFields ?>
                              </tr>
                              <tr>
                                <td height="25" colspan="3" align="center" nowrap="nowrap" bgcolor="#F0F0F0"><strong>QUICKBOOK TOTAL</strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td bgcolor="#F0F0F0" align="center"><strong><DIV id="glCodeTotalDIV_Tax"><?php 
                                $glExpTaxes0 = DB::select(DB::raw("select sum((select sum(ifnull(amount,0)) as res from gpg_gl_expense where gpg_expense_gl_code_id = a.id and year(date) = '$reportYear')) as amount from gpg_expense_gl_code a LEFT JOIN gpg_expense_gl_code b on (a.parent_id = b.id) where a.expense_gl_code in (6600) or (b.id is not null and b.expense_gl_code in (6600)) "));
                                $glExpTaxes = @$glExpTaxes0[0]->res;
                                echo '$' . number_format($glExpTaxes, 2);
                                ?></DIV></strong><input type="hidden" id="glCodeTotalVal_Tax" name="glCodeTotalVal_Tax" value="<?php echo  $glExpTaxes ?>" /></td>
                                <?php echo  $glCodeTotalFields ?>
                              </tr>
                              <tr>
                                <td height="25" colspan="3" align="center" nowrap="nowrap" bgcolor="#F0F0F0"><strong><font color="#c10000">DIFFERENCE</font></strong></td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td nowrap="nowrap" bgcolor="#F0F0F0" align="center">&nbsp;</td>
                                <td bgcolor="#F0F0F0" align="center"><strong><DIV id="TotalDiffDIV_Tax"><?php  if ( $glExpTaxes >= $TTaxTotal ) { ?><font color="green"><?php  echo '$' . number_format($glExpTaxes - $TTaxTotal, 2); ?></font><?php  } else { ?><font color="#c10000"><?php  echo '$' . number_format($glExpTaxes - $TTaxTotal, 2); ?></font><?php  } ?></DIV></strong></td>
                                <?php echo  $gTotalDiffFields ?>
                              </tr>
                          </tbody>
                  </table>
                  <?php
                      $yearSecondArray = array();
                      $queryYearSecond = DB::select(DB::raw("select if(ifnull(b.parent_id,0) = 0,concat(b.expense_gl_code,' - ',b.description),(select concat(expense_gl_code,' - ',description) from gpg_expense_gl_code where id = b.parent_id and b.parent_id!=0)) as expenseGlCode,b.parent_id as expenseParent,sum(a.amount) as amt from gpg_gl_expense a LEFT JOIN gpg_expense_gl_code b ON a.gpg_expense_gl_code_id = b.id where year(a.date)= '" . $reportYear . "'  GROUP BY  if(ifnull(b.parent_id,0) = 0,b.id,b.parent_id)"));
                      foreach ($queryYearSecond as $key => $value3){
                        $rowYearSecond = (array)$value3;
                        $yearSecondArray[$rowYearSecond["expenseGlCode"]]['amt'] = $rowYearSecond["amt"];
                      }
                      $resYearFirst = DB::select(DB::raw("select if(ifnull(b.parent_id,0) = 0,concat(b.expense_gl_code,' - ',b.description),(select concat(expense_gl_code,' - ',description) from gpg_expense_gl_code where id = b.parent_id and b.parent_id!=0)) as expenseGlCode,b.parent_id as expenseParent,sum(a.amount) as amt from gpg_gl_expense a LEFT JOIN gpg_expense_gl_code b ON a.gpg_expense_gl_code_id = b.id where year(a.date)= '" . ($reportYear - 1) . "'  GROUP BY  if(ifnull(b.parent_id,0) = 0,b.id,b.parent_id)"));
                  ?>
                    <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                        <thead>
                          <tr>
                            <th><b>Gl-Code</b></th>
                            <th><b>{{$reportYear}}</b></th>
                            <th><b>{{$reportYear-1}}</b></th>
                            <th><b>Diff</b></th>
                            <th><b>Action</b></th>
                            <th><b>Growth %</b></th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                          $colcount = 0;
                          $TotalGrid1 = 0;
                          $TotalGrid2 = 0;
                          $TotalGridDiff = 0;
                          foreach ($resYearFirst as $key => $value4){
                            $yearFirstRow = (array)$value4;
                            ?>
                            <tr  bgcolor="<? echo ($colcount % 2 == 0 ? "#FFFFCC" : "#FFFFFF"); ?>">
                              <td height="25" align="left" >&nbsp;<strong><? echo $C51 = $yearFirstRow['expenseGlCode'] ?></strong></td>
                              <?php
                              if ( trim($yearFirstRow['contract_number']) == trim($yearSecondArray[$yearFirstRow['contract_number']]['contractNum']) ) {
                              ?>
                              <td height="25" align="center" >&nbsp;<?php echo  '$' . number_format($D51 = $yearSecondArray[$yearFirstRow['expenseGlCode']]['amt'], 2) ?></td>
                              <td height="25" align="center" >&nbsp;<? echo '$' . number_format($yearFirstRow['amt'], 2) ?></td>
                              <td height="25" align="center" >&nbsp;<?php echo  '$' . number_format($yearSecondArray[$yearFirstRow['expenseGlCode']]['amt'] - $yearFirstRow['amt'], 2) ?></td>
                              <td height="25" align="center" >&nbsp;<?php echo  number_format((($yearSecondArray[$yearFirstRow['expenseGlCode']]['amt'] - $yearFirstRow['amt']) / $yearFirstRow['amt']) * 100, 2) . "%" ?></td>
                            </tr> 
                              <?php
                              unset($yearSecondArray[$yearFirstRow['expenseGlCode']]);
                              } else {
                            ?>
                              <td height="25" align="center" >&nbsp;</td>
                              <td height="25" align="center" ><? echo '$' . number_format(-$yearFirstRow['amt'], 2) ?></td>
                              <td height="25" align="center" ><?php echo  number_format(-100, 2) . "%" ?></td>
                            </tr>
                            <? }
                              $TotalGrid1 +=$yearSecondArray[$yearFirstRow['expenseGlCode']]['amt'];
                              $TotalGrid2 +=$yearFirstRow['amt'];
                              $TotalGridDiff +=$yearSecondArray[$yearFirstRow['expenseGlCode']]['amt'] - $yearFirstRow['amt'];
                              $colcount++;
                            }
                            if ( is_array($yearSecondArray) ) {
                              foreach ( $yearSecondArray as $key => $Value ) {
                            ?>
                            <tr  bgcolor="<? echo ($colcount % 2 == 0 ? "#FFFFCC" : "#FFFFFF"); ?>">
                              <td height="25" align="left" >&nbsp;<strong><?php echo  $key ?></strong></td>
                              <td height="25" align="center" >&nbsp;<?php echo  '$' . number_format($Value['amt'], 2) ?></td>
                              <td height="25" align="center" >&nbsp;</td>
                              <td height="25" align="center" >&nbsp;<?php echo  '$' . number_format($Value['amt'], 2) ?></td>
                              <td height="25" align="center" ><?php echo  number_format(100, 2) . "%" ?></td>
                            </tr>
                            <?
                              $TotalGrid1 +=$Value['amt'];
                              $TotalGridDiff +=$Value['amt'];
                              $colcount++;
                            }
                          } ?>
                            <tr  bgcolor="#f0f0f0">
                              <td height="25" align="left" >&nbsp;<strong>TOTAL </strong></td>
                              <td height="25" align="center" ><strong><?php echo  '$' . number_format($TotalGrid1, 2) ?></strong> </td>
                              <td height="25" align="center" ><strong><?php echo  '$' . number_format($TotalGrid2, 2) ?></strong></td>
                              <td height="25" align="center" ><strong><?php echo  '$' . number_format($TotalGridDiff, 2) ?></strong> </td>
                              <td height="25" align="center" > </td>
                            </tr>
                        </tbody>
                    </table>   