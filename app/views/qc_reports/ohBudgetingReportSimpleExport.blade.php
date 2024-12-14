<table border=0 cellpadding=0 cellspacing=0  style='border-collapse: collapse;table-layout:fixed;'>
 <tr style='height:13.5pt;mso-width-source:userset;width:10px;'>
    <td style='height:13.5pt;'>&nbsp;</td>
    <td >&nbsp;</td>
    <td style='text-align:center;' align="center" colspan="2" >Glowbal Power Group, Inc</td>
  </tr>
  <tr style='height:13.5pt'>
    <td style='height:13.5pt;'>&nbsp;</td>
    <td style=''>&nbsp;</td>
    <td style='text-align:center' align="center" colspan="2">Over Head Budgeting Report</td>
  </tr>
  <tr style='height:13.5pt'>
    <td>&nbsp;</td>
    <td style='height:13.5pt;'>Start Date:&nbsp;&nbsp;&nbsp;<?php echo date('m/d/Y',strtotime($SDate))?></td>
  </tr>
  <tr style='height:13.5pt'>
  <td>&nbsp;</td>
    <td  style='height:13.5pt;'>End Date:&nbsp;&nbsp;&nbsp;<?php echo date('m/d/Y',strtotime($EDate))?></td>
  </tr>
  <tr style='height:13.5pt'>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
               <tr>
                 <td  align="center">&nbsp;</td>                 
                 <tdalign="center"><strong>Description</strong></td>
                 <tdalign="center"><strong>Debit</strong></td>
                 <tdalign="center"><strong>Credit</strong></td>
                 <tdalign="center"><strong>Amount</strong></td>
                 <tdalign="center"><strong>Reductions</strong></td>
                 <tdalign="center"><strong>Adj OH</strong></td>
               </tr>
              <?
			  
			  $grand_debit = 0;
			  $grand_credit = 0;
			  $grand_amount = 0;
			  $cell_start = 7;
			  $cell_end = $cell_start;
        foreach($arr_final_totals as $key => $val)
			  {
          $qryt = DB::select(DB::raw("SELECT CONCAT(expense_gl_code,' - ',description) as edetails FROM gpg_expense_gl_code WHERE id = '".$key."'"));
				  $title = @$qryt[0]->edetails;
			    $grand_debit += $val['debit_total'];
			    $grand_credit += $val['credit_total'];
			    $grand_amount += $val['amount_total'];
			  ?>

			   <tr height="20px">
               	<td width="16px"></td>
               	<td><?php echo $title?></td>
                <td align="right"><?php echo '$'.number_format(@$val['debit_total'],2)?></td>
                <td align="right"><?php echo '$'.number_format(@$val['credit_total'],2)?></td>
                <td align="right"><?php echo '$'.number_format(@$val['amount_total'],2)?></td>
                <td align="right"><?php echo '$'.number_format(@$val['exclude_from_oh'],2)?></td>
                <td></td>
               </tr>
        <? 
			   $cell_end++;
			  }
			$cell_end = $cell_end-1;
			?> 
             	<tr height="40px">
               	<td width="16px"></td>
                <td bgcolor="#FFFFDF" align="right"><strong style="margin-right:16px;font-size:16px">Grand Total</strong></td>
                <td bgcolor="#FFFFDF" align="right"><strong style="font-size:16px"></strong></td>
                <td bgcolor="#FFFFDF" align="right"><strong style="font-size:16px"></strong></td>
                <td bgcolor="#FFC1C1" align="right"><strong style="font-size:16px"></strong></td>
                <td bgcolor="#FFFFDF" align="right"><strong style="font-size:16px"></strong></td>
                <td bgcolor="#FFFFDF" align="right"><strong style="font-size:16px"></strong></td>
               </tr>
             </table>