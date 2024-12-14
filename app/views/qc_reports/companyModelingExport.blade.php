<?php
$ending_column =0;
$starting_column =0;
$non_billable = array(6,5,7);
function num2alpha($n)
{
    for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
        $r = chr($n%26 + 0x41) . $r;
    return $r;
}
?>
<table border=0 cellpadding=0 cellspacing=0 width=1796 style='border-collapse: collapse;table-layout:fixed;width:826pt'>
  <tr>
	  <td>Start Date</td>
    <td><?php echo $SDate;?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>75.00%</td>
  </tr>
  <tr>
	  <td>End Date</td>
    <td><?php echo $EDate;?></td>
    <td></td><td></td><td></td><td>15%</td>
  </tr>
  <tr>
		    <td align="center" bgcolor="#EEEEEE" style="width:250px;">Start Date</td>
        <td align="center" bgcolor="#EEEEEE" style="width:250px;">End Date</td>
        <td align="center" bgcolor="#EEEEEE" style="width:250px;">Employee Type </td>
        <td align="center" bgcolor="#EEEEEE" style="width:250px;">Department </td>
        <td align="center" bgcolor="#EEEEEE" style="width:250px;">Location </td>
        <td bgcolor="#EEEEEE" style="width:200px;">Real Name </td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Hourly</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">&nbsp;&nbsp;&nbsp;&nbsp;Salary&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Total Salary</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Actual Salary</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Payroll Taxes</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Medical</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Dental</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Benefits</td>
        <?php 
		if(sizeof($arr_excluded_ids)>0)
		{
			foreach($arr_excluded_ids as $key => $val)
			{
				?>
                <td align="center" bgcolor="#EEEEEE" style="width:100px;"><?php echo @$all_arr[$val]["title"]?></td>
                <?php 
			}
		}
		?>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Total EE (Bill)</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Per Hour (Bill)</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Total EE (NonB)</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Per Hour (NonB)</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Alloc of EE NonB</td>
        <td align="center" bgcolor="#EEEEEE" style="width:136px;">Alloc of EE NonB/HR</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Non EE costs</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Non EE costs/HR</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Cost/Hr</td>
        <td class="xl261" align="center" bgcolor="#EEEEEE" style="mso-width-source:userset;width:550px;">Tot Costs</td>
        <td class="xl261" align="center" bgcolor="#EEEEEE" style="width:100px;">Cost per Day</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Cost per Wk</td>
        <td align="center" bgcolor="#EEEEEE" style="width:100px;">Cost per Mon</td>
       </tr>
        <?php  
		$rowstart = 3;
		$rowcount = $rowstart;
    $colcount=0;
		$arr_employee_types = array();
		$total_recs = count($result);
	 $gl_code_expense_array = array();
    foreach ($result as $key => $value) {
        $row = (array)$value;
        $gl_code_expense_array = array();
        if($row['status'] == 'A'){
          $gl_code_expense_array = @$row['gl_code_expenses'];
        }
        if($row['status'] == 'A'){
        }
  	   $colcount++;
		   $rowcount++;
		
		  $query = DB::select(DB::raw("SELECT rate, start_date FROM gpg_employee_wage WHERE gpg_employee_id = '".$row['id']."' AND type = '".($row['salaried']==1?'s':'h')."' AND start_date <= '".date('m/d/Y')."' ORDER BY start_date DESC LIMIT 0,1"));
      $curRow = array();
      foreach ($query as $key => $value) {
          $curRow = (array)$value;
      }  
		?>
  <tr>
		<td><?php echo @$curRow['start_date']?date('m/d/Y',strtotime(@$curRow['start_date'])):"-"?></td>
    <td><?php echo $row['terminated_date']?($row['terminated_date']!="0000-00-00"?date('m/d/Y',strtotime($row['terminated_date'])):"-"):"-"?></td>
    <td ><?php  
		$typeName = $row['emp_type'];
		$arr_employee_types[$row['GPG_employee_type_id']] = $typeName;
		echo ($typeName==""?"-":$typeName);
		?></td>
    <?php 
      $department_name0 = DB::select(DB::raw("SELECT
                      GROUP_CONCAT(gpg_department.dept_name) as dept
                      FROM gpg_department,
                           gpg_department_user
                      WHERE gpg_department.id = gpg_department_user.gpg_department_id
                      AND gpg_department_user.gpg_employee_id='".$row['id']."'"));
      $department_name = @$department_name0[0]->dept;
		  ?>
          <td height="30" >&nbsp;<?php  echo $department_name ?></td>
          <td height="30" >&nbsp;<?php  echo DB::table('gpg_employee_location')->where('id','=',$row['gpg_employee_location_id'])->pluck('name'); ?></td>
          <td height="30" >&nbsp;<?php  echo $row['name'] ?></td>
          <?php  
          $totalSalary =  0;
          $salary = 0;
          if (isset($curRow['rate']) && $curRow['rate']>0) {
            if($row['salaried']==1){
				  if($row['terminated_date'] && $row['terminated_date']!="0000-00-00"){
					if(strtotime($curRow['start_date']) > strtotime($SDate))
						$days_start_date = date('Y-m-d',strtotime($curRow['start_date']));
					else
						$days_start_date = date('Y-m-d',strtotime($SDate));
					if(strtotime($row['terminated_date']) < strtotime($EDate))
						$days_end_date = date('Y-m-d',strtotime($row['terminated_date']));
					else
						$days_end_date = date('Y-m-d',strtotime($EDate));
					$WorkingDays = mysql_result(mysql_query("select DATEDIFF('".$days_end_date."','".$days_start_date."')"),0,0)+1;
					$salary = "(H".$rowcount."/24*CEILING(".$WorkingDays."/16,1))";
				}
				else
				{
				   $salary = "(H".$rowcount."/24*CEILING(".$tDays."/16,1))";
				}
				echo '<td></td><td align="right">'.$curRow['rate']."</td>";
		  }
			else
				echo '<td align="right">'.$curRow['rate']."</td><td></td>";
		  }
		  else
		  {
			  ?><td></td><td></td><?php 
		  }
		?>
    </td> 
      <td align="right"><?php echo ($rowcount*2080)+$rowcount; ?></td>
      <td><?php echo $salary?></td>
		  <td><?php echo $rowcount;?></td>
      <td></td>
      <td></td>
      <td bgcolor="#CCFFCC" align="right"><?php echo $rowcount?>)*12)</td>
      <?php 
		    $expense_cell_value = 0;
            if(sizeof($arr_excluded_ids)>0){
      				foreach($arr_excluded_ids as $key => $val){
                  if($row['status'] == 'A' && is_array($gl_code_expense_array)){
                      foreach($gl_code_expense_array as $keyGl => $glCodeData){
                        if(trim($glCodeData['title']) == trim($all_arr[$val]["title"])){
                          $expense_cell_value = $glCodeData['amount']; 
                        } 
                      } 
                  }
                                    ?>
					<td bgcolor="#CCFFCC"><?php echo ($expense_cell_value != "")? $expense_cell_value : 0 ?></td>
					<?php 
					$ending_column++;
				}
			}
 		  $active_column = $starting_column+sizeof($arr_excluded_ids)-1;
      $fmla_bills = "=I".$rowcount."+SUM(".num2alpha($starting_column-1).$rowcount.":".num2alpha($active_column).$rowcount.")";
		  $res = array_search($row['GPG_employee_type_id'],$non_billable);
		  if(is_numeric($res))	// non-billable
		  {
			  if($row['terminated_date'] && $row['terminated_date']!="0000-00-00")
			  {
				  ?>				  
          <td bgcolor="#FFCC99"></td>
				  <td bgcolor="#FFCC99"></td>
				  <td bgcolor="#FFCC99"></td>
				  <td bgcolor="#FFCC99"></td>
				<?php 
			  }
			  else
			  {
				  ?>
				  <td bgcolor="#FFCC99"></td>
				  <td bgcolor="#FFCC99"></td>
				  <td  bgcolor="#FFCC99"><?php echo $fmla_bills?></td>
				  <td  bgcolor="#FFCC99"><?php echo num2alpha($active_column+3)?></td>
				  <?php 
			  }
		  }
		  else		// billable
		  {
			  if($row['terminated_date'] && $row['terminated_date']!="0000-00-00")
			  {
				  ?>
                  <td bgcolor="#FFCC99"></td>
                  <td bgcolor="#FFCC99"></td>
                  <td bgcolor="#FFCC99"></td>
                  <td bgcolor="#FFCC99"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <?php 
			  }
			  else
			  {
				  ?>
          <td bgcolor="#FFCC99"><?php echo $fmla_bills?></td>
          <td bgcolor="#FFCC99"><?php echo num2alpha($active_column+1)?></td>
          <td bgcolor="#FFCC99"></td>
          <td bgcolor="#FFCC99"></td>
          <td><?php echo $rowstart+$total_recs+7?></td>
          <td><?php echo num2alpha($active_column+5)?></td>
          <td><?php echo $rowstart+$total_recs+4+sizeof($arr_final_totals)?></td>
          <td ><?php echo num2alpha($active_column+7)?></td>
          <td><?php echo num2alpha($active_column+2)+$rowcount+num2alpha($active_column+8)+$rowcount+num2alpha($active_column+6)+$rowcount?></td>
          <td><?php echo num2alpha($active_column+9)?></td>
          <td><?php echo num2alpha($active_column+8)?></td>
          <td><?php echo num2alpha($active_column+11)?></td>
          <td><?php echo num2alpha($active_column+12)?></td>
          <?php 
			  }
		  }
		  ?>
      </tr>
        <?php  } ?>
        <tr>
        	<td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align:left">Totals</td>
            
            <td><?php echo $rowstart+1+$rowcount?>G</td>
            <td><?php echo $rowstart+1+$rowcount?>H</td>
            <td><?php echo $rowstart+1+$rowcount?>I</td>
            <td><?php echo $rowstart+1+$rowcount?>J</td>
            <td><?php echo $rowstart+1+$rowcount?>K</td>
            <td><?php echo $rowstart+1+$rowcount?>L</td>
            <td><?php echo $rowstart+1+$rowcount?>M</td>
            <td><?php echo $rowstart+1+$rowcount?>N</td>
            <?php 
            for($loop=$starting_column; $loop<=$active_column+13;$loop++){
				      ?><td><?php echo num2alpha($loop)+$rowstart+1+num2alpha($loop)+$rowcount?></td><?php 
      			}
      			?>
        </tr>
        <tr>
        	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            <?php 
      for($loop=0;$loop<sizeof($arr_excluded_ids);$loop++)
			{
				?><td><?php echo '$'.number_format($all_arr[$arr_excluded_ids[$loop]]['amount'],2)?></td><?php 
			}
			?>
        </tr>
        
        <tr>
        <td></td>
        <td colspan="4" rowspan="<?php echo sizeof($arr_final_totals)+1?>">
            <table>
            <tr>
                 <td bgcolor="#EEEEEE" align="center"><strong>Description</strong></td>
                 <td bgcolor="#EEEEEE" align="center"><strong>Amount</strong></td>
                 <td bgcolor="#EEEEEE" align="center"><strong>&nbsp;&nbsp;Reduction&nbsp;&nbsp;</strong></td>
                 <td bgcolor="#EEEEEE" align="center"><strong>&nbsp;&nbsp;Adjusted OH&nbsp;&nbsp;</strong></td>
               </tr>
              <?php 
			  $start = 1;
			  $grand_debit = 0;
			  $grand_credit = 0;
			  $grand_amount = 0;
			  $cell_start = 7;
			  $cell_end = $cell_start;
              foreach($arr_final_totals as $key => $val)
			  {
				  $start++;
				  $title = @mysql_result(mysql_query("SELECT CONCAT(expense_gl_code,' - ',description) FROM gpg_expense_gl_code WHERE id = '".$key."'"),0,0);
			   
			   $grand_debit += $val['debit_total'];
			   $grand_credit += $val['credit_total'];
			   $grand_amount += $val['amount_total'];
			   ?>

			   <tr height="20px">
               	
               	<td><?php echo $title?></td>
                <td align="right"><?php echo '$'.number_format(@$val['amount_total'],2)?></td>
                 <td align="right"><?php echo '$'.number_format(@$val['exclude_from_oh'],2)?></td>
                 <td></td>
               </tr>
              
               

			   <?php  
			   
			   $cell_end++;
			  }?>
              <tr>
              	<td style="text-align:left;">Total </td><td><?php echo sizeof($arr_final_totals)+1?></td>
                <td><?php echo sizeof($arr_final_totals)+1?></td>
                <td><?php echo sizeof($arr_final_totals)+1?></td>
              </tr>
              </table></td>
              </tr>
              <tr>
              </tr>
              <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Total Employees</td><td><?php echo $rowstart+1+$rowcount?></td>
              </tr>
              <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Total Billable Employees</td><td><?php echo num2alpha($active_column+1)+$rowstart+1+num2alpha($active_column+1)+$rowcount?></td>
              </tr>
              <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Total Non-Billable Employees</td><td><?php echo num2alpha($active_column+3)+$rowstart+1+num2alpha($active_column+3)+$rowcount?></td>
              </tr>
              <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Ration of Billable to Non-Billable</td><td><?php echo $rowcount+7+$rowcount+8?></td>
              </tr>
              <tr>
              </tr>
              <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Employee Related Costs</td><td><?php echo num2alpha($active_column+1)+$rowcount+1+num2alpha($active_column+3)+$rowcount+1?></td>
              </tr>
              <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Employee Costs Related to Billable</td><td><?php echo num2alpha($active_column+1)?></td>
              </tr>
              <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Employee Costs OH</td><td><?php echo num2alpha($active_column+3)+$rowcount+1?></td>
              </tr>
             <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Total Costs</td><td><?php echo $rowcount+4+sizeof($arr_final_totals);?></td>
              </tr>
             <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Costs that are not EE Related</td><td><?php echo $rowcount+14-$rowcount+11?></td>
              </tr>
              <tr></tr>
              <tr></tr>
              <tr>
              <td></td>
                  <td></td><td></td><td></td><td></td><td></td>
                  <td>Description</td>
                  <td>Costs</td>
                  <td>Active</td>
                  <td>Total</td>
              </tr>
              <?php 
              foreach($arr_employee_types as $key => $val)
			  {
				  $val = $val?$val:"-";
				  ?>
                  
              <tr>
                  <td></td>
                  <td></td><td></td><td></td><td></td><td></td>
                  
                  <td>Cost of <?php echo $val?></td>
                  <?php 
                  
				  $res = array_search($key,$non_billable);
				  if(is_numeric($res)) // non-billable
				  {
				  ?>
                  <td><?php echo num2alpha($active_column+3)+$rowstart+1+num2alpha($active_column+3)+$rowcount+$rowstart+1+$rowcount+$val;?></td>
                  <?php 
				  }else
				  {
				  ?>
                  <td><?php echo num2alpha($active_column+1)+$rowstart+1+num2alpha($active_column+1)+$rowcount+$rowstart+1+$rowcount+$val?></td>
                  <?php 
				  }
				  ?>
                  <td><?php echo $rowstart+1+$rowcount+$val+$rowstart+1+$rowcount?></td>
                  <td><?php echo $rowstart+1+$rowcount+$val?></td>
                  </tr><?php 
			  }
			  
			  ?>
              
              <tr>
              <td></td>
              <td></td><td></td><td></td><td></td><td></td>
              <td>Total</td>
              <td><?php echo $rowcount+19+$rowcount+18+sizeof($arr_employee_types)?></td>
              <td><?php echo $rowcount+19+$rowcount+18+sizeof($arr_employee_types)?></td>
              <td><?php echo $rowcount+19+$rowcount+18+sizeof($arr_employee_types)?></td>
              </tr>
      </tbody>
    </table>