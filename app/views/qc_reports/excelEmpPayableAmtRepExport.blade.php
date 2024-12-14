<table x:str border=0 cellpadding=0 cellspacing=0 width=1000 style='border-collapse: collapse;table-layout:fixed;width:900pt'>
	<tr>
		<td>&nbsp;</td>
	</tr>
    <tr style='height:12.75pt'>
        <td align="center" colspan="11"> Employee Payable Amount Report</td>
    </tr>
    <tr>
     	<td>&nbsp;</td>
    </tr>
    <tr>
    	<td><strong>Start Date</strong></td>
        <td><?php echo $SDate?></td>
        <td><strong>End Date</strong></td>
        <td><?php echo $EDate?></td>
    </tr>
<?php 
$emp_name = '';
	$grand_total_employee_actual_rate =0;
	$grand_total_actual_weca_rate =0;
	$colcount=0;
	$jj = 0;
	$array_check_row = array() ;
	$previous_employee_name = ""  ;
	$totalCount = 0 ;
	$totalRowsJobRates = count($query_data) ;
	$jobColCount = 2 ;
	if($Etype==1){
		$emp_id = 0;
		$count = 0;
		$grand_total_employee_actual_rate = 0;
		$grand_total_actual_weca_rate = 0;
		$c_name = "xl118_y333";
	?>
	<tr>
        <td height="40" align="center" colspan="7">Employee Name</td>
        <td align="center" colspan="1">EE Rate</td>
        <td align="center" colspan="1">Total Employee Wage</td>
        <td align="center" colspan="1">Total WECA</td>
    </tr>
	<?php
	foreach ($query_data as $key => $value) {
		$row = (array)$value; 
		if($emp_name != $row['employee_name'] && $count > 0) {
			$c_name = ($c_name=="xl118_y333"?"xl118_y33":"xl118_y333");
	?>
    <tr>
        <td height="40" align="center" colspan="7"><? echo $emp_name ?></td>
        <td align="center" colspan="1"><?php echo $perHourLabor ; ?></td>
        <td align="center" colspan="1"><?php echo ((float)$grand_total_employee_actual_rate > 0) ? $grand_total_employee_actual_rate : '-';?></td>
        <td align="center" colspan="1"><?php echo ((float)$grand_total_actual_weca_rate > 0) ? $grand_total_actual_weca_rate : '-';?></td>
    </tr>
    <?php
		$grand_total_actual_weca_rate = 0;
		$grand_total_employee_actual_rate = 0;
		$emp_name = $row['employee_name'];
		}//endif
		$totalCount++ ;
		$perHourLabor = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$row['GPG_employee_Id'])->where('type','=','h')->where('start_date','<=',$row['timesheet_date'])->OrderBy('start_date','DESC')->pluck('rate');
		$employee_id = $row["GPG_employee_Id"] ;
	    $perHourLabor = ((float)$perHourLabor > 0) ? $perHourLabor : $row["labor_rate"] ;
		$basic_hourly_rate_job = 0 ;
		$health_and_welfare_job = 0 ;
		$pension_job = 0 ;
		$vacations_job = 0 ;
		$training_job = 0 ;
		$other_payments_job = 0 ;
		$total_prevailing_other_job_rate = 0 ;
		$basic_hourly_rate_deduction = 0 ;
		$health_and_welfare_deduction = 0 ;
		$pension_deduction = 0 ;
		$pension_deduction_calc = 0 ;
		$vacations_deduction = 0 ;
		$training_deduction = 0 ;
		$other_payments_deduction = 0 ;
		$total_prevailing_other_deduction_rate = 0 ;
		$grand_total_employee_deduction = 0 ;
		$grand_total_prevailing_job_rates = 0;
		$calc_basic_hourly_rate_job = 0 ;
		$grand_total_deduction_hourly_rate = 0 ;
		$calc_basic_hourly_rate_deduction = 0;
		$job_prevailing_rate = 0 ;
		$total_employee_actual_rate = 0 ;
		$total_actual_weca_rate = 0 ;
		if((float)$perHourLabor > 0){
			$result_rates = DB::select(DB::raw("SELECT * FROM gpg_job_rates_breakup WHERE job_rates_id = '".$row['job_rate_id']."'"));
			foreach ($result_rates as $key => $value2) {
				$row_rates = (array)$value2;
				if((float)$row_rates["pw_wages_rate_type"] == 1){
					$basic_hourly_rate_job = $row_rates["rate"] ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 2){
					$health_and_welfare_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $health_and_welfare_job ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 3){
					$pension_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $pension_job ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 4){
					$vacations_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $vacations_job ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 5){
					$training_job = $row_rates["rate"] ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 6){
					$other_payments_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $other_payments_job ;
				}
			}//foreach
			$total_prevailing_rate_sum0 = DB::select(DB::raw("SELECT IFNULL(SUM(rate),0) as rate FROM gpg_job_rates_breakup WHERE job_rates_id = '".$row['job_rate_id']."'"));
			$total_prevailing_rate_sum	= @$total_prevailing_rate_sum0[0]->rate;			
			$result_rates = DB::select(DB::raw("SELECT * FROM gpg_employee_deduction WHERE gpg_employee_id = '".$employee_id."'"));
			if(count($result_rates)>0){
				foreach ($result_rates as $key => $value3) {
					$row_rates = (array)$value3;
					if((float)$row_rates["pw_wage_rate_type"] == 1){
						$basic_hourly_rate_deduction = $row_rates["rate"] ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 2){
						$health_and_welfare_deduction = $row_rates["rate"] ;
						$total_prevailing_other_deduction_rate +=  $health_and_welfare_deduction ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 3){
						$pension_deduction = $row_rates["rate"] ;
						$pension_deduction = $pension_deduction/100; // converting to percentage
						$pension_deduction_calc = (((float)$pension_deduction / 2) > 0.03) ? ((float)$basic_hourly_rate_job * 0.03) : ((float)$basic_hourly_rate_job * ((float)$pension_deduction / 2)) ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 4){
						$vacations_deduction = $row_rates["rate"] ;
						$total_prevailing_other_deduction_rate +=  $vacations_deduction ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 5){
						$training_deduction = $row_rates["rate"] ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 6){
						$other_payments_deduction = $row_rates["rate"] ;
						$total_prevailing_other_deduction_rate +=  $other_payments_deduction ;
					}
				}//while
			}//endif
			if($basic_hourly_rate_job <= 0){
				$job_prevailing_rate = $row["pw_reg"] ;
				$basic_hourly_rate_job = $job_prevailing_rate ;
			} 
			if(((float)$total_prevailing_rate_sum > 0) || ((float)$job_prevailing_rate > 0)){
				if((float)$perHourLabor > (float)$basic_hourly_rate_job){
					$calc_basic_hourly_rate_deduction = (float)$perHourLabor - (float)$basic_hourly_rate_job ;
					$calc_basic_hourly_rate_deduction = ((float)$basic_hourly_rate_deduction > 0) ? (($calc_basic_hourly_rate_deduction > $basic_hourly_rate_deduction) ? ($calc_basic_hourly_rate_deduction - $basic_hourly_rate_deduction) : 0) : $calc_basic_hourly_rate_deduction ;
				}
				elseif((float)$perHourLabor < (float)$basic_hourly_rate_job){
					$calc_basic_hourly_rate_job = 0 ;
					$calc_basic_hourly_rate_deduction = 0 ;
				}
			}
			$grand_total_prevailing_job_rates = $calc_basic_hourly_rate_job + $total_prevailing_other_job_rate ;
			$grand_total_employee_deduction = $calc_basic_hourly_rate_deduction + $total_prevailing_other_deduction_rate + $pension_deduction_calc ;
			$grand_total_deduction_hourly_rate  = $perHourLabor + $basic_hourly_rate_deduction ;
			$employee_actual_job_rate = $grand_total_prevailing_job_rates - $grand_total_employee_deduction ;
			$actual_weca_rate = $training_job - $training_deduction ;
			$prevailing_hours = (float)$row["total_prevailing_hours"] ;
			$total_employee_actual_rate = $prevailing_hours * $employee_actual_job_rate ;
			$grand_total_employee_actual_rate += ($total_employee_actual_rate > 0) ? $total_employee_actual_rate : 0  ;
			$total_actual_weca_rate = $prevailing_hours * $actual_weca_rate ;
			$grand_total_actual_weca_rate += ($total_actual_weca_rate > 0) ? $total_actual_weca_rate : 0 ;
			if($count == 0){
				$emp_name = $row['employee_name'];
				$count++;
			}
		}
	}
		$c_name = ($c_name=="xl118_y333"?"xl118_y33":"xl118_y333");
		if(strlen($emp_name)>0){
	?>
        <tr>
            <td height="40" align="center" colspan="7"><? echo $emp_name ?></td>
            <td align="center" colspan="1"><?php echo $perHourLabor ; ?></td>
            <td align="center" colspan="1"><?php echo ((float)$grand_total_employee_actual_rate > 0) ? $grand_total_employee_actual_rate : '-';?></td>
            <td align="center" colspan="1"><?php echo ((float)$grand_total_actual_weca_rate > 0) ? $grand_total_actual_weca_rate : '-';?></td>
        </tr>
    <?php
		}
	}
	elseif($Etype==2){ 
?>
		<tr>
          	<td>Employee</td>
            <td>Job Num</td>
            <td>Customer</td>
          	<td>Employee Rate</td>
          	<td>Wage Rate</td>
           	<td align="center">H & W Rate</td>
           	<td align="center">Pension</td>
           	<td align="center">Vacations</td>
           	<td align="center">Training</td>
           	<td align="center">Other</td>
           	<td align="center" width="30pt">Hours</td>
            <td align="center" width="30pt">Total</td>
            <td align="center" width="30pt">WECA</td>
            <td align="center" width="30pt">Paid to Employee</td>
            <td align="center" width="30pt">Total by Employee</td>
            <td align="center" width="30pt">WECA by Employee</td>
		</tr>
			<?
			$repeat_employee_name = "";
			$start_employee_total = "";
			$start_employee_total_chk = 0;
		foreach ($query_data as $key => $value11) { 
			$row = (array)$value11;
		    $totalCount++ ;
		    $perHourLabor = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$row['GPG_employee_Id'])->where('type','=','h')->where('start_date','<=',$row['timesheet_date'])->OrderBy('start_date','DESC')->pluck('rate');
		    $employee_id = $row["GPG_employee_Id"] ;
		    if(!isset($array_check_row[$row['employee_name']])){
		    	$colcount++;
				$previous_employee_name = $row['employee_name'] ;
				$total_cells = "";
				$total_weca_cells = "";
			}
			$array_check_row[$row['employee_name']] = $row['employee_name'] ;
			$perHourLabor = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$employee_id)->where('type','=','h')->where('start_date','<=',$row['timesheet_date'])->OrderBy('start_date','DESC')->pluck('rate');
			$perHourLabor = ((float)$perHourLabor > 0) ? $perHourLabor : $row["labor_rate"] ;
			$basic_hourly_rate_job = 0 ;
			$health_and_welfare_job = 0 ;
			$pension_job = 0 ;
			$vacations_job = 0 ;
			$training_job = 0 ;
			$other_payments_job = 0 ;
			$total_prevailing_other_job_rate = 0 ;
			$basic_hourly_rate_deduction = 0 ;
			$health_and_welfare_deduction = 0 ;
			$pension_deduction = 0 ;
			$pension_deduction_calc = 0 ;
			$vacations_deduction = 0 ;
			$training_deduction = 0 ;
			$other_payments_deduction = 0 ;
			$total_prevailing_other_deduction_rate = 0 ;
			$grand_total_employee_deduction = 0 ;
			$grand_total_prevailing_job_rates = 0;
			$calc_basic_hourly_rate_job = 0 ;
			$grand_total_deduction_hourly_rate = 0 ;
			$calc_basic_hourly_rate_deduction = 0;
			$job_prevailing_rate = 0 ;
			$total_employee_actual_rate = 0 ;
			$total_actual_weca_rate = 0 ;
		if((float)$perHourLabor > 0){
			$result_rates = DB::select(DB::raw("SELECT * FROM gpg_job_rates_breakup WHERE job_rates_id = '".$row['job_rate_id']."'"));
			foreach ($result_rates as $key => $value4) {
				$row_rates = (array)$value4;
				if((float)$row_rates["pw_wages_rate_type"] == 1){
					$basic_hourly_rate_job = $row_rates["rate"] ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 2){
					$health_and_welfare_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $health_and_welfare_job ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 3){
					$pension_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $pension_job ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 4){
					$vacations_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $vacations_job ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 5){
					$training_job = $row_rates["rate"] ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 6){
					$other_payments_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $other_payments_job ;
				}
			}//while
			
			$total_prevailing_rate_sum0 = DB::select(DB::raw("SELECT IFNULL(SUM(rate),0) as rate FROM gpg_job_rates_breakup WHERE job_rates_id = '".$row['job_rate_id']."'"));
			$total_prevailing_rate_sum = @$total_prevailing_rate_sum0[0]->rate;
			$result_rates = DB::select(DB::raw("SELECT * FROM gpg_employee_deduction WHERE gpg_employee_id = '".$employee_id."'"));
			if(count($result_rates)){
				foreach ($result_rates as $key => $value5) {
					$row_rates = (array)$value5;
					if((float)$row_rates["pw_wage_rate_type"] == 1){
						$basic_hourly_rate_deduction = $row_rates["rate"] ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 2){
						$health_and_welfare_deduction = $row_rates["rate"] ;
						$total_prevailing_other_deduction_rate +=  $health_and_welfare_deduction ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 3){
						$pension_deduction = $row_rates["rate"] ;
						$pension_deduction_calc = (((float)$pension_deduction / 2) > 0.03) ? ((float)$basic_hourly_rate_job * 0.03) : ((float)$basic_hourly_rate_job * ((float)$pension_deduction / 2)) ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 4){
						$vacations_deduction = $row_rates["rate"] ;
						$total_prevailing_other_deduction_rate +=  $vacations_deduction ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 5){
						$training_deduction = $row_rates["rate"] ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 6){
						$other_payments_deduction = $row_rates["rate"] ;
						$total_prevailing_other_deduction_rate +=  $other_payments_deduction ;
					}
				}
			}
			if($basic_hourly_rate_job <= 0){
				$job_prevailing_rate = $row["pw_reg"] ;
				$basic_hourly_rate_job = $job_prevailing_rate ;
			} 			
			if(((float)$total_prevailing_rate_sum > 0) || ((float)$job_prevailing_rate > 0)){
				if((float)$perHourLabor > (float)$basic_hourly_rate_job){
					$calc_basic_hourly_rate_deduction = (float)$perHourLabor - (float)$basic_hourly_rate_job ;
					$calc_basic_hourly_rate_deduction = ((float)$basic_hourly_rate_deduction > 0) ? (($calc_basic_hourly_rate_deduction > $basic_hourly_rate_deduction) ? ($calc_basic_hourly_rate_deduction - $basic_hourly_rate_deduction) : 0) : $calc_basic_hourly_rate_deduction ;
				}//if
				elseif((float)$perHourLabor < (float)$basic_hourly_rate_job){
					$calc_basic_hourly_rate_job = 0 ;
					$calc_basic_hourly_rate_deduction = 0 ;
				}//elseif
			}
			$grand_total_prevailing_job_rates = $calc_basic_hourly_rate_job + $total_prevailing_other_job_rate ;
			$grand_total_employee_deduction = $calc_basic_hourly_rate_deduction + $total_prevailing_other_deduction_rate + $pension_deduction_calc ;
			$grand_total_deduction_hourly_rate  = $perHourLabor + $basic_hourly_rate_deduction ;
			$employee_actual_job_rate = $grand_total_prevailing_job_rates - $grand_total_employee_deduction ;
			$actual_weca_rate = $training_job - $training_deduction ;
			$actual_weca_rate = $row['time_employee_type_id'];
			$prevailing_hours = (float)$row["total_prevailing_hours"] ;
			$total_employee_actual_rate = $prevailing_hours * $employee_actual_job_rate ;
			$grand_total_employee_actual_rate += ($total_employee_actual_rate > 0) ? $total_employee_actual_rate : 0  ;
			$total_actual_weca_rate = $prevailing_hours * $actual_weca_rate ;
			$grand_total_actual_weca_rate += ($total_actual_weca_rate > 0) ? $total_actual_weca_rate : 0 ;
		}
		$class_name_num_only = '';
		if($start_employee_total_chk==0){
			$repeat_employee_name = $row['employee_name'];
			$start_employee_total = $jobColCount+4;
			$start_employee_total_chk++;
		}
		elseif($repeat_employee_name != $row['employee_name']){
?>
		<td align="right"></td>
        <td align="right">{{$start_employee_total+$jobColCount+3}}</td>
		<?
		$repeat_employee_name = $row['employee_name'];
		$start_employee_total = $jobColCount+4;
		$start_employee_total_chk++;
	}
	else
	{
		echo "</tr>";
	}
		
