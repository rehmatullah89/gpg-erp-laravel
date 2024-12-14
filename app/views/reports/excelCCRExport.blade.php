<?php
$SYear = Input::get('SYear');
if(empty($SYear))
  $SYear = date("Y",strtotime("-1 year"));
$EYear = Input::get('EYear');
if(empty($EYear))
  $EYear = date('Y');
?>
<table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                        <th colspan="3" style="text-align:center;">[Againest Invoice Dates] Year {{$SYear}}</th>
                                        <th colspan="3" style="text-align:center;">[Againest Created Dates] Year {{$EYear}}</th>
                                        <th colspan="2" style="text-align:center;"></th>
                                      </tr>
                                      <tr>
                                          <th style="text-align:center;">Contract Number</th>
                                          <th style="text-align:center;">Job Number</th>
                                          <th style="text-align:center;">Invoice Amount</th>
                                          <th style="text-align:center;">Contract Number</th>
                                          <th style="text-align:center;">Job Number</th>
                                          <th style="text-align:center;">Invoice Amount</th>
                                          <th style="text-align:center;">Customer</th>
                                          <th style="text-align:center;">Total</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        <?php 
                                        $colcount=0;
                                        foreach ($yearFirstResult as $key => $yearFirstRow){ 
                                        ?>
                                          <tr  bgcolor="<?php echo ($colcount%2==0?"#FFFFCC":"#FFFFFF"); ?>">
                                            <td height="25" align="center" >&nbsp;<strong><?php echo $yearFirstRow['contract_number']?></strong></td>
                                        <td height="25" align="center" >&nbsp;<?php echo $yearFirstRow['job_num']?></td>
                                            <td height="25"  align="center" >&nbsp;<?php echo '$'.number_format($yearFirstRow['invoice_amt'],2)?></td>
                                         <?php 
                                        if(trim($yearFirstRow['contract_number']) == trim(@$yearSecondArray[$yearFirstRow['contract_number']]['contractNum']))
                                        { 
                                        ?>
                                        <td height="25" align="center" >&nbsp;<strong><?php echo @$yearSecondArray[$yearFirstRow['contract_number']]['contractNum']?></strong></td>
                                        <td height="25" align="center" >&nbsp;<?php echo @$yearSecondArray[$yearFirstRow['contract_number']]['jobNum']?></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format(@$yearSecondArray[$yearFirstRow['contract_number']]['invoiceAmt'],2)?></td>
                                        <td height="25" align="center" >&nbsp;<?php echo @$yearSecondArray[$yearFirstRow['contract_number']]['customer']?></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format($yearFirstRow['invoice_amt']+$yearSecondArray[$yearFirstRow['contract_number']]['invoiceAmt'],2)?></td>
                                        </tr> 
                                        <?php 
                                        unset($yearSecondArray[$yearFirstRow['contract_number']]);
                                        } else {?>
                                        
                                        <td height="25" align="center" >&nbsp;</td>
                                        <td height="25" align="center" >&nbsp;</td>
                                            <td height="25" align="center" >&nbsp;</td>
                                        <td height="25" align="center" >&nbsp;<?php echo $yearFirstRow['customer']?></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format($yearFirstRow['invoice_amt'],2)?></td>
                                        </tr>
                                        <?php }
                                        $colcount++;
                                        }
                                        if (is_array($yearSecondArray)) {
                                        foreach($yearSecondArray as $key=>$Value) {
                                        ?>
                                        <tr  bgcolor="<?php echo ($colcount%2==0?"#FFFFCC":"#FFFFFF"); ?>">
                                            <td height="25" align="center" >&nbsp;</td>
                                        <td height="25" align="center" >&nbsp;</td>
                                            <td height="25"  align="center" >&nbsp;</td>
                                        <td height="25" align="center" >&nbsp;<strong><?php echo $key;?></strong></td>
                                        <td height="25" align="center" >&nbsp;<strong><?php echo $Value['jobNum'];?></strong></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format($Value['invoiceAmt'],2);?></td>
                                        <td height="25" align="center" >&nbsp;<?php echo $Value['customer']?></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format($Value['invoiceAmt'],2);?></td>
                                        </tr>
                                        <?php $colcount++; 
                                        } 
                                        } 
                                        ?>
                                      </tbody>
                                  </table>