<table>
 <col width=64>
 <col width=71>
 <col width=64>
 <col width=105>
 <col width=86>
 <col width=94>
 <col width=103>
 <col width=86>
 <col width=70>
 <col width=90>
 <col width=64>
 <tr height=21>
  <td height=21>Time Report for JOB <?php echo $jobNum;?></td>
  <td width=64></td>
  <td width=105></td>
  <td width=86></td>
  <td width=94></td>
  <td width=103></td>
  <td width=86></td>
  <td width=70></td>
  <td width=90></td>
  <td width=64></td>
 </tr>
 <tr height=17>
  <td height=17>Labor</td>
  <td colspan=12></td>
 </tr>
 <tr height=17>
  <td height=17>Tech</td>
  <td>Type</td>
  <td>Date</td>
  <td>Time In</td>
  <td>Time Out</td>
  <td>Total</td>
  <td>Total in Decimal</td>
  <td>Reg</td>
  <td>OT</td>
  <td>DT</td>
  <td>Reg $</td>
  <td>OT $</td>
  <td>DT $</td>
  <td>Total $</td>
 </tr>
 <?php
 $tHrs = 0.0;
 $tWage = 0.0;
 if(isset($jobNum) && !empty($jobNum))
 { 
    $tHrs = 0.0;
    $tWage = 0.0;
    $laborDbfieldCounter = 0;
    foreach ($laborDataRows as $key => $laborDataRow){ 
  		$prevail=0;
			if(@$laborDataRow['pw_flag']=='1' && (@$laborDataRow['timetypId'] ==1 || @$laborDataRow['timetypId'] ==2)){
				$prevail = 1;
			}
			$timetyp = DB::table('gpg_timetype')->where('id','=',@$laborDataRow['timetypId'])->pluck('name');
  ?>
 <tr height=17>
  <td ><?php echo @$laborDataRow['emp_name']; ?></td>
  <td ><?php echo ($prevail==1)?"<strong>PREV</strong>&nbsp;/&nbsp;".$timetyp:$timetyp;?></td>
  <td><?php echo (!empty($laborDataRow['date'])?date('m/d/Y',strtotime($laborDataRow['date'])):''); ?></td>
  <td><?php echo (!empty($laborDataRow['time_in'])?date('HH:MM',strtotime($laborDataRow['time_in'])):''); ?></td>
  <td><?php echo (!empty($laborDataRow['time_out'])?date('HH:MM',strtotime($laborDataRow['time_out'])):''); ?></td>
  <td><?php 
        $start_date = new DateTime(@$laborDataRow['time_in']);
        $since_start = $start_date->diff(new DateTime(@$laborDataRow['time_out']));
        echo $since_start->h.":".($since_start->i==0?"00":$since_start->i);
	
	?></td>
  <td><?php echo $totalHours = @$laborDataRow['time_diff_dec'];
                 $tHrs = $tHrs + $totalHours ;?>
  </td>
  <?php
				$rH=0.0;
				$otH=0.0;
				$dtH=0.0;
				$pw_reg= @$laborDataRow['pw_reg'];
				$pw_ot = @$laborDataRow['pw_ot'];
				$pw_dt = @$laborDataRow['pw_dt'];
				$perHourLabor = @$laborDataRow['LaborRate'];
        if ($totalHours<=8) { $rH = $totalHours; }
        else if ($totalHours>8 && $totalHours<=12) { $rH = 8; $otH = $totalHours-8; } 
        else if ($totalHours>12) { $rH = 8; $otH = 4; $dtH = $totalHours-12; }
  		  $regWage = @round($rH*($prevail==1?$pw_reg:$perHourLabor),2);
				$otWage =@round($otH*($prevail==1?$pw_ot:($perHourLabor*1.5)),2);
				$dtWage = @round($dtH*($prevail==1?$pw_dt:($perHourLabor*2)),2);
				$totalWage = $regWage + $otWage + $dtWage;
?>
  <td><?php echo $rH;?></td>
  <td><?php echo $otH;?></td>
  <td><?php echo $dtH;?></td>
  <td><?php echo $regWage;?></td>
  <td><?php echo $otWage;?></td>
  <td><?php echo $dtWage;?></td>
  <td><?php $tWage  = $tWage + $totalWage;echo $totalWage;?>
</tr>
 <?php } } ?>
 <tr height=21>
  <td height=21></td>
  <td>TOTALS</td>
  <td>Total Hours</td>
  <td><?php echo $tHrs.($tHrs!=''?" Hrs":"");?></td>
  <td>Grand Total</td>
  <td x:num="<?php echo $tWage?>"> </td>
 </tr>
 <tr height=21>
  <td height=21 colspan=6></td>
  <td></td>
  <td colspan=2></td>
  <td colspan=4></td>
 </tr>
 </table>
