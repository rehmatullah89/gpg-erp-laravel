<?php
  header("Pragma: public");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
?>
<table border=0 cellpadding=0 cellspacing=0 width=1796 style='border-collapse:collapse;table-layout:fixed;width:826pt'>
  <tr   style='height:13.5pt'>
    <td   style='height:13.5pt;width:58pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:69pt'>&nbsp;</td>
    <td style='width:68pt;text-align:center' align="center">Glowbal Power Group, Inc</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
  </tr>
  <tr style='height:13.5pt'>
    <td style='height:13.5pt;width:58pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:69pt'>&nbsp;</td>
    <td style='width:68pt;text-align:center' align="center">GL Detail Report</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
    <td style='width:143pt'>&nbsp;</td>
  </tr>
  <tr style='height:13.5pt'>
    <td   colspan="3" style='height:13.5pt;width:100pt'>Start Date:&nbsp;&nbsp;&nbsp;<?php echo date('m/d/Y',strtotime($SDate))?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr   style='height:13.5pt'>
    <td   colspan="3" style='height:13.5pt;width:100pt'>End Date:&nbsp;&nbsp;&nbsp;<?php echo date('m/d/Y',strtotime($EDate))?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr   style='height:13.5pt'>
    <td style='height:13.5pt;width:58pt'>&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr   style='height:13.5pt'>
    <td>Date</td>
    <td>Modified On</td>
    <td>Last Modified By</td>
    <td style='height:13.5pt;width:58pt'>Type</td>
    <td>Num</td>
    <td>Name</td>
    <td>Source Name</td>
    <td>Memo</td>
    <td>Class</td>
    <td style='width:58pt'>Clr</td>
    <td>Debit</td>
    <td>Credit</td>
    <td>Amount</td>
  </tr>
  <?php   
  $totalMaterialCost = 0;  
  $debit_total  = 0;
  $credit_total = 0;
  $amount_total = 0;
  $start = 8;
  $end   = 7;
  foreach ($ohArr as $row=>$val) { 
	
?>
<tr>
	<td  style='height:13.5pt;width:100pt' ><strong><?php   
	if(is_numeric($row)) {
	  $tqry = DB::select(DB::raw("select concat(expense_gl_code,'-',description) as c_data from  gpg_expense_gl_code where id = '".$row."'")); 
    echo @$tqry[0]->c_data;
   } else {
	  echo $row;
	}
?></strong></td>
                 </tr>
<?php                    for ($i=0 ; $i < count($val[$groupBy]) ; $i++) {
					 
					 
					 
					 $debit        = $val['debit'][$i];
					 $credit       = $val['credit'][$i];
					 $amount       = $debit - $credit;
					 $debit_total  = $debit + $debit_total;
					 $credit_total = $credit + $credit_total;
					 $amount_total = $amount + $amount_total;
					  ?>
    <tr >
                  <td><?php echo date('m/d/Y',strtotime($val['date'][$i])) ?></td>
                 <td ><?php echo date('m/d/Y',strtotime($val['last_modified_on'][$i])) ?></td>
                  <td ><?php echo $val['modified_by'][$i] ?></td>

    			  <td ><?php echo $val['type'][$i] ?></td>
                  <td ><?php echo $val['num'][$i] ?></td>
                  <td ><?php echo $val['name'][$i] ?></td>
                  <td ><?php echo $val['source_name'][$i] ?></td>
                  <td ><?php echo $val['memo'][$i] ?></td>
                  <td ><?php echo $val['class'][$i] ?></td>
                  <td  style='width:58pt'><?php echo $val['clr'][$i] ?></td>
                  <td ><?php echo number_format($debit,2)?></td>
                  <td ><?php echo number_format($credit,2)?></td>
                  <td ><?php echo number_format($amount,2)?></td>
  </tr>
  <?php   
  	 $totalMaterialCost += @$costDataRow['amount'];
	 $end++;
  } ?>
    <tr style='height:12.75pt'>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td ></td>
    <td  ></td>
    <td  ></td>
    </tr>

<?php    
$start = $end + 3 ;
$end = $end + 2 ;
}
  ?>
</table>