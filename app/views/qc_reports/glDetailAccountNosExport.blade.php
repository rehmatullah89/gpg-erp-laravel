<?php

function num2alpha($n)
{
    for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
        $r = chr($n%26 + 0x41) . $r;
    return $r;
}
?>
<table style='border-collapse:collapse;table-layout:fixed;'>
  <col style='mso-width-source:userset;mso-width-alt:7985;width:200pt'>
  <col style='mso-width-source:userset;mso-width-alt:3985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:3985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:3985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:3985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:2985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:7985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:7985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:6985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:3985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:3985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:3985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:2985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:2985;width:100pt'>
  <col style='mso-width-source:userset;mso-width-alt:2985;width:100pt'>
  <tr style='mso-width-source:userset;mso-width-alt:3985;width:1500px;height:13.5pt'>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td style='text-align:center' align="center">Glowbal Power Group, Inc</td>
    <td>&nbsp;</td>
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
  <tr style='height:13.5pt'>
    <td style='height:13.5pt;'>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td  style='text-align:center' align="center">GL Detail With Line Item Account Number Report</td>
    <td>&nbsp;</td>
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
  <tr style='height:13.5pt'>
    <td colspan="3" style='height:13.5pt;'>Start Date:&nbsp;&nbsp;&nbsp;<?php echo date('m/d/Y',strtotime($SDate))?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
  <tr style='height:13.5pt'>
    <td colspan="3"  style='height:13.5pt;'>End Date:&nbsp;&nbsp;&nbsp;<?php echo date('m/d/Y',strtotime($EDate))?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
  <tr style='height:13.5pt'>
    <td style='height:13.5pt;'>&nbsp;</td>
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
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <?
    if($tagging > 0){
  		for($loop=0; $loop<$tags_col_counts;$loop++)
  		{
  			echo '<td >&nbsp;</td>';
  		}
	  }
	?>
  </tr>
  <tr style='height:13.5pt'>
  	<td>GL Acct</td>
    <td>Date</td>
    <td>Modified On</td>
    <td>Last Modified By</td>
    <td style='height:13.5pt;width:58pt'>Type</td>
    <td >Num</td>
    <td >Name</td>
    <td >Source Name</td>
    <td >Memo</td>
    <td >Class</td>
    <?
	if($tagging > 0)
		echo $tagging_cols_str;
	?>

    <td style='width:58pt'>Clr</td>
    <td>Debit</td>
    <td>Credit</td>
    <td>Amount</td>
  </tr>
  <?php
  $start = 7;
  $count = 1;
   foreach ($ohData as $key => $value123) {
    $row =  (array)$value123;
?>
    <tr height=32 style='mso-height-source:userset;height:24.0pt'>
    	<td><?php echo $row['code_desc']; ?></td>
        <td><?php echo date('m/d/Y',strtotime($row['date'])) ?></td>
        <td><?php echo $row['last_modified_on'] > 0 ? date('m/d/Y',strtotime($row['last_modified_on'])):"-"; ?></td>
        <td><?php echo $row['modified_by'] ?></td>
        <td height=32 style='height:24.0pt'><?php echo $row['type'];?></td>
        <td x:num><?php echo $row['num'];?></td>
        <td><?php echo $row['name'] ?></td>
        <td><?php echo $row['source_name'] ?></td>
        <td><?php echo $row['memo'] ?></td>
        <td><?php echo $row['class'] ?></td>
        <?
		foreach($total_tags as $k=> $v)
		{
			if(isset($tags_detail_data[$v][$row['gpg_expense_gl_code_id']]))
			{
				  $parent = $tags_detail_data[$v][$row['gpg_expense_gl_code_id']];
				  
				?>
                    <td><?php echo $tags_detail_data_names[key($parent)] ?></td>
                    <td><?php echo $tags_detail_data_names[$parent[key($parent)]] ?></td>
				<?
			}
			else{
				?>
                    <td></td>
                    <td></td>
				<?
			}
		}
		?>
        <td style='width:58pt'><?php echo $row['clr'] ?></td>
        <td><?php echo number_format($row['debit'],2)?></td>
        <td><?php echo number_format($row['credit'],2)?></td>
        <td><?php echo number_format($row['amount'],2)?></td>
  </tr>
<?php
$count++;
}
$end = $count - $start;
?>
<tr style='height:13.5pt'>
  	<td colspan="13"><?php echo 10+$tags_col_counts+1?></td>
     <?
	 if($tagging > 0)
	{
		for($loop=0; $loop<$tags_col_counts;$loop++)
		{
			echo '<td >&nbsp;</td>';
		}
	}
	?>
    <td><?php echo num2alpha(13+$tags_col_counts+0).$start;?>:<?php echo num2alpha(13+$tags_col_counts+1).($start+$count-2);?></td>
    <td><?php echo num2alpha(13+$tags_col_counts+1).$start;?>:<?php echo num2alpha(13+$tags_col_counts+2).($start+$count-2);?></td>
    <td><?php echo num2alpha(13+$tags_col_counts+2).$start;?>:<?php echo num2alpha(13+$tags_col_counts+3).($start+$count-2);?></td>
</tr>
</table>