?>
    <tr>
    	<td height="30" align="center"><? echo $row['employee_name'] ?></strong></td>
        <td><strong><?php echo $row["job_num"]?></strong></td>
        <td><strong><?php echo $row["cus_name_detail"]?></strong></td>
        <td align="right"><?php echo ($perHourLabor > 0) ? $perHourLabor : '-' ;?></td>
        <td align="right"><?php echo ($basic_hourly_rate_job > 0) ? $basic_hourly_rate_job : '-' ;?></td>
        
        <td align="right"> <?php echo ($health_and_welfare_job > 0) ? (" \"".$health_and_welfare_job."-".(($health_and_welfare_deduction > 0) ? $health_and_welfare_deduction : '0')."\">"): '>0'?></td>
        <td align="right"> <?php echo ($pension_job > 0) ? (" \"".$pension_job."-(IF(".$pension_deduction."/2>0.03,0.03,".($pension_deduction)."/2)*E".($jobColCount+4).")\">") : '>0';?></td>
        <td align="right"> <?php echo ($vacations_job > 0) ? (" \"".$vacations_job."-".(($vacations_deduction > 0) ? $vacations_deduction : '0')."\">" ): '>0'?></td>
        <td align="right"> <?php echo ($training_job > 0) ? (" \"".$training_job."-".(($training_deduction > 0) ? $training_deduction : '0')."\">" ): '>0'?></td>
        <td align="right"> <?php echo ($other_payments_job > 0) ? (" \"".$other_payments_job."-".(($other_payments_deduction > 0) ? $other_payments_deduction : '0')."\">" ): '>0'?></td>
        
        <td align="right"><b><?php echo ($prevailing_hours > 0) ? $prevailing_hours : '-'?></b></td>
        <td align="right">{{$jobColCount+4}}</td>
        <td align="right"><?php echo $row['time_employee_type_id']==4?"0":("I".($jobColCount+4)."*K".($jobColCount+4))?></td>
        <td align="right"><?php echo $jobColCount+4?>*L<?php echo $jobColCount+4?></td>
        
		<? 
		$total_cells .= "K".($jobColCount+6).",";
		$total_weca_cells .= "K".($jobColCount+7).",";
		$jobColCount++ ;	
	}
	if($start_employee_total_chk > 0){
?>
		<td align="right"><?php echo $start_employee_total?>:N<?php echo $jobColCount+3?></td>
		<td align="right"><?php echo $start_employee_total?>:M<?php echo $jobColCount+3?></td>
<?php
		$repeat_employee_name = $row['employee_name'];
		$start_employee_total = $jobColCount+4;
		$start_employee_total_chk++;
	}
