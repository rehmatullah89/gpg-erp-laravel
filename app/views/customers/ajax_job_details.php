<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
-->
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#CCCCCC"><table width="100%" border="1" align="center" cellspacing="1" >
      <tbody>
        <tr >
          <td height="30" colspan="6" nowrap="nowrap" bgcolor="#F7F7F7" class="schrift_ueberschrift "  >&nbsp;Details: <strong><?=$urow[0]->name ?></strong> <?=$rangeStr?></td>
        </tr>
        
        <tr>
          <td height="20" bgcolor="#F7F7F7" class="schrift_ueberschrift"  >&nbsp;Job Num</td>
          <td bgcolor="#F7F7F7" class="schrift_ueberschrift"  >&nbsp;Invoice Date</td>
          <td bgcolor="#F7F7F7" class="schrift_ueberschrift"  >&nbsp;Invoicev #</td>
          <td bgcolor="#F7F7F7" class="schrift_ueberschrift"  >&nbsp;Invoice Amount</td>
          <td bgcolor="#F7F7F7" class="schrift_ueberschrift"  >&nbsp;Tax</td>
          <td bgcolor="#F7F7F7" class="schrift_ueberschrift"  >&nbsp;Inv Amt Net</td>
        </tr>
        <? foreach ($jobRs as $key => $jobRow) { ?>
        <tr>
          <td bgcolor="#FFFFFF" class="schrift_ueberschrift"  >&nbsp;<?=$jobRow->job_num ?></td>
          <td bgcolor="#FFFFFF" class="schrift_ueberschrift">&nbsp;<?
            //$invoiceData = split("#~#",$jobRow['invoice_data']);
            $invoiceData = explode("#~#",$jobRow->invoice_data);
            echo ($invoiceData[4]>1?"Multiple":($invoiceData[2]!=""?date($_DateFormat,strtotime($invoiceData[2])):"-")); ?></td>
          <td bgcolor="#FFFFFF" class="schrift_ueberschrift">&nbsp;<?=($invoiceData[4]>1?"Multiple":$invoiceData[0])?></td>
          <td bgcolor="#FFFFFF" class="schrift_ueberschrift">&nbsp;<? echo $_DefaultCurrency.number_format($invoiceData[1],2)  ?></td>
          <td bgcolor="#FFFFFF" class="schrift_ueberschrift">&nbsp;<? echo $_DefaultCurrency.number_format($invoiceData[3],2)  ?></td>
          <td bgcolor="#FFFFFF" class="schrift_ueberschrift">&nbsp;<?
				  $invAmt = ($invoiceData[1]!=0?$invoiceData[1] - $invoiceData[3]:0);
				  echo $_DefaultCurrency.number_format(($invAmt>0?$invAmt:0),2);  ?></td>
        </tr>
        <? } ?>
      </tbody>
    </table></td>
  </tr>
</table>