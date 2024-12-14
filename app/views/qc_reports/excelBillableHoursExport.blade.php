 <?php
                  $sdate = Input::get("SDate1");
                  $edate = Input::get("EDate1");
                  if ( $sdate == "" )
                      $sdate = "01/01/" . date("Y");
                  if ( $edate == "" )
                      $edate = date("m/d/Y");
                  $optEmployee = Input::get("optEmployee");
                  $empType = Input::get("empType");
                  $order_by = Input::get("orderby");
                  if ( $order_by == "" ) {
                      $order_by = "ename";
                  }
                  $start_date = date('Y-m-d', strtotime($sdate) - (strtotime($edate) - strtotime($sdate)));
                  $emp_type_total_prev = array();
               ?>
               <table class="table table-bordered table-striped table-condensed cf" >
                <thead>
                  <tr>
                    <th  colspan="17" align="center"><strong><?php echo date('m/d/Y', strtotime($sdate)) . " - " . date('m/d/Y', strtotime($edate)); ?></strong></th>
                  </tr>
                  <tr bgcolor="#EEE">
                      <th nowrap="nowrap" align="center" rowspan="2"><strong>Emp. Type</strong></th>
                      <th nowrap="nowrap" align="center" rowspan="2"><strong>Emp. Name</strong></th>
                      <th nowrap="nowrap" align="center" rowspan="2"><strong>Hrs. Paid For</strong></th>
                      <th nowrap="nowrap" height="30" align="center" rowspan="2"><strong>Hrs. Billable</strong></th>
                      <th nowrap="nowrap" align="center" rowspan="2"><strong>Efficiency</strong></th>
                      <th nowrap="nowrap" align="center" rowspan="2"><strong>Lbr. Rate</strong></th> 
                      <th colspan="9" align="center" height="30px"><strong>HOURS</strong></th>
                      <th nowrap="nowrap" align="center" rowspan="2"><strong>Deductions</strong></th>
                      <th nowrap="nowrap" align="center" rowspan="2"><strong>Amt. Paid</strong></th>
                  </tr>
                  <tr bgcolor="#F2F2F2">
                      <th nowrap="nowrap" align="center"><strong>P Jobs</strong></th>
                      <th nowrap="nowrap" align="center"><strong>Vacation</strong></th>
                      <th  nowrap="nowrap" align="center"><strong>Sick Leave</strong></th>
                      <th  nowrap="nowrap" align="center"><strong>Rglr</strong></th>
                      <th  nowrap="nowrap" align="center"><strong>Ov-time</strong></th>
                      <th  nowrap="nowrap" align="center"><strong>2-Time</strong></th>
                      <th  nowrap="nowrap" align="center"><strong>Prev.<br />Reg.</strong></th>
                      <th  nowrap="nowrap" align="center"><strong>Prev.<br />Ov-time</strong></th>
                      <th  nowrap="nowrap" align="center"><strong>Prev.<br />2-Time</strong></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $totalpw_reg_wages = 0;
                      $billable = 0;
                      $total_reg_hrs = 0;
                      $total_ot_hrs = 0;
                      $total_dt_hrs = 0;
                      $total_pw_reg_hrs = 0;
                      $total_pw_ot_hrs = 0;
                      $total_pw_dt_hrs = 0;
                      $total_reg_wages = 0;
                      $total_ot_wages = 0;
                      $total_dt_wages = 0;
                      $totalpw_reg_wages = 0;
                      $totalpw_ot_wages = 0;
                      $totalpw_dt_wages = 0;
                      $non_billable = 0;
                      $counter = 0;
                      $emp_P_jobs = 0;
                      $emp_sick = 0;
                      $emp_vacation = 0;
                      $each_date_hours = 0;
                  if ( is_array($billable_records_next) )
                    foreach ( $billable_records_next as $emp_id => $date_row ) {
                      $deduction_sum = DB::table('gpg_employee_deduction')->where('gpg_employee_id','=',$emp_id)->sum('rate'); mysql_result(mysql_query($emp_deduct_sql), 0, 0);
                      $thours = 0.00;
                      $billable = 0;
                      $total_reg_hrs = 0;
                      $total_ot_hrs = 0;
                      $total_dt_hrs = 0;
                      $total_pw_reg_hrs = 0;
                      $total_pw_ot_hrs = 0;
                      $total_pw_dt_hrs = 0;
                      $total_reg_wages = 0;
                      $total_ot_wages = 0;
                      $total_dt_wages = 0;
                      $totalpw_reg_wages = 0;
                      $totalpw_ot_wages = 0;
                      $totalpw_dt_wages = 0;
                      $non_billable = 0;
                      $counter = 0;
                      $emp_P_jobs = 0;
                      $emp_sick = 0;
                      $emp_vacation = 0;
                      $each_date_hours = 0;
                    foreach ( $date_row as $date => $emp_row ) {
                      $emp_P_jobs += $non_billable_next[$emp_id][$date]["non_billable"]['P000'];
                      $emp_vacation += $non_billable_next[$emp_id][$date]["non_billable"]['vacation'];
                      $emp_sick += $non_billable_next[$emp_id][$date]["non_billable"]['sick'];
                      $ename = $emp_row['ename'];
                      $etype = $emp_row['etype'];
                      $labor_rate = $emp_row["labor_rate"];
                      $thours += $emp_row['working_hours'];
                      $emp_job_nums = explode(",", $emp_row['job_nums']);
                    for ( $i = 0; $i < count($emp_job_nums) - 1; $i++ ) {
                      $j_num2 = explode("~", $emp_job_nums[$i]);
                      $j_num = $j_num2[0];
                      $job_type_meta = explode("@", $j_num2[1]);
                      $meta = explode("!", $job_type_meta[1]);
                      $prevail = $meta[0];
                      $wage_type = $meta[1];
                      $emp_job_num = $j_num2[0] . "~" . $job_type_meta[0];
                    if ( $prevail == 1 && $wage_type > 0 ) {
                      $total_pw_reg_hrs += @$emp_row['reg_hours'][$j_num];
                      $total_pw_ot_hrs += @$emp_row['ot_hours'][$j_num];
                      $total_pw_dt_hrs += @$emp_row['dt_hours'][$j_num];
                      $totalpw_reg_wages += @round(($emp_row['reg_hours'][$j_num] * ($rates[$emp_job_num]['pw_reg'] - $deduction_sum)), 2);
                      $totalpw_ot_wages += @round($emp_row['ot_hours'][$j_num] * (($rates[$emp_job_num]['pw_overtime'] - $deduction_sum) * 1.5), 2);
                      $totalpw_dt_wages += @round($emp_row['dt_hours'][$j_num] * (($rates[$emp_job_num]['pw_doubletime'] - $deduction_sum) * 2), 2);
                    } else {
                      $total_reg_hrs += @$emp_row['reg_hours'][$j_num];
                      $total_ot_hrs += @$emp_row['ot_hours'][$j_num];
                      $total_dt_hrs += @$emp_row['dt_hours'][$j_num];
                      $total_reg_wages += @round(($emp_row['reg_hours'][$j_num] * ($labor_rate)), 2);
                      $total_ot_wages += @round(($emp_row['ot_hours'][$j_num] * ($labor_rate * 1.5)), 2);
                      $total_dt_wages += @round(($emp_row['dt_hours'][$j_num] * ($labor_rate * 2)), 2);
                    }
                  }
                }
                  $non_billable = $emp_P_jobs;
                  $billable_hrs = $thours - $non_billable;
                ?>
                <tr style="height: 30px;">
                  <td  align="left" bgcolor="#FFFFFF">{{ucwords($etype)}}</td>
                  <td  align="left" bgcolor="#FFFFFF">{{$ename}}</td>
                  <td align="right" bgcolor="#FFFFCC">
                  <?php
                    $total_record['next']['hours_paid'] += $thours;
                    echo number_format($thours, 2);
                    $emp_type_total_next[$etype]['total_hrs'] += $thours;
                  ?></td>
                  <td  align="right" bgcolor="#FFFFCC">
                  <?php
                    $total_record['next']['hours_billable'] += $billable_hrs;
                    echo number_format($billable_hrs, 2);
                    $emp_type_total_next[$etype]['hours_billable'] += $billable_hrs;
                  ?>
                  </td>
                  <td align="right" bgcolor="#FFFFFF"><?php echo @number_format((($billable_hrs / $thours) * 100), 2) . "%"; ?></td>
                  <td  align="right" bgcolor="#FFFFFF"><?php echo '$' . $labor_rate ?></td>
                  <td align="right" bgcolor="#FFFFFF"><?php
                  echo $emp_P_jobs == 0? " - ":number_format($emp_P_jobs,2);
                  $total_record['next']['p_jobs'] += $emp_P_jobs;?>
                  </td>
                  <td align="right" bgcolor="#FFFFFF">
                  <?php
                  echo $emp_vacation == 0 ? " - " : number_format($emp_vacation,2);
                  $total_record['next']['vacation'] += $emp_vacation;
                  ?></td>
                  <td align="right" bgcolor="#FFFFFF"><?php
                  echo $emp_sick == 0? " - ": number_format($emp_sick,2);
                  $total_record['next']['sick'] += $emp_sick;?>
                  </td>
                  <td  align="right" bgcolor="#FFFFFF"><?php
                  $total_record['next']['reg_hrs'] += $total_reg_hrs;
                  echo $total_reg_hrs == 0 ? "-" : number_format($total_reg_hrs, 2);
                  ?></td>
                  <td  align="right" bgcolor="#FFFFFF"><?php
                  $total_record['next']['ot_hrs'] += $total_ot_hrs;
                  echo $total_ot_hrs == 0 ? "-" : number_format($total_ot_hrs, 2);
                  ?></td>
                  <td  align="right" bgcolor="#FFFFFF"><?php
                  $total_record['next']['dt_hrs'] += $total_dt_hrs;
                  echo $total_dt_hrs == 0 ? "-" : number_format($total_dt_hrs, 2);
                  ?></td>
                  <td  align="right" bgcolor="#FFFFFF"><?php
                  $total_record['next']['pw_reg_hrs'] += $total_pw_reg_hrs;
                  echo $total_pw_reg_hrs == 0 ? "-" : number_format($total_pw_reg_hrs, 2);
                  ?></td>
                  <td  align="right" bgcolor="#FFFFFF"><?php
                  $total_record['next']['pw_ot_hrs'] += $total_pw_ot_hrs;
                  echo $total_pw_ot_hrs == 0 ? "-" : number_format($total_pw_ot_hrs, 2);
                  ?></td>
                  <td  align="right" bgcolor="#FFFFFF"><?php
                  $total_record['next']['pw_dt_hrs'] += $total_pw_dt_hrs;
                  echo $total_pw_dt_hrs == 0 ? "-" : number_format($total_pw_dt_hrs, 2);
                  ?></td>
                  <td  align="right" bgcolor="#FFFFFF"><?php
                  $total_record['next']['deduction'] += $deduction_sum;
                  echo $deduction_sum != 0 ? '$' . $deduction_sum : "-";
                  ?></td>
                  <td  align="right" bgcolor="#ffc1c1"><?php
                  $paid_amount = $total_reg_wages + $totalpw_reg_wages + $total_ot_wages + $total_dt_wages + $totalpw_ot_wages + $totalpw_dt_wages;
                  echo $paid_amount == 0 ? "-" : '$' . number_format($paid_amount, 2);
                  $total_record['next']['amount_paid'] += $paid_amount;
                  $emp_type_total_next[$etype]['amount_paid'] += $paid_amount;
                  ?></td>
                </tr><?php }?>
                <tr style="height: 30px;">
                  <td colspan="2"  align="center" bgcolor="#EEE"><strong>T O T A L</strong></td>
                  <td  align="right" bgcolor="#EEE"><strong><?php
                  echo $total_record['next']['hours_paid'] == 0 ? "-" : number_format($total_record['next']['hours_paid'], 2);?>
                  </strong></td>
                  <td  align="right" bgcolor="#EEE"><strong><?php
                  echo $total_record['next']['hours_billable'] == 0 ? "-" : number_format($total_record['next']['hours_billable'], 2);
                  ?></strong></td>
                  <td  align="right" bgcolor="#EEE"></td>
                  <td  align="right" bgcolor="#EEE"></td>
                  <td  align="right" bgcolor="#EEE"><strong><?php
                  echo $total_record['next']['p_jobs'] == 0 ? "-" : number_format($total_record['next']['p_jobs'],2);
                  ?></strong></td>
                  <td  align="right" bgcolor="#EEE"><strong><?php
                  echo $total_record['next']['vacation'] == 0 ? "-" : number_format($total_record['next']['vacation'],2);
                  ?></strong></td>
                  <td  align="right" bgcolor="#EEE"><strong><?php
                  echo $total_record['next']['sick'] == 0 ? "-" : number_format($total_record['next']['sick'],2);
                  ?></strong></td>
                  <td  align="right" bgcolor="#EEE"><strong>
                  <?php echo $total_record['next']['reg_hrs'] == 0 ? "-" : number_format($total_record['next']['reg_hrs'], 2); ?>
                  </strong></td>
                  <td  align="right" bgcolor="#EEE"><strong>
                  <?php echo $total_record['next']['ot_hrs'] == 0 ? "-" : number_format($total_record['next']['ot_hrs'], 2); ?>
                  </strong></td>
                  <td  align="right" bgcolor="#EEE"><strong>
                  <?php echo $total_record['next']['dt_hrs'] == 0 ? "-" : number_format($total_record['next']['dt_hrs'], 2); ?>
                  </strong></td>
                  <td  align="right" bgcolor="#EEE"><strong>
                  <?php echo $total_record['next']['pw_reg_hrs'] == 0 ? "-" : number_format($total_record['next']['pw_reg_hrs'], 2); ?>
                  </strong></td>
                  <td  align="right" bgcolor="#EEE"><strong>
                  <?php echo $total_record['next']['pw_ot_hrs'] == 0 ? "-" : number_format($total_record['next']['pw_ot_hrs'], 2); ?>
                  </strong></td>
                  <td  align="right" bgcolor="#EEE"><strong>
                  <?php echo $total_record['next']['pw_dt_hrs'] == 0 ? "-" : number_format($total_record['next']['pw_dt_hrs'], 2); ?>
                  </strong></td>
                  <td  align="right" bgcolor="#EEE"><strong>
                  <?php echo @$total_record['next']['deduction'] == 0 ? "-" : '$' . @$total_record['next']['deduction']; ?>
                  </strong></td>
                  <td  align="right" bgcolor="#ffc1c1"><strong><?php
                  $paid_amount = $total_reg_wages + $totalpw_reg_wages + $total_ot_wages + $total_dt_wages + $totalpw_ot_wages + $totalpw_dt_wages;
                  echo $total_record['next']['amount_paid'] == 0 ? "-" : '$' . number_format($total_record['next']['amount_paid'], 2);
                  ?></strong></td>
                </tr>
              </tbody>
            </table>
            <table class="table table-bordered table-striped table-condensed cf" >
              <thead>
                <tr height="40px">
                  <th colspan="17" align="center"><strong><?php echo date('m/d/Y', strtotime($start_date)) . " - " . date('m/d/Y', strtotime($sdate)); ?></strong></th>
                </tr>
                <tr bgcolor="#F2F2F2" class="tablehead">
                  <th  nowrap="nowrap" align="center" rowspan="2"><strong>Emp. Type</strong></th>
                  <th  nowrap="nowrap" align="center" rowspan="2"><strong>Emp. Name</strong></th>
                  <th  nowrap="nowrap" align="center" rowspan="2"><strong>Hrs. Paid For</strong></th>
                  <th  nowrap="nowrap" height="30" align="center" rowspan="2"><strong>Hrs. Billable</strong></th>
                  <th  nowrap="nowrap" align="center" rowspan="2"><strong>Efficiency</strong></th>
                  <th nowrap="nowrap" align="center" rowspan="2"><strong>Lbr. Rate</strong></th> 
                  <th colspan="9" align="center" height="30px"><strong>HOURS</strong></th>
                  <th  nowrap="nowrap" align="center" rowspan="2"><strong>Deduct.</strong></th>
                  <th  nowrap="nowrap" align="center" rowspan="2"><strong>Amt. Paid</strong></th>
                </tr>
                <tr bgcolor="#F2F2F2" class="tablehead" height="30px">
                  <th nowrap="nowrap" align="center"><strong>P Jobs</strong></th>
                  <th nowrap="nowrap" align="center"><strong>Vac.</strong></th>
                  <th  nowrap="nowrap" align="center"><strong>Sick Leave</strong></th>
                  <th  nowrap="nowrap" align="center"><strong>Reg. </strong></th>
                  <th  nowrap="nowrap" align="center"><strong>Ov-time </strong></th>
                  <th  nowrap="nowrap" align="center"><strong>2-Time </strong></th>
                  <th  nowrap="nowrap" align="center"><strong>Prev.<br />Reg.</strong></th>
                  <th  nowrap="nowrap" align="center"><strong>Prev.<br />Ov-time</strong></th>
                  <th  nowrap="nowrap" align="center"><strong>Prev.<br />2-Time</strong></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $counter = 0;
                  if ( is_array($billable_records_prev) )
                    foreach ( $billable_records_prev as $emp_id => $date_row ) {
                      $deduction_sum = DB::table('gpg_employee_deduction')->where('gpg_employee_id','=',$emp_id)->sum('rate');
                      $thours = 0.00;
                      $billable = 0;
                      $total_reg_hrs = 0;
                      $total_ot_hrs = 0;
                      $total_dt_hrs = 0;
                      $total_pw_reg_hrs = 0;
                      $total_pw_ot_hrs = 0;
                      $total_pw_dt_hrs = 0;
                      $total_reg_wages = 0;
                      $total_ot_wages = 0;
                      $total_dt_wages = 0;
                      $totalpw_reg_wages = 0;
                      $totalpw_ot_wages = 0;
                      $totalpw_dt_wages = 0;
                      $non_billable = 0;
                      $each_date_hours = 0;
                      $emp_P_jobs = 0;
                      $emp_vacation = 0;
                      $emp_sick = 0;
                      foreach ( $date_row as $date => $emp_row ) {
                        $emp_P_jobs += @$non_billable_prev[$emp_id][$date]["non_billable"]['P000'];
                        $emp_vacation += @$non_billable_prev[$emp_id][$date]["non_billable"]['vacation'];
                        $emp_sick += @$non_billable_prev[$emp_id][$date]["non_billable"]['sick'];
                        $ename = $emp_row['ename'];
                        $etype = $emp_row['etype'];
                        $labor_rate = $emp_row["labor_rate"];
                        $thours += $emp_row['working_hours'];
                        $emp_job_nums = explode(",", $emp_row['job_nums']);
                        for ( $i = 0; $i < count($emp_job_nums) - 1; $i++ ) {
                          $j_num2 = explode("~", $emp_job_nums[$i]);
                          $j_num = $j_num2[0];
                          $job_type_meta = explode("@", $j_num2[1]);
                          $meta = explode("!", $job_type_meta[1]);
                          $prevail = $meta[0];
                          $wage_type = $meta[1];
                          $emp_job_num = $j_num2[0] . "~" . $job_type_meta[0];
                        if ( $prevail == 1 && $wage_type > 0 ) {
                          $total_pw_reg_hrs += @$emp_row['reg_hours'][$j_num];
                          $total_pw_ot_hrs += @$emp_row['ot_hours'][$j_num];
                          $total_pw_dt_hrs += @$emp_row['dt_hours'][$j_num];
                          $totalpw_reg_wages += @round(($emp_row['reg_hours'][$j_num] * ($rates[$emp_job_num]['pw_reg'] - $deduction_sum)), 2);
                          $totalpw_ot_wages += @round($emp_row['ot_hours'][$j_num] * (($rates[$emp_job_num]['pw_overtime'] - $deduction_sum) * 1.5), 2);
                          $totalpw_dt_wages += @round($emp_row['dt_hours'][$j_num] * (($rates[$emp_job_num]['pw_doubletime'] - $deduction_sum) * 2), 2);
                        } else {
                          $total_reg_hrs += @$emp_row['reg_hours'][$j_num];
                          $total_ot_hrs += @$emp_row['ot_hours'][$j_num];
                          $total_dt_hrs += @$emp_row['dt_hours'][$j_num];
                          $total_reg_wages += @round(($emp_row['reg_hours'][$j_num] * ($labor_rate)), 2);
                          $total_ot_wages += @round(($emp_row['ot_hours'][$j_num] * ($labor_rate * 1.5)), 2);
                          $total_dt_wages += @round(($emp_row['dt_hours'][$j_num] * ($labor_rate * 2)), 2);
                        }
                      }
                    }//endforeach 
                    $non_billable = $emp_P_jobs;
                    $billable_hrs = $thours - $non_billable; //-- here ?>
                    <tr style="height: 30px;"> 
                      <td  align="left" bgcolor="#FFFFFF"><?php echo ucwords($etype); ?></td>
                      <td  align="left" bgcolor="#FFFFFF"><?php echo $ename; ?></td>
                      <td  align="right" bgcolor="#FFFFCC"><?php $total_record['prev']['hours_paid'] += $thours; echo number_format($thours, 2);
                       @$emp_type_total_prev[$etype]['total_hrs'] += $thours; ?></td>
                      <td  align="right" bgcolor="#FFFFCC"><?php
                        $total_record['prev']['hours_billable'] += $billable_hrs;
                        echo number_format($billable_hrs, 2);
                        @$emp_type_total_prev[$etype]['hours_billable'] += $billable_hrs; ?></td>
                      <td  align="right" bgcolor="#FFFFFF"><?php echo @number_format((($billable_hrs / $thours) * 100), 2) . "%"; ?></td>
                      <td  align="right" bgcolor="#FFFFFF"><?php echo '$' . $labor_rate ?></td>
                      <td align="right" bgcolor="#FFFFFF"><?php
                        echo $emp_P_jobs == 0? " - ":number_format($emp_P_jobs,2);
                        $total_record['prev']['p_jobs'] += $emp_P_jobs;
                      ?></td>
                      <td align="right" bgcolor="#FFFFFF"><?php
                        echo $emp_vacation == 0 ? " - " : number_format($emp_vacation,2);
                        $total_record['prev']['vacation'] += $emp_vacation;
                      ?></td>
                      <td align="right" bgcolor="#FFFFFF"><?php
                        echo $emp_sick == 0? " - ": number_format($emp_sick,2);
                        $total_record['prev']['sick'] += $emp_sick;
                      ?></td>
                      <td  align="right" bgcolor="#FFFFFF"><?php
                        $total_record['prev']['reg_hrs'] += $total_reg_hrs;
                        echo $total_reg_hrs == 0 ? "-" : number_format($total_reg_hrs, 2);
                      ?></td>
                      <td  align="right" bgcolor="#FFFFFF"><?php
                        $total_record['prev']['ot_hrs'] += $total_ot_hrs;
                        echo $total_ot_hrs == 0 ? "-" : number_format($total_ot_hrs, 2);
                      ?></td>
                      <td  align="right" bgcolor="#FFFFFF"><?php
                        $total_record['prev']['dt_hrs'] += $total_dt_hrs;
                        echo $total_dt_hrs == 0 ? "-" : number_format($total_dt_hrs, 2);
                      ?></td>
                      <td  align="right" bgcolor="#FFFFFF"><?php
                        $total_record['prev']['pw_reg_hrs'] += $total_pw_reg_hrs;
                        echo $total_pw_reg_hrs == 0 ? "-" : number_format($total_pw_reg_hrs, 2);
                      ?></td>
                      <td  align="right" bgcolor="#FFFFFF"><?php
                        $total_record['prev']['pw_ot_hrs'] += $total_pw_ot_hrs;
                        echo $total_pw_ot_hrs == 0 ? "-" : number_format($total_pw_ot_hrs, 2);
                      ?></td>
                      <td  align="right" bgcolor="#FFFFFF"><?php
                        $total_record['prev']['pw_dt_hrs'] += $total_pw_dt_hrs;
                        echo $total_pw_dt_hrs == 0 ? "-" : number_format($total_pw_dt_hrs, 2);
                      ?></td>
                      <td  align="right" bgcolor="#FFFFFF"><?php
                        @$total_record['prev']['deduction'] += $deduction_sum;
                        echo $deduction_sum != 0 ? '$' . number_format($deduction_sum,2) : "-";
                      ?></td>
                      <td  align="right" bgcolor="#ffc1c1"><?php
                        $paid_amount = $total_reg_wages + $totalpw_reg_wages + $total_ot_wages + $total_dt_wages + $totalpw_ot_wages + $totalpw_dt_wages;
                        echo $paid_amount == 0 ? "-" : '$' . number_format($paid_amount, 2);
                        $total_record['prev']['amount_paid'] += $paid_amount;
                        @$emp_type_total_prev[$etype]['amount_paid'] += $paid_amount;
                      ?></td>
                    </tr>
                    <?php } ?>
                    <tr style="height: 30px;">
                      <td colspan="2"  align="center" bgcolor="#EEE"><strong>T O T A L</strong></td>
                      <td  align="right" bgcolor="#EEE"><strong><?php
                        echo $total_record['prev']['hours_paid'] == 0 ? "-" : number_format($total_record['prev']['hours_paid'], 2);
                      ?></strong></td>
                      <td  align="right" bgcolor="#EEE"><strong><?php
                        echo $total_record['prev']['hours_billable'] == 0 ? "-" : number_format($total_record['prev']['hours_billable'], 2);
                      ?></strong></td>
                      <td  align="right" bgcolor="#EEE"></td>
                      <td  align="right" bgcolor="#EEE"></td>
                      <td  align="right" bgcolor="#EEE"><strong><?php
                        echo $total_record['prev']['p_jobs'] == 0 ? "-" :number_format($total_record['prev']['p_jobs'],2);
                      ?></strong></td>
                      <td  align="right" bgcolor="#EEE"><strong><?php
                        echo $total_record['prev']['vacation'] == 0 ? "-" : number_format($total_record['prev']['vacation'],2);
                      ?></strong></td>
                      <td  align="right" bgcolor="#EEE"><strong><?php
                        echo $total_record['prev']['sick'] == 0 ? "-" : number_format($total_record['prev']['sick'],2);
                      ?></strong></td>
                      <td  align="right" bgcolor="#EEE"><strong>
                        <?php echo $total_record['prev']['reg_hrs'] == 0 ? "-" : number_format($total_record['prev']['reg_hrs'], 2); ?>
                      </strong></td>
                      <td  align="right" bgcolor="#EEE"><strong>
                        <?php echo $total_record['prev']['ot_hrs'] == 0 ? "-" : number_format($total_record['prev']['ot_hrs'], 2); ?>
                      </strong></td>
                      <td  align="right" bgcolor="#EEE"><strong>
                        <?php echo $total_record['prev']['dt_hrs'] == 0 ? "-" : number_format($total_record['prev']['dt_hrs'], 2); ?>
                      </strong></td>
                      <td  align="right" bgcolor="#EEE"><strong>
                        <?php echo $total_record['prev']['pw_reg_hrs'] == 0 ? "-" : number_format($total_record['prev']['pw_reg_hrs'], 2); ?>
                      </strong></td>
                      <td  align="right" bgcolor="#EEE"><strong>
                        <?php echo $total_record['prev']['pw_ot_hrs'] == 0 ? "-" : number_format($total_record['prev']['pw_ot_hrs'], 2); ?>
                      </strong></td>
                      <td  align="right" bgcolor="#EEE"><strong>
                        <?php echo $total_record['prev']['pw_dt_hrs'] == 0 ? "-" : number_format($total_record['prev']['pw_dt_hrs'], 2); ?>
                      </strong></td>
                      <td  align="right" bgcolor="#EEE"><strong>
                        <?php echo @$total_record['prev']['deduction'] == 0 ? "-" : '$' . number_format($total_record['prev']['deduction'],2); ?>
                      </strong></td>
                      <td  align="right" bgcolor="#ffc1c1"><strong><?php
                        $paid_amount = $total_reg_wages + $totalpw_reg_wages + $total_ot_wages + $total_dt_wages + $totalpw_ot_wages + $totalpw_dt_wages;
                        echo $total_record['prev']['amount_paid'] == 0 ? "-" : '$' . number_format($total_record['prev']['amount_paid'], 2);
                      ?></strong></td>
                    </tr>    
              </tbody>
            </table>
           </section>
           </div>
          </div>
         </div>
        <div class="row">
              <div class="col-sm-6">
              <section class="panel">
              <div class="panel-body">
              <section id="no-more-tables">
                <table class="table table-bordered table-striped table-condensed cf" >
                 <thead>
                  <tr>
                    <th colspan="18" bgcolor="#FFFFCC" height="22" align="center"><strong><?php echo date('m/d/Y', strtotime($sdate)) . " - " . date('m/d/Y', strtotime($edate)); ?></strong></th>
                  </tr>
                  <tr>
                    <th bgcolor="#FFFFCC" height="20" align="center">Employee Type</th>
                    <th bgcolor="#FFFFCC" align="center">Hours Paid For</th>
                    <th bgcolor="#FFFFCC" align="center">Hours Billable</th>
                    <th bgcolor="#FFFFCC" align="center">Amount Paid</th>
                  </tr>
                 </thead>
                  <tbody>
                    <?php 
                    if (isset($emp_type_total_next) && is_array($emp_type_total_next)){
                    ksort($emp_type_total_next);
                    foreach ( $emp_type_total_next as $key => $val ) { ?>
                    <tr>
                      <td bgcolor="#FFFFCC" height="20" align="left"><strong><?php echo  $key ?></strong></td>
                      <td bgcolor="#FFFFFF" align="right"><?php echo  number_format($val['total_hrs'], 2); ?></td>
                      <td bgcolor="#FFFFFF" align="right"><?php echo  number_format($val['hours_billable'], 2); ?></td>
                      <td bgcolor="#FFFFFF" align="right" style="padding:0 5px"><?php echo  '$' . number_format($val['amount_paid'], 2); ?></td>
                    </tr>
                    <?php } } ?>
                    <tr height="20px" bgcolor="#EEE">
                      <td height="20" align="left"><strong>T O T A L</strong></td>
                      <td  align="right"><strong><?php echo  $total_record['next']['hours_paid'] == 0 ? "-" : number_format($total_record['next']['hours_paid'], 2); ?></strong></td>
                      <td  align="right"><strong><?php echo  $total_record['next']['hours_billable'] == 0 ? "-" : number_format($total_record['next']['hours_billable'], 2); ?></strong></td>
                      <td  align="right" style="padding:0 5px"><strong><?php echo  $total_record['next']['amount_paid'] == 0 ? "-" : '$' . number_format($total_record['next']['amount_paid'], 2); ?></strong></td>
                    </tr>
                  </tbody>
                </table>
              </section>
              </div>
              </section>
              </div>
              <div class="col-sm-6">
              <section class="panel">
              <div class="panel-body">
              <section id="no-more-tables">
                <table class="table table-bordered table-striped table-condensed cf" >
                 <thead>
                  <tr>
                    <th colspan="18" bgcolor="#FFFFCC" height="22" align="center"><strong><?php echo date('m/d/Y', strtotime($start_date)) . " - " . date('m/d/Y', strtotime($sdate)); ?></strong></th>
                  </tr>
                  <tr>
                    <th bgcolor="#FFFFCC" height="20" align="center">Employee Type</th>
                    <th bgcolor="#FFFFCC" align="center">Hours Paid For</th>
                    <th bgcolor="#FFFFCC" align="center">Hours Billable</th>
                    <th bgcolor="#FFFFCC" align="center">Amount Paid</th>
                  </tr>
                 </thead>
                  <tbody>
                    <?php 
                    ksort($emp_type_total_prev);
                    foreach ( $emp_type_total_prev as $key => $val ) { ?>
                    <tr height="20px">
                      <td bgcolor="#FFFFCC" height="20" align="left"><strong><?php echo  $key ?></strong></td>
                      <td bgcolor="#FFFFFF" align="right"><?php echo  number_format($val['total_hrs'], 2); ?></td>
                      <td bgcolor="#FFFFFF" align="right"><?php echo  number_format($val['hours_billable'], 2); ?></td>
                      <td bgcolor="#FFFFFF" align="right" style="padding:0 5px"><?php echo  '$' . number_format($val['amount_paid'], 2); ?></td>
                    </tr>
                    <?php } ?>
                    <tr height="20px" bgcolor="#EEE">
                      <td height="20" align="left"><strong>T O T A L</strong></td>
                      <td align="right"><strong><?php echo  $total_record['prev']['hours_paid'] == 0 ? "-" : number_format($total_record['prev']['hours_paid'], 2); ?></strong></td>
                      <td align="right"><strong><?php echo  $total_record['prev']['hours_billable'] == 0 ? "-" : number_format($total_record['prev']['hours_billable'], 2); ?></strong></td>
                      <td align="right" style="padding:0 5px"><strong><?php echo  $total_record['prev']['amount_paid'] == 0 ? "-" : '$' . number_format($total_record['prev']['amount_paid'], 2); ?></strong></td>
                    </tr>
                  </tbody>
                </table>
              </section>
              </div>
              </section>
              </div>