?>
</table>
<?
}
	else{ // detailed export
		foreach ($query_data as $key => $value01) {
			$row = (array)$value01;
			$totalCount++ ;
	   		$perHourLabor = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$row['GPG_employee_Id'])->where('type','=','h')->where('start_date','<=',$row['timesheet_date'])->OrderBy('start_date','DESC')->pluck('rate');
		   	$employee_id = $row["GPG_employee_Id"] ;
			if(!isset($array_check_row[$row['employee_name']])){
				if($colcount > 0){
					if(strlen($total_cells)>0){
						$total_cells = substr($total_cells,0,strlen($total_cells)-1);
						$total_weca_cells = substr($total_weca_cells,0,strlen($total_weca_cells)-1);
					}
		?>
            <tr >
                <td colspan="7" rowspan="2" valign="middle" style="text-align:right;padding-right:12;vertical-align:middle">Grand Totals</td>
                <td>Employee</td>
                <td align="right" colspan="2"><strong>?php echo $total_cells?></strong></td>
            </tr>
            <tr>
                <td colspan="1">Training (Weca)</td>
                <td align="right" colspan="2">&nbsp;<strong><?php echo $total_weca_cells?></strong></td>
            </tr>
            <tr>
                <td colspan="10">&nbsp;</td>
            </tr>
            <?php
				$jobColCount+=3;
				$grand_total_employee_actual_rate = 0;
			  	$grand_total_actual_weca_rate = 0;
				}
			}
		if(!isset($array_check_row[$row['employee_name']])){
			$colcount++;
			$previous_employee_name = $row['employee_name'] ;
			$jobColCount+=3;
			$total_cells = "";
			$total_weca_cells = "";
		?>
        <tr>
          <td height="30" align="center" colspan="5"><? echo $row['employee_name'] ?></strong></td>
          <td align="center" colspan="5"><?php echo $perHourLabor ; ?></td>
		</tr>
        <tr>
          	<td colspan="2">&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
          	<td>Employee Rate</td>
           	<td>Wage Rate</td>
           	<td align="center">H & W Rate</td>
          	<td align="center">Pension</td>
          	<td align="center">Vacations</td>
           	<td align="center">Training</td>
           	<td align="center">Other</td>
           	<td align="center" width="30pt">Total</td>
           	<td align="center" width="30pt">Hours</td>
		</tr>
          <? }
			$array_check_row[$row['employee_name']] = $row['employee_name'] ;
			?>
		<?
			$perHourLabor = DB::table('gpg_employee_wage')->where('gpg_employee_id','=',$employee_id)->where('type','=','h')->where('start_date','<=',$row['timesheet_date'])->OrderBy('start_date','DESC')->pluck('rate');
			$perHourLabor = ((float)$perHourLabor > 0) ? $perHourLabor : $row["labor_rate"] ;
			$basic_hourly_rate_job = 0 ;
			$health_and_welfare_job = 0 ;
			$pension_job = 0 ;
			$vacations_job = 0 ;
			$training_job = 0 ;
			$other_payments_job = 0 ;
			$total_prevailing_other_job_rate = 0 ;
			$basic_hourly_rate_deduction = 0 ;
			$health_and_welfare_deduction = 0 ;
			$pension_deduction = 0 ;
			$pension_deduction_calc = 0 ;
			$vacations_deduction = 0 ;
			$training_deduction = 0 ;
			$other_payments_deduction = 0 ;
			$grand_total_employee_actual_rate =0;
			$total_prevailing_other_deduction_rate = 0 ;
			$grand_total_employee_deduction = 0 ;
			$grand_total_prevailing_job_rates = 0;
			$calc_basic_hourly_rate_job = 0 ;
			$grand_total_deduction_hourly_rate = 0 ;
			$calc_basic_hourly_rate_deduction = 0;
			$job_prevailing_rate = 0 ;
			$total_employee_actual_rate = 0 ;
			$total_actual_weca_rate = 0 ;
		if((float)$perHourLabor > 0){
			$result_rates = DB::select(DB::raw("SELECT * FROM gpg_job_rates_breakup WHERE job_rates_id = '".$row['job_rate_id']."'"));
			foreach ($result_rates as $key => $value6) {
				$row_rates = (array)$value6;
				if((float)$row_rates["pw_wages_rate_type"] == 1){
					$basic_hourly_rate_job = $row_rates["rate"] ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 2){
					$health_and_welfare_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $health_and_welfare_job ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 3){
					$pension_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $pension_job ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 4){
					$vacations_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $vacations_job ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 5){
					$training_job = $row_rates["rate"] ;
				}
				elseif((float)$row_rates["pw_wages_rate_type"] == 6){
					$other_payments_job = $row_rates["rate"] ;
					$total_prevailing_other_job_rate +=  $other_payments_job ;
				}
			}//while
			
			$total_prevailing_rate_sum0 = DB::select(DB::raw("SELECT IFNULL(SUM(rate),0) as rate FROM gpg_job_rates_breakup WHERE job_rates_id = '".$row['job_rate_id']."'"));
			$total_prevailing_rate_sum = @$total_prevailing_rate_sum0[0]->rate;
			$result_rates = DB::select(DB::raw("SELECT * FROM gpg_employee_deduction WHERE gpg_employee_id = '".$employee_id."'"));
			if(count($result_rates)>0){
				foreach ($result_rates as $key => $value7) {
					$row_rates = (array)$value7;
					if((float)$row_rates["pw_wage_rate_type"] == 1){
						$basic_hourly_rate_deduction = $row_rates["rate"] ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 2){
						$health_and_welfare_deduction = $row_rates["rate"] ;
						$total_prevailing_other_deduction_rate +=  $health_and_welfare_deduction ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 3){
						$pension_deduction = $row_rates["rate"] ;
						$pension_deduction_calc = (((float)$pension_deduction / 2) > 0.03) ? ((float)$basic_hourly_rate_job * 0.03) : ((float)$basic_hourly_rate_job * ((float)$pension_deduction / 2)) ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 4){
						$vacations_deduction = $row_rates["rate"] ;
						$total_prevailing_other_deduction_rate +=  $vacations_deduction ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 5){
						$training_deduction = $row_rates["rate"] ;
					}
					elseif((float)$row_rates["pw_wage_rate_type"] == 6){
						$other_payments_deduction = $row_rates["rate"] ;
						$total_prevailing_other_deduction_rate +=  $other_payments_deduction ;
					}
				}//while
			}//endif
			if($basic_hourly_rate_job <= 0){
				$job_prevailing_rate = $row["pw_reg"] ;
				$basic_hourly_rate_job = $job_prevailing_rate ;
			} 
			if(((float)$total_prevailing_rate_sum > 0) || ((float)$job_prevailing_rate > 0)){
				if((float)$perHourLabor > (float)$basic_hourly_rate_job){
					$calc_basic_hourly_rate_deduction = (float)$perHourLabor - (float)$basic_hourly_rate_job ;
					$calc_basic_hourly_rate_deduction = ((float)$basic_hourly_rate_deduction > 0) ? (($calc_basic_hourly_rate_deduction > $basic_hourly_rate_deduction) ? ($calc_basic_hourly_rate_deduction - $basic_hourly_rate_deduction) : 0) : $calc_basic_hourly_rate_deduction ;
				}
				elseif((float)$perHourLabor < (float)$basic_hourly_rate_job){
					//$calc_basic_hourly_rate_job = (float)$basic_hourly_rate_job - (float)$perHourLabor ;
					$calc_basic_hourly_rate_job = 0 ;
					$calc_basic_hourly_rate_deduction = 0 ;
				}
			}
			$grand_total_prevailing_job_rates = $calc_basic_hourly_rate_job + $total_prevailing_other_job_rate ;
			$grand_total_employee_deduction = $calc_basic_hourly_rate_deduction + $total_prevailing_other_deduction_rate + $pension_deduction_calc ;
			$grand_total_deduction_hourly_rate  = $perHourLabor + $basic_hourly_rate_deduction ;
			$employee_actual_job_rate = $grand_total_prevailing_job_rates - $grand_total_employee_deduction ;
			$actual_weca_rate = $training_job - $training_deduction ;
			$prevailing_hours = (float)$row["total_prevailing_hours"] ;
			$total_employee_actual_rate = $prevailing_hours * $employee_actual_job_rate ;
			$grand_total_employee_actual_rate += ($total_employee_actual_rate > 0) ? $total_employee_actual_rate : 0  ;
			$total_actual_weca_rate = $prevailing_hours * $actual_weca_rate ;
			$grand_total_actual_weca_rate += ($total_actual_weca_rate > 0) ? $total_actual_weca_rate : 0 ;
		}
			$class_name = '';
			$class_name_num_only = '';
?>
    <tr><td colspan="10">&nbsp;</td></tr>
    <tr >
        <td><strong><?php echo $row["job_num"]?></strong></td>
        <td align="right"><?php echo ($perHourLabor > 0) ? $perHourLabor : '-' ;?></td>
        <td align="right"><?php echo ($basic_hourly_rate_job > 0) ? $basic_hourly_rate_job : '-' ;?></td>
        <td align="right"><?php echo ($health_and_welfare_job > 0) ? $health_and_welfare_job : '-';?></td>
        <td align="right" ><?php echo ($pension_job > 0) ? $pension_job : '-';?></td>
        <td align="right"><?php echo ($vacations_job > 0) ? $vacations_job : '-';?></td>
        <td align="right"><?php echo ($training_job > 0) ? $training_job : '-'?></td>
        <td align="right"><?php echo ($other_payments_job > 0) ? $other_payments_job : '-'?></td>
        <td align="right"><?php echo $jobColCount+4?>:F<?php echo $jobColCount+4?>,H<?php echo $jobColCount+4?></td>
        <td align="right"><b><?php echo ($prevailing_hours > 0) ? $prevailing_hours : '-'?></b></td>
    </tr>
    <tr>
        <td><strong>Employee Deductions</strong></td>
        <td>&nbsp;</td>
        <td align="right"><?php echo $jobColCount+4?>>C<?php echo $jobColCount+4?>,B<?php echo $jobColCount+4?>-C<?php echo $jobColCount+4?></td>
        <td align="right"><?php echo ($health_and_welfare_deduction > 0) ? $health_and_welfare_deduction : '-'?></td>
        <td align="right"><?php echo $pension_deduction?>/2>0.03,0.03,<?php echo $pension_deduction/2?><?php echo $jobColCount+4?></td>
        <td align="right"><?php echo ($vacations_deduction > 0) ? $vacations_deduction : '-'?></td>
        <td align="right"><?php echo ($training_deduction > 0) ? $training_deduction : '-'?></td>
        <td align="right"><?php echo ($other_payments_deduction > 0) ? $other_payments_deduction : '-'?></td>
        <td align="right"><?php echo $jobColCount+5?>:H<?php echo $jobColCount+5?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8" align="right" style="text-align:right;padding-right:12"><b>Difference</b></td>
        <td align="right"><b><?php echo $jobColCount+4?>-I<?php echo $jobColCount+5?>>0,I<?php echo $jobColCount+4?>-I<?php echo $jobColCount+5?></b></td>
        <td align="right"><b><?php echo $jobColCount+4?>*I<?php echo $jobColCount+6?></b></td>
    </tr>
    <tr>
        <td colspan="8" align="right" style="text-align:right;padding-right:12"><b>Training Difference (WECA)</b></td>
        <td align="right"><b><?php echo $jobColCount+4?>-G<?php echo $jobColCount+5?>>0,G<?php echo $jobColCount+4?>-G<?php echo $jobColCount+5?></b></td>
        <td align="right"><?php echo $jobColCount+4?>*I<?php echo $jobColCount+7?><b></td>
    </tr>
    <tr>
        <td colspan="10">&nbsp;</td>
     </tr>
		<? 
		$total_cells .= "J".($jobColCount+6).",";
		$total_weca_cells .= "J".($jobColCount+7).",";
		$jobColCount++ ;	
		$jobColCount+=5;
	}
	if(strlen($total_cells)>0){
		$total_cells = substr($total_cells,0,strlen($total_cells)-1);
		$total_weca_cells = substr($total_weca_cells,0,strlen($total_weca_cells)-1);
	}
	?>
<tr >
	<td colspan="7" rowspan="2" valign="middle" style="text-align:right;padding-right:12;vertical-align:middle">Grand Totals</td>
	<td>Wage</td>
	<td align="right" colspan="2"><?php echo $total_cells;?><strong></strong></td>
</tr>
<tr>
	<td colspan="1">Training (Weca)</td>
	<td align="right" colspan="2"><?php echo $total_weca_cells?><strong></strong></td>
</tr>
<tr>
	<td colspan="10">&nbsp;</td>
</tr>
<tr>
	<td colspan="10">&nbsp;</td>
</tr>
</table>
<?
}
?>