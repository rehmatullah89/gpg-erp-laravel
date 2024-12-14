<?php
$arr_final_totals = array();
$arr_total_cells = array();
function set_totals($parent_id=0,$sub=0,$main_parent=0)
{
	if (!isset($GLOBALS['arr_final_totals']))
		$GLOBALS['arr_final_totals'] = array(); 
	if (!isset($GLOBALS['parent_arr']))
		$GLOBALS['parent_arr'] = array();
	if (!isset($GLOBALS['data_arr']))
		$GLOBALS['data_arr'] = array();
	if (!isset($GLOBALS['cell_end']))
	$GLOBALS['cell_end'] = array();
	if (!isset($GLOBALS['arr_total_cells']))
		$GLOBALS['arr_total_cells'] = array();
	$excluded = 0;
	$credit_sum = 0;
	$str = "";
	$str = "<tr>";
	@$GLOBALS['arr_final_totals'][$parent_id]['credit_total'] += @$GLOBALS['data_arr'][$parent_id]->credit_sum;
	@$GLOBALS['arr_final_totals'][$parent_id]['debit_total'] +=  @$GLOBALS['data_arr'][$parent_id]->debit_sum;
	@$GLOBALS['arr_final_totals'][$parent_id]['amount_total'] += @$GLOBALS['data_arr'][$parent_id]->amount_sum;
	@$GLOBALS['arr_total_cells'][] = @$GLOBALS['cell_end'];
	
	
	$str .= "<td></td><td colspan=2><strong>".@$GLOBALS['parent_arr'][0][$parent_id]["title"]."</strong></td>";
	$arr = @$GLOBALS['parent_arr'][$parent_id];
	if(is_array($arr))
		$str .="<td></td>";
	else
		$str .="<td><strong>".'$'.number_format($GLOBALS['parent_arr'][0][$parent_id]["amount_sum"],2)."</strong></td>";
	if($GLOBALS['parent_arr'][0][$parent_id]["excluded"]==1)
	{
		$str .= "<td>".'$'.number_format($GLOBALS['parent_arr'][0][$parent_id]["amount_sum"],2)."</td>";
		$excluded=1;
	}
	else
		$str .= "<td></td>";
	$str .= "<td></td></tr>";
	$GLOBALS['cell_end']++;
	
	$subtotal= 0;
	if(is_array($arr))
	{
		foreach($arr as $key => $val)
		{
			
				$str .= "<tr></td><td><td class=x1234></td><td>".@$GLOBALS['parent_arr'][$parent_id][$key]["title"]."</td><td>".'$'.number_format($GLOBALS['parent_arr'][$parent_id][$key]["amount_sum"],2)."</td>";
				if(isset($GLOBALS['parent_arr'][$parent_id][$key]["excluded"]) && $GLOBALS['parent_arr'][$parent_id][$key]["excluded"]==1 || $excluded==1)
				{
					$str .= "<td>".'$'.number_format(@$GLOBALS['parent_arr'][$parent_id][$key]["amount_sum"],2)."</td>";
				}
				else
					$str .= "<td></td>";
				$subtotal += @$GLOBALS['parent_arr'][$parent_id][$key]["amount_sum"];
				$str .= "<td></td></tr>";
				@$GLOBALS['arr_final_totals'][$parent_id]['credit_total'] += @$GLOBALS['data_arr'][$key]->credit_sum;
				@$GLOBALS['arr_final_totals'][$parent_id]['debit_total'] +=  @$GLOBALS['data_arr'][$key]->debit_sum;
				@$GLOBALS['arr_final_totals'][$parent_id]['amount_total'] += @$GLOBALS['data_arr'][$key]->amount_sum;
				if(isset($GLOBALS['parent_arr'][0][$parent_id]) or $val==1)
					@$GLOBALS['arr_final_totals'][$parent_id]['exclude_from_oh'] += @$GLOBALS['data_arr'][$key]->amount_sum;
					@$GLOBALS['cell_end']++;
		}
		$GLOBALS['arr_total_cells'][] = $GLOBALS['cell_end'];
		$GLOBALS['cell_end']++;
		
		$str .= "<tr></td><td><td colspan=2><strong>TOTAL - ".@$GLOBALS['parent_arr'][0][$parent_id]["title"]."</strong></td><td><strong>".'$'.number_format($subtotal,2)."</strong></td></tr>";
	}
	return $str;
}
?>
<table border=0 cellpadding=0 cellspacing=0  style='border-collapse: collapse;table-layout:fixed;'>
	<tr style='height:13.5pt;mso-width-source:userset;width:10px;'>
    	<td style='height:13.5pt;'>&nbsp;</td>
    	<td >&nbsp;</td>
    	<td style='text-align:center;' align="center" colspan="2" >Global Power Group, Inc</td>
  	</tr>
  	<tr style='height:13.5pt'>
	    <td style='height:13.5pt;'>&nbsp;</td>
	    <td style=''>&nbsp;</td>
	    <td style='text-align:center' align="center" colspan="2">Over Head Budgeting Report</td>
  	</tr>
 	<tr style='height:13.5pt'>
    	<td>&nbsp;</td>
    	<td  colspan="2" style='height:13.5pt;'>Start Date:&nbsp;&nbsp;&nbsp;<?php echo date('m/d/Y',strtotime($SDate))?></td>
  	</tr>
  	<tr style='height:13.5pt'>
  		<td>&nbsp;</td>
    	<td  colspan="2" style='height:13.5pt;'>End Date:&nbsp;&nbsp;&nbsp;<?php echo date('m/d/Y',strtotime($EDate))?></td>
  	</tr>
  	<tr style='height:13.5pt'>
   		<td>&nbsp;</td>
  	</tr>
    <tr>
        <td align="center"></td>                 
        <td align="center" colspan="2"><strong>Description</strong></td>
        <td align="center"><strong>Amount</strong></td>
        <td align="center"><strong>Reduction</strong></td>
        <td align="center"><strong>Adj OH</strong></td>
    </tr>
    <?php
		    $grand_debit = 0;
			$grand_credit = 0;
			$grand_amount = 0;
			$cell_start = 7;
			$GLOBALS['cell_end'] = $cell_start;
			$arr = $GLOBALS['parent_arr'][0];
			$str2 = "";
			foreach($arr as $key => $val) {
				$str2 .= set_totals($key);
			}
			echo $str2;
			$GLOBALS['cell_end'] = $GLOBALS['cell_end']-1;
			?> 
             	<tr height="40px">
               	<td width="16px"></td>
                <td bgcolor="#FFFFDF" align="right"></td>
                <?
                
				
				?>
                <td bgcolor="#FFFFDF" align="right"><strong style="margin-right:16px;font-size:16px">Grand Total</strong></td>
                <td bgcolor="#FFFFDF" align="right"><strong style="font-size:16px"><?php echo implode("+D",$arr_total_cells);?></strong></td>
                <td bgcolor="#FFC1C1" align="right"><strong style="font-size:16px"><?php echo $cell_start?>:E<?php echo $GLOBALS['cell_end']?></strong></td>
                <td bgcolor="#FFFFDF" align="right"><strong style="font-size:16px"><?php echo $cell_start?>:F<?php echo $GLOBALS['cell_end']?></strong></td>
                
               </tr>
             </table>