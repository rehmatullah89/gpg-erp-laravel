 <section id="no-more-tables" >
              <?php
                $InvoiceSDate =  Input::get("InvoiceSDate");
                $InvoiceEDate =  Input::get("InvoiceEDate");
                $optJobStatus = Input::get("optJobStatus");
                $optCustomer = Input::get("optCustomer");
                if(Input::get("view")==''){
                  $view = "expand";
                } else {
                  $view = Input::get("view");
                }
                $contract_number =  Input::get("contract_number");
                $jobTypeTask =  Input::get("jobTypeTask");
                $currentSDate = date('m/d/Y',strtotime('01/01/2010'));
                $currentEDate = date('m/d/Y');
              ?>
              @if ($view == 'expand')
             <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
                <tr bgcolor="#F2F2F2">
                  <td height="30" rowspan="2" align="center" bgcolor="#EEEEEE"><strong>&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Quote Attached</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Contract Number</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Job Type</strong></td>
                  <td colspan="2" align="center" bgcolor="#EEEEEE"><strong> Labor Cost </strong></td>
                  <td colspan="2" align="center" bgcolor="#EEEEEE"><strong>Material Cost</strong></td>
                  <td colspan="2" align="center" bgcolor="#EEEEEE"><strong>Invoice Amount Net</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Actual Difference</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Job Invoice Status</strong></td>
                  <td colspan="2" align="center" bgcolor="#EEEEEE"><strong>Net Inv Amt  Not In Range</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Job Status</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Completed Date</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE"><strong>AR</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE"><strong>AP</strong></td>
               </tr>
               <tr bgcolor="#F2F2F2">
                 <td align="center" bgcolor="#EEEEEE"><strong>Esti.</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Actual</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Esti.</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Actual</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Esti.</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Actual</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Before</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>After<strong></td>
               </tr>
              </thead>
              <tbody class="cf">
              <?php 
                $preCustomer ="";
                $TotalHTML = "";
                $chk = 0;
                foreach ($query_data as $key => $row){
                  if($row['name']!= $preCustomer){
                        $est_labor_cost_total     =0;
                        $est_mat_cost_total       =0;
                        $est_inv_amt_total        =0;
                        $labor_cost_total         =0;
                        $material_cost            =0;
                        $invoice_amt_total        =0;
                        $difference               =0;
                        $NotInRangeInvB_total     =0;
                        $NotInRangeInvB_tax_total =0;
                        $NotInRangeInvA_total     =0;
                        $NotInRangeInvA_tax_total =0;
                        $ar_sum = 0;
                        $ap_sum = 0;
                        $chk ++;
                        echo $TotalHTML; 
              ?>
              <tr bgcolor="#FFFFFF">
                  <td height="25" colspan="2" align="left" bgcolor="#EEEEEE" style="font-size:12px;font-weight:bold;">{{$row['name']}}</td>
                  <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                  <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                  <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                  <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                  <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                  <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                  <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                  <td bgcolor="#EEEEEE">&nbsp;</td>
                  <td colspan="2" bgcolor="#EEEEEE">&nbsp;</td>
                  <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                  <td bgcolor="#EEEEEE">&nbsp;</td>
                  <td bgcolor="#EEEEEE">&nbsp;</td>
                  <td bgcolor="#EEEEEE">&nbsp;</td>
                  <td bgcolor="#EEEEEE">&nbsp;</td>
                  <td bgcolor="#EEEEEE">&nbsp;</td>
                  </tr>
                  <?php   
                  }?>
              <tr  bgcolor="#FFFFFF">
                  <td height="25" align="center">{{ HTML::link('job/'.(preg_match("/GPG/i",$row['job_num'])?'elec_job_list':(preg_match("/IG/i",$row['job_num']) ? 'grassivyJobList' : ((preg_match("/LK/i",$row['job_num']) ? 'specialProjectJobList' : 'service_job_list')))), $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$row['id'],'j_num'=>$row['job_num']))}}</td>
                  <td align="center">
                  <?php $attachQuote = @$cusData[$row['id']][$row['job_num']]['quote_attached'];
                      $attachQuoteType = substr($attachQuote,0,1) ;
                      if($row['GPG_job_type_id']=='4') { ?>
                        {{ HTML::link('job/field_service_work_list', isset($attachQuote)?$attachQuote:"-" , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                      <?php  } else {?>
                      {{ HTML::link('quote/'.(preg_match("/GPG/i",$row['job_num'])?'elec_quote_list':(preg_match("/IG/i",$row['job_num']) ? 'grassivy_quote_list' : ((preg_match("/LK/i",$row['job_num']) ? 'specialproject_quote_list':'')))),isset($attachQuote)?$attachQuote:"-", array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$row['id'],'j_num'=>$row['job_num']))}}
                      <?php  } ?>
                  </td>
                  <td align="center"><?php echo ($row["contract_number"] != "") ?  $row["contract_number"] : "-";?></td>
                  <td align="center"><?php
                      if (preg_match("/GPG/i",$row['job_num'])) {
                        echo @$elecJobTypeArray[$row['elec_job_type']];
                      }elseif(preg_match("/SH/i",$row['job_num'])){
                        echo "-";
                      }else{
                        echo ($row['task'] != "") ? wordwrap($row['task'],25, "<br>",1) : "-"; 
                      }?></td>
                  <?php $est_labor_cost_total += @$cusData[$row['id']][$row['job_num']]['est_labor_cost'];
                        $est_mat_cost_total   += @$cusData[$row['id']][$row['job_num']]['est_mat_cost'];
                        $est_inv_amt_total    += @$cusData[$row['id']][$row['job_num']]['est_inv_amt'];
                        $labor_cost_total     += @$cusData[$row['id']][$row['job_num']]['labor_cost'];
                        $material_cost        += @$cusData[$row['id']][$row['job_num']]['material_cost'];
                        $invoice_amt_total    += (@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax']);
                        $difference       += (@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax'])-(@$cusData[$row['id']][$row['job_num']]['labor_cost']+@$cusData[$row['id']][$row['job_num']]['material_cost']); 
                        $ar_sum         += @$arr_ar_ap_report[$row['job_num']]['AR'];
                        $ap_sum         += @$arr_ar_ap_report[$row['job_num']]['AP'];
                  ?>
                  <td align="right" bgcolor="#FFFFFF">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['est_labor_cost'],2)}}</td>
                  <td align="right" bgcolor="#FFFFFF">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['labor_cost'],2)}}</td>
                  <td align="right" bgcolor="#FFFFCC">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['est_mat_cost'],2)}}</td>
                  <td align="right" bgcolor="#FFFFCC">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['material_cost'],2)}}</td>
                  <td align="right" bgcolor="#FFFFCC">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['est_inv_amt'],2)}}</td>
                  <td align="right" bgcolor="#FFFFCC">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax'],2)}}</td>
                  <?php  $difference += @$row['invoice_amount'][$i]-(@$row['labor_cost'][$i]+@$row['material_cost'][$i]);?>
                  <td align="right" bgcolor="#ffc1c1">{{'$'.number_format((@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax'])-(@$cusData[$row['id']][$row['job_num']]['labor_cost']+@$cusData[$row['id']][$row['job_num']]['material_cost']),2)}}</td>
                  <td align="center">
                  <?php   if($row['invoices']==0){
                          echo '<font color="#c10000">Not Invoiced</font>';
                      }else
                        echo 'Invoiced'; 
                  ?>
                  </td>
                  <?php   $NotInRangeInvB_total     += $row['sum_inv_amount_before'];
                      $NotInRangeInvA_total     += $row['sum_inv_amount_after'];
                  ?>
                  <td align="right"><?php 
                  echo '<font color="#c10000">'.'$'.number_format($row['sum_inv_amount_before'],2).'</font>';?>
                </a></td>
                <td align="right">{{'<font color="#c10000">'.'$'.number_format($row['sum_inv_amount_after'],2).'</font>'}}</a></td>                  
                <td align="center"><strong>{{($row['complete']==1?"Completed":"-")}}</strong></td>
                <td align="center">{{($row['date_completion']!=''?date('m/d/Y',strtotime($row['date_completion'])):"-")}}</td>
                <td align="right">{{@$arr_ar_ap_report[$row['job_num']]['AR']?'$'.number_format(@$arr_ar_ap_report[$row['job_num']]['AR'],2):""}}</td>
                <td align="right">{{@$arr_ar_ap_report[$row['job_num']]['AP']?'$'.number_format(@$arr_ar_ap_report[$row['job_num']]['AP'],2):""}}</td>
            </tr><?php  
                $TotalHTML =    '<tr  bgcolor>
                  <td height="25" colspan="4" align="right">TOTALS:&nbsp;&nbsp;</td>
                  <td align="right" style="font-weight:bold">'. '$'.number_format($est_labor_cost_total,2).'</td>
                  <td align="right" style="font-weight:bold">'. '$'.number_format($labor_cost_total,2).'</td>
                  <td align="right" bgcolor="#FFFFCC" style="font-weight:bold">'. '$'.number_format($est_mat_cost_total,2).'</td>
                  <td align="right" bgcolor="#FFFFCC" style="font-weight:bold">'. '$'.number_format($material_cost,2).'</td>
                  <td align="right" bgcolor="#FFFFCC" style="font-weight:bold">'. '$'.number_format($est_inv_amt_total,2).'</td>
                  <td align="right" bgcolor="#FFFFCC" style="font-weight:bold">'. '$'.number_format($invoice_amt_total,2).'</td>
                  <td align="right" bgcolor="#ffc1c1" style="font-weight:bold"></td>
                  <td align="right" >&nbsp;</td>
                  <td align="right" style="font-weight:bold">'. '$'.number_format((($NotInRangeInvB_total)!=0)?($NotInRangeInvB_total):0.00 ,2).'</td>
                  <td align="right" style="font-weight:bold">'. '$'.number_format((($NotInRangeInvA_total)!=0)?($NotInRangeInvA_total):0.00 ,2).'</td>
                  <td align="right" >&nbsp;</td>
                  <td align="right" >&nbsp;</td>
                  <td align="right"  style="font-weight:bold">'. '$'.number_format((($ar_sum)!=0)?($ar_sum):0.00 ,2).'</td>
                  <td align="right"  style="font-weight:bold">'. '$'.number_format((($ap_sum)!=0)?($ap_sum):0.00 ,2).'</td>
                  </tr> ';
                  $preCustomer = $row['name']; 
                } 
                echo $TotalHTML;
            ?> 
              </tbody>
            </table>
            @elseif ($view == 'colapse')
             <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
                <tr bgcolor="#F2F2F2">
                  <th height="30" rowspan="2" align="center" bgcolor="#EEEEEE"><strong>&nbsp;</strong></th>
                  <th rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Quote Attached</strong></th>
                  <th colspan="2" align="center" bgcolor="#EEEEEE"><strong> Labor Cost </strong></th>
                  <th colspan="2" align="center" bgcolor="#EEEEEE"><strong>Material Cost</strong></th>
                  <th colspan="2" align="center" bgcolor="#EEEEEE"><strong>Invoice Amt. Net</strong></th>
                  <th rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Actual Difference</strong></th>
                  <th rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Job Invoice Status</strong></th>
                  <th colspan="2" align="center" bgcolor="#EEEEEE"><strong>Net Inv Amt  Not In Range</strong></th>
                  <th rowspan="2" align="center" bgcolor="#EEEEEE"><strong>Job Status</strong></th>
                </tr>
                <tr bgcolor="#F2F2F2">
                  <th align="center" bgcolor="#EEEEEE"><strong>Esti.</strong></th>
                  <th align="center" bgcolor="#EEEEEE"><strong>Actual</strong></th>
                  <th align="center" bgcolor="#EEEEEE"><strong>Esti.</strong></th>
                  <th align="center" bgcolor="#EEEEEE"><strong>Actual</strong></th>
                  <th align="center" bgcolor="#EEEEEE"><strong>Esti.</strong></th>
                  <th align="center" bgcolor="#EEEEEE"><strong>Actual</strong></th>
                  <th align="center" bgcolor="#EEEEEE"><strong>Before</strong></th>
                  <th align="center" bgcolor="#EEEEEE"><strong>After</strong></th>
                </tr>
              </thead>
              <tbody>
              <?php  
                $chk =0;
                $NotInRangeInv =0;
                $NotInRangeInvTax =0;
                $preCustomer ="";
                $TotalHTML = "";
                foreach ($query_data as $key => $row){
                  if($row['name']!= $preCustomer){
                    $est_labor_cost_total     =0;
                    $est_mat_cost_total       =0;
                    $est_inv_amt_total        =0;
                    $labor_cost_total         =0;
                    $material_cost            =0;
                    $invoice_amt_total        =0;
                    $difference               =0;
                    $NotInRangeInvB_total     =0;
                    $NotInRangeInvB_tax_total =0;
                    $NotInRangeInvA_total     =0;
                    $NotInRangeInvA_tax_total =0;
                    $chk ++;
                    echo $TotalHTML
              ?>    
              <tr bgcolor="#FFFFFF">
                <td height="25" colspan="2" align="left" bgcolor="#EEEEEE" style="font-size:12px;font-weight:bold;">{{$row['name']}}</td>
                <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
                <td colspan="2" bgcolor="#EEEEEE">&nbsp;</td>
                <td height="25" bgcolor="#EEEEEE">&nbsp;</td>
              </tr>
              <?php  }
                $NotInRangeInv        += @$invoiceRow['invoice_amount_total'];
                $NotInRangeInvTax     += @$invoiceRow['tax_amount_total'];
                $est_labor_cost_total += @$cusData[$row['id']][$row['job_num']]['est_labor_cost'];
                $est_mat_cost_total   += @$cusData[$row['id']][$row['job_num']]['est_mat_cost'];
                $est_inv_amt_total    += @$cusData[$row['id']][$row['job_num']]['est_inv_amt'];
                $labor_cost_total     += @$cusData[$row['id']][$row['job_num']]['labor_cost'];
                $material_cost        += @$cusData[$row['id']][$row['job_num']]['material_cost'];
                $invoice_amt_total    += (@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax']);
                $difference           += (@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax'])-(@$cusData[$row['id']][$row['job_num']]['labor_cost']+@$cusData[$row['id']][$row['job_num']]['material_cost']); 
                $NotInRangeInvB    = 0;
                $NotInRangeInvTaxB = 0;
                $NotInRangeInvA    = 0;
                $NotInRangeInvTaxA = 0;
                $NotInRangeInvB_total     += $row['sum_inv_amount_before'];
                $NotInRangeInvA_total     += $row['sum_inv_amount_after'];
                $TotalHTML =    '<tr  bgcolor>
                    <td height="25" colspan="2" align="right">TOTALS:&nbsp;&nbsp;</td>
                    <td align="right" style="font-weight:bold">'. '$'.number_format($est_labor_cost_total,2).'</td>
                    <td align="right" style="font-weight:bold">'. '$'.number_format($labor_cost_total,2).'</td>
                    <td align="right" bgcolor="#FFFFCC" style="font-weight:bold">'. '$'.number_format($est_mat_cost_total,2).'</td>
                    <td align="right" bgcolor="#FFFFCC" style="font-weight:bold">'. '$'.number_format($material_cost,2).'</td>
                    <td align="right" bgcolor="#FFFFCC" style="font-weight:bold">'. '$'.number_format($est_inv_amt_total,2).'</td>
                    <td align="right" bgcolor="#FFFFCC" style="font-weight:bold">'. '$'.number_format($invoice_amt_total,2).'</td>
                    <td align="left" bgcolor="#ffc1c1" style="font-weight:bold">
                    <td align="left" >&nbsp;</td>
                    <td align="right" style="font-weight:bold">'. '$'.number_format((($NotInRangeInvB_total)!=0)?($NotInRangeInvB_total):0.00 ,2).'</td>
                    <td align="right" style="font-weight:bold">'. '$'.number_format((($NotInRangeInvA_total)!=0)?($NotInRangeInvA_total):0.00 ,2).'</td>
                    <td align="left" >&nbsp;</td>
                  </tr> ';
                  $preCustomer = $row['name']; 
                } 
                echo $TotalHTML;
                ?> 
              </tbody>
            </table>
             @elseif ($view == 'jobView')
             <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
                <tr bgcolor="#F2F2F2">
                  <td height="30" rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>Job Number</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>Customer</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>Quote Attached</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>Contract Number</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Job Type&nbsp;</strong></td>
                  <td colspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Labor Cost&nbsp;</strong></td>
                  <td colspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Material Cost&nbsp;</strong></td>
                  <td colspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Invoice Amount Net&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Actual Difference&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Job Invoice Status&nbsp;</strong></td>
                  <td colspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Net Inv Amt  Not In Range&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Job Status&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Completed Date&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Contract Amount&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Cost at Completion&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Profit at Completion&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Cost to Date&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;% Compl&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Profit to Date&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Earned Amount to Date&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Billed to Date&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Costs in Excess&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Billings in Excess&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Estimated Cost to Complete&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Contract Balance&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;Labor&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;AR&nbsp;</strong></td>
                  <td rowspan="2" align="center" bgcolor="#EEEEEE" ><strong>&nbsp;AP&nbsp;</strong></td>
                </tr>
                <tr bgcolor="#F2F2F2">
                 <td align="center" bgcolor="#EEEEEE"><strong>Esti.</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Actual</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Esti.</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Actual</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Esti.</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Actual</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>Before</strong></td>
                 <td align="center" bgcolor="#EEEEEE"><strong>After<strong></td>
               </tr>
              </thead>
              <tbody>
                <?php
                  $est_labor_cost_total     =0;
                  $est_mat_cost_total       =0;
                  $est_inv_amt_total        =0;
                  $labor_cost_total         =0;
                  $material_cost            =0;
                  $invoice_amt_total        =0;
                  $difference               =0;
                  $NotInRangeInvB_total     =0;
                  $NotInRangeInvB_tax_total =0;
                  $NotInRangeInvA_total     =0;
                  $NotInRangeInvA_tax_total =0;
                  $actual_differenece_total =0;
                  $ar_total         = 0;
                  $ap_total         = 0;
                  foreach ($query_data as $key => $row){
                ?>
                <tr  bgcolor="#FFFFFF">
                  <td height="25" align="center">{{ HTML::link('job/'.(preg_match("/GPG/i",$row['job_num'])?'elec_job_list':(preg_match("/IG/i",$row['job_num']) ? 'grassivyJobList' : ((preg_match("/LK/i",$row['job_num']) ? 'specialProjectJobList' : 'service_job_list')))), $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$row['id'],'j_num'=>$row['job_num']))}}</td>
                  <td align="center"><?php echo $row['name'] ?></td>
                  <td align="center"><?php  
                  $attachQuote = @$cusData[$row['id']][$row['job_num']]['quote_attached'];
                  $attachQuoteType = substr($attachQuote,0,1) ;
                  if($row['GPG_job_type_id']=='4') { 
                    if(substr($attachQuote,0,2) == 'HS'){?>
                      {{ HTML::link('quote/shop_work_quote_list', isset($attachQuote)?$attachQuote:"-" , array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$row['id'],'j_num'=>$row['job_num']))}}
                    <?php  }else{?>
                    {{ HTML::link('job/field_service_work_list', isset($attachQuote)?$attachQuote:"-" , array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$row['id'],'j_num'=>$row['job_num']))}}  
                    <?php  }
                  } else {?> 
                     {{ HTML::link('quote/'.(preg_match("/GPG/i",$row['job_num'])?'elec_quote_list':(preg_match("/IG/i",$row['job_num']) ? 'grassivy_quote_list' : ((preg_match("/LK/i",$row['job_num']) ? 'specialproject_quote_list':'')))),isset($attachQuote)?$attachQuote:"-", array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$row['id'],'j_num'=>$row['job_num']))}}
                  <?php  } ?>
                  </td>
                  <td align="center">{{($row["contract_number"] != "") ?  $row["contract_number"] : "-"}}</td>
                  <td align="center"><?php 
                  if (preg_match("/GPG/i",$row['job_num'])) {
                    echo @$elecJobTypeArray[$row['elec_job_type']];
                  }
                  elseif(preg_match("/SH/i",$row['job_num'])){
                    echo "-";
                  }else{
                    echo ($row['task'] != "") ? wordwrap($row['task'],25, "<br>",1) : "-"; 
                  }?></td><?php 
                  $est_labor_cost_total += @$cusData[$row['id']][$row['job_num']]['est_labor_cost'];
                  $est_mat_cost_total   += @$cusData[$row['id']][$row['job_num']]['est_mat_cost'];
                  $est_inv_amt_total    += @$cusData[$row['id']][$row['job_num']]['est_inv_amt'];
                  $labor_cost_total     += @$cusData[$row['id']][$row['job_num']]['labor_cost'];
                  $material_cost        += @$cusData[$row['id']][$row['job_num']]['material_cost'];
                  $invoice_amt_total    += (@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax']);
                  $difference           += (@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax'])-(@$cusData[$row['id']][$row['job_num']]['labor_cost']+@$cusData[$row['id']][$row['job_num']]['material_cost']); 
                  ?>
                  <td align="right" bgcolor="#FFFFFF">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['est_labor_cost'],2)}}</td>
                  <td align="right" bgcolor="#FFFFFF">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['labor_cost'],2)}}</td>
                  <td align="right" bgcolor="#FFFFCC">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['est_mat_cost'],2)}}</td>
                  <td align="right" bgcolor="#FFFFCC">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['material_cost'],2)}}</td>
                  <td align="right" bgcolor="#FFFFCC">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['est_inv_amt'],2)}}</td>
                  <td align="right" bgcolor="#FFFFCC">{{'$'.number_format(@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax'],2)}}</td>
                  <?php  $difference += @$row['invoice_amount'][$i]-(@$row['labor_cost'][$i]+@$row['material_cost'][$i]);?>
                  <td align="right" bgcolor="#ffc1c1"><?php  echo '$'.number_format((@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax'])-(@$cusData[$row['id']][$row['job_num']]['labor_cost']+@$cusData[$row['id']][$row['job_num']]['material_cost']),2);
                  $actual_differenece_total += (@$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax'])-(@$cusData[$row['id']][$row['job_num']]['labor_cost']+@$cusData[$row['id']][$row['job_num']]['material_cost']);
                  ?></td>
                  <td align="center"><?php  
                  if($row['invoices']==0){
                    echo '<font color="#c10000">Not Invoiced</font>';
                  }
                  else
                    echo 'Invoiced'; 
                  ?>
                  </td><?php 
                  $NotInRangeInvB    = 0;
                  $NotInRangeInvTaxB = 0;
                  $NotInRangeInvA    = 0;
                  $NotInRangeInvTaxA = 0;            
                  $NotInRangeInvB_total     += $row['sum_inv_amount_before'];
                  $NotInRangeInvA_total     += $row['sum_inv_amount_after'];
                  ?>
                  <td align="right">{{'<font color="#c10000">'.'$'.number_format($row['sum_inv_amount_before'],2)}}</td>
                  <td align="right">{{'<font color="#c10000">'.'$'.number_format($row['sum_inv_amount_after'],2)}}</td>                  
                  <td align="center"><strong>{{($row['complete']==1?"Completed":"-")}}</strong></td>
                  <td align="center"><?php echo ($row['date_completion']!=''?date('m/d/Y',strtotime($row['date_completion'])):"-")?></td>
                  <?php  if( ($row['invoices']> 0) && ($row['complete']==1)) { ?>
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <td align="center">&nbsp;-</td> 
                  <?php  } else {?>
                  <td align="center"><?php 
                  $ContractAmount = @$cusData[$row['id']][$row['job_num']]['est_inv_amt'];
                  echo '$'.number_format($ContractAmount,2); ?></td>
                  <td align="center"><?php  
                  $CostAtCompletion = @$cusData[$row['id']][$row['job_num']]['est_labor_cost'] + @$cusData[$row['id']][$row['job_num']]['est_mat_cost'];
                  echo  '$'.number_format($CostAtCompletion,2);?></td>
                  <td align="center"><?php 
                  $ProfitAtCompletion = (@$cusData[$row['id']][$row['job_num']]['est_inv_amt']) - (@$cusData[$row['id']][$row['job_num']]['est_labor_cost'] + @$cusData[$row['id']][$row['job_num']]['est_mat_cost']);
                  echo '$'.number_format($ProfitAtCompletion,2);?></td>
                  <td align="center"><?php  
                  $CostToDate = @$cusData[$row['id']][$row['job_num']]['labor_cost'] + @$cusData[$row['id']][$row['job_num']]['material_cost'];
                  echo '$'.number_format($CostToDate,2)  ?></td>
                  <td align="center"><?php  
                  $Compl = @(($CostToDate/$CostAtCompletion)*100); 
                  echo round($Compl,0).'%'  ?></td>
                  <td align="center"><?php 
                  $ProfitToDate =  $ProfitAtCompletion * $Compl ;
                  echo  '$'.number_format($ProfitToDate,2)  ?></td> 
                  <td align="center"><?php 
                  $EarnedAmountToDate =  $CostToDate +  $ProfitToDate;
                  echo  '$'.number_format($EarnedAmountToDate,2)  ?></td> 
                  <td align="center"><?php 
                  $BilledToDate = @$cusData[$row['id']][$row['job_num']]['invoice_amount'] - @$cusData[$row['id']][$row['job_num']]['InvTax'];
                  echo  '$'.number_format($BilledToDate,2)  ?></td> 
                  <td align="center"><strong><?php 
                  $CostsInExcess = $EarnedAmountToDate - $BilledToDate ;
                  echo  ($CostsInExcess>0)?'$'.number_format($CostsInExcess,2):'-'  ?></strong></td> 
                  <td align="center"><?php 
                  $BillingsInExcess = $BilledToDate - $EarnedAmountToDate ;
                  echo ($BillingsInExcess>0)?'$'.number_format($BillingsInExcess,2):'-'  ?></td> 
                  <td align="center"><?php 
                  $EstimatedCostsToComplete = $CostAtCompletion - $CostToDate ;
                  echo  '$'.number_format($EstimatedCostsToComplete,2)  ?></td> 
                  <td align="center"><?php 
                  $ContractBalance = $ContractAmount - $BilledToDate;
                  echo  '$'.number_format($ContractBalance,2)  ?></td> 
                  <td align="center"><?php 
                  $Labor = @$cusData[$row['id']][$row['job_num']]['labor_cost'];
                  echo  '$'.number_format($Labor,2)  ?></td> 
                  <td align="center"><?php 
                  $ar_total += @$arr_ar_ap_report[$row['job_num']]['AR'];
                  echo  '$'.number_format(@$arr_ar_ap_report[$row['job_num']]['AR'],2)  ?></td>
                  <td align="center"><?php 
                  $ap_total += @$arr_ar_ap_report[$row['job_num']]['AP'];
                  echo  '$'.number_format(@$arr_ar_ap_report[$row['job_num']]['AP'],2)  ?></td> 
                  <?php  }?>
                </tr>     
            <?php  } ?> 
            </tbody>
          </table>
            @endif