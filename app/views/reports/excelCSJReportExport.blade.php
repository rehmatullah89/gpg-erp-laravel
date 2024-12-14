<table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>#id</th>
                  <th>Customer</th>
                  <th>Contract Number </th>
                  <th>Job Number  </th>
                  <th>Contract Amount</th>
                  <th>Invoice Amount </th>
                  <th>Invoice Number</th>
                  <th>Invoice Date </th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php 
                  $colcount=0;
                  $preDate='preDate';
                  foreach ($query_data as $key => $jobComp_row){   
                  if($preDate!=$jobComp_row['date_completion'])
                  {
                  $colcount++;
                   ?>
                   <tr>
                        <td width="3%" height="30" bgcolor="#EEEEEE" colspan="8"><div align="left"><strong>Completion Date: <?php echo ($jobComp_row['date_completion']!=''?date('m/d/Y',strtotime($jobComp_row['date_completion'])):"-")?></strong></div></td>
                     </tr>
                   <?php } ?> 
                      <tr  bgcolor="<?php echo ($colcount%2==0?"#FFFFCC":"#FFFFFF"); ?>">
                        <td align="center" ><strong>&nbsp;<?php echo $jobComp_row['id'] ?></strong></td>
                        <td height="30" align="center" >&nbsp;<?php echo $jobComp_row['customer_name']; ?></td>
                        <td height="30" align="center" >&nbsp;<?php echo $jobComp_row['contract_number'] ?></td>
                        <td height="30" align="center" >&nbsp;<?php echo $jobComp_row['job_num'] ?></td>
                        <td align="center" >&nbsp;<?php echo '$'.number_format($jobComp_row['contract_amount'],2)?></td>
                        <td align="center" onClick="callInvoice('<?php echo $jobComp_row['id']; ?>','invoiceAmountDiv','<?php echo $jobComp_row['job_num'] ?>');  this.className='highlight'; return false;" style="cursor:pointer;">&nbsp;<?php
                        $invoiceData = explode("#~#",$jobComp_row['invoice_data']);
                        echo '$'.number_format(@$invoiceData[1],2);?></td>
                        <td align="center" onClick="callInvoice('<?php echo $jobComp_row['id']; ?>','invoiceAmountDiv','<?php echo $jobComp_row['job_num'] ?>');  this.className='highlight'; return false;" style="cursor:pointer;">&nbsp;<?php echo (@$invoiceData[3]>1?"Multiple":$invoiceData[0])?></td>
                        <td align="center" onClick="callInvoice('<?php echo $jobComp_row['id']; ?>','invoiceAmountDiv','<?php echo $jobComp_row['job_num'] ?>');  this.className='highlight'; return false;" style="cursor:pointer;">&nbsp;<?php echo (@$invoiceData[3]>1?"Multiple":(@$invoiceData[2]!=""?date('m/d/Y',strtotime(@$invoiceData[2])):"-"));?></td>
                      </tr>  
                    <?php 
                    $preDate=$jobComp_row['date_completion'];
                  }
                  ?>   
               </tbody>
              </table>
            </section><br/>
            <h4>GRAND TOTALS</h4>
              <section id="no-more-tables" >
              <table class="table table-bordered table-striped table-condensed cf" >
                <thead class="cf">
                  <tr>
                    <th>Contract Amount</th>
                    <th>Invoice Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{'$'.number_format($invoiceTotals["contract_amount_total"],2)}}</td>
                    <td>{{'$'.number_format($invoiceTotals["invoice_total"],2)}}</td>
                  </tr>
                </tbody>
              </table>