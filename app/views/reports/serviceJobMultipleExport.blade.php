<?php
      $checks = array();
      $SDate2 = Input::get("SDate2");
      $EDate2 = Input::get("EDate2");
      $ignoreCostDate =  Input::get("ignoreCostDate");
      $ignoreInvoiceDate =  Input::get("ignoreInvoiceDate");
      $filters = array();
      $filters['__gen'] = 'General Service Report';
      $filters['__tech_job'] = 'Technicians On Job';
      $filters['__open_cus'] = 'Customer Having Open Jobs';
      $filters['__comp_work'] = 'Completed Work Orders';
      $filters['__comp_work_tech'] = 'Completed Work Orders by Particular Technician';
      $filters['__comp_work_sold'] = 'Who Sold The Work';
      $filters['__comp_work_profit'] = '% of Jobs Profitable';
      $filters['__comp_work_sale_productivity'] = 'Salesperson Productivity Report';
      while (list($k,$v)=each($_REQUEST)) {
        if (preg_match("/^__/i",$k)) {
            $checks[$k] = $v;
        }
      }
      if (isset($_REQUEST['__gen'])) { 
                $fromBigPM0= DB::select(DB::raw("select count(id) as t_count from gpg_job where job_num like 'PM%' $queryPartPM  "));
                $fromBigPM = @$fromBigPM0[0]->t_count;
                $comPM0=DB::select(DB::raw("select count(id) as t_count  from gpg_job where job_num like 'PM%' and complete='1' $queryPart2 "));
                $comPM = @$comPM0[0]->t_count;
                $notComPM0=DB::select(DB::raw("select count(id) as t_count  from gpg_job where job_num like 'PM%' and complete = '0' $queryPartPM1 "));
                $notComPM = @$notComPM0[0]->t_count;
                ///////////
                if($SDate2!='' and $EDate2!=''){
                  $rangePM0=DB::select(DB::raw("select count(id) as t_count  from gpg_job where job_num like 'PM%' $queryPart2 "));
                  $rangePM = $rangePM0[0]->t_count;
                  $rangeQT0=DB::select(DB::raw("select count(id) as t_count  from gpg_job where job_num like 'QT%' $queryPart2"));
                  $rangeQT = $rangeQT0[0]->t_count;
                  $rangeTC0=DB::select(DB::raw("select count(id) as t_count  from gpg_job where job_num like 'TC%' $queryPart2 "));
                  $rangeTC = $rangeTC0[0]->t_count;
                }else{
                  $rangePM=0;
                  $rangeTC=0;
                  $rangeQT=0;
                }
                $fromBigQT0=DB::select(DB::raw("select count(id) as t_count from gpg_job where job_num like 'QT%' $queryPartQT"));
                $fromBigQT = $fromBigQT0[0]->t_count;
                $comQT0=DB::select(DB::raw("select count(id) as t_count from gpg_job where job_num like 'QT%' and complete='1' $queryPart2 "));
                $comQT = $comQT0[0]->t_count;
                $notComQT0=DB::select(DB::raw("select count(id) as t_count from gpg_job where job_num like 'QT%' and complete = '0' $queryPartQT1 "));
                $notComQT = $notComQT0[0]->t_count;
                $fromBigTC0=DB::select(DB::raw("select count(id) as t_count from gpg_job where job_num like 'TC%' $queryPartTC "));
                $fromBigTC = $fromBigTC0[0]->t_count;
                $comTC0=DB::select(DB::raw("select count(id) as t_count from gpg_job where job_num like 'TC%' and complete='1' $queryPart2 "));
                $comTC = $comTC0[0]->t_count;
                $notComTC0=DB::select(DB::raw("select count(id) as t_count from gpg_job where job_num like 'TC%' and complete = '0' $queryPartTC1 "));
                $notComTC = $notComTC0[0]->t_count;
                $totalInvoiced0= DB::select(DB::raw("select count(id) as t_count from gpg_job where (select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id $queryPartInvoice limit 0,1) and (job_num like 'PM%' or job_num like 'QT%' or job_num like 'TC%') $queryPart2 "));
                $totalInvoiced = $totalInvoiced0[0]->t_count;
                $totalNotInvoiced0= DB::select(DB::raw("select count(id) as t_count from gpg_job where  if((select gpg_job_id from gpg_job_invoice_info where gpg_job_id = gpg_job.id $queryPartInvoice limit 0,1)>0,0,1) AND (job_num like 'PM%' or job_num like 'QT%' or job_num like 'TC%') $queryPart2 "));
                $totalNotInvoiced = $totalNotInvoiced0[0]->t_count;
              ?>
              <h4>Service Jobs</h4>
              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Job Type</th>
                  <th>From Beginning</th>
                  <th>Added</th>
                  <th>Completed</th>
                  <th>Open</th>
              </tr>
              </thead>
              <tbody class="cf">
                <tr  bgcolor="#FFFFFF">
                    <td >&nbsp;PM</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$fromBigPM, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$rangePM, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$comPM, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$notComPM, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                   </tr>
                   <tr  bgcolor="#FFFFFF">
                    <td >&nbsp;QT</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$fromBigQT, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$rangeQT, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$comQT, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$notComQT, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                   </tr> 
                   <tr  bgcolor="#FFFFFF">
                    <td >&nbsp;TC</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$fromBigTC, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$rangeTC, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$comTC, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                    <td align="center" >&nbsp;{{ HTML::link('job/service_job_list/',$notComTC, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                   </tr> 
                   <tr>
                      <td>Of the ones "Completed" = <strong>{{ HTML::link('job/service_job_list/',($comTC+$comQT+$comPM), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</strong></td>
                    </tr>
                    <tr>
                      <td>How many have been invoiced = <strong>{{ HTML::link('job/service_job_list/',$totalInvoiced, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</strong></td>
                    </tr>
                    <tr>
                      <td>How many have not been invoiced = <strong>{{ HTML::link('job/service_job_list/',$totalNotInvoiced, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</strong></td>
                    </tr>
               </tbody>
              </table>
              <?php }
                if (isset($_REQUEST['__tech_job'])){ 
                  $totJob = 0;
                  $totContractAmt = 0;
                  $totInvAmt = 0;
                  $totTaxAmt = 0;
                  $totInvAmtNet = 0;
                  $totMatCost = 0;
                  $totLaborCost = 0;
                  $totCostToDate = 0;
                  $totalEmployee= DB::select(DB::raw("select (b.GPG_employee_id) as tech_id, (select name from gpg_employee where id=b.GPG_employee_Id) as technician from gpg_timesheet_detail a, gpg_timesheet b where b.id=a.GPG_timesheet_id and (a.job_num like 'PM%' or a.job_num like 'QT%' or a.job_num like 'TC%') group by b.GPG_employee_Id"));
                
              ?>
              <h4>TECHNICIANS ON JOBS</h4>
              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Technician on jobs</th>
                  <th>Completed # of Jobs</th>
                  <th>Contract Amount</th>
                  <th>Inv'd Amount</th>
                  <th>Tax</th>
                  <th>Inv'd Amount Net</th>
                  <th>Material Cost</th>
                  <th>Labor Cost</th>
                  <th>Cost to Date</th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php 
                        $fg = false;
                        $flag1=false;
                        foreach ($totalEmployee as $key => $value)
                        {
                          $employeeRow = (array)$value;
                          $tech_rows = DB::select(DB::raw("select count(id) as cnt,sum((select sum(gpg_job_invoice_info.invoice_amount - gpg_job_invoice_info.tax_amount) from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id = gpg_job.id $queryPartInvoice group by gpg_job_invoice_info.gpg_job_id)) as invoice_amount_net, sum(if(fixed_price>0,fixed_price,contract_amount)) as contract_amount, sum((select sum(invoice_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice)) as inv_amount, sum((select sum(tax_amount)  from gpg_job_invoice_info where gpg_job_invoice_info.gpg_job_id=gpg_job.id $queryPartInvoice)) as tax_amount ,sum((select sum(total_wage) from gpg_timesheet_detail a , gpg_timesheet b where b.id = a.gpg_timesheet_id and a.job_num = gpg_job.job_num $queryPartTimesheet)) as labor_cost,sum((select sum(amount) from gpg_job_cost where job_num = gpg_job.job_num $queryPartMaterialCost)) as mat_cost, sum(cost_to_dat) as cost_to_date from gpg_job where (job_num like 'PM%' or job_num like 'QT%' or job_num like 'TC%') AND complete='1' AND id IN (select d.GPG_job_id from gpg_timesheet_detail d, gpg_timesheet e where e.id = d.GPG_timesheet_id and e.GPG_employee_Id = '".$employeeRow['tech_id']."') $queryPart2")); 
                          $tech_row = array();
                          foreach ($tech_rows as $key => $value2) {
                            $tech_row = (array)$value2;
                          }
                          if($tech_row['cnt'] > 0)
                          {
                            $flag1=true;
                            ?>
                            <tr bgcolor="<?php echo ($fg?"#FFFFCC":"#FFFFFF") ?>">
                              <td height="20" ><?php echo ($employeeRow['technician']!=''?$employeeRow['technician']:"Technician is not Selected")?></td>
                              <td align="center"> {{ HTML::link('job/service_job_list/',$tech_row['cnt'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                                <?php $totJob +=$tech_row['cnt']; ?>
                              </td>
                              <td align="center">{{ HTML::link('job/service_job_list/','$'.number_format($tech_row['contract_amount'],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                              <?php $totContractAmt +=$tech_row['contract_amount'];?>
                              </td>
                              <td align="center">{{ HTML::link('job/service_job_list/','$'.number_format($tech_row['inv_amount'],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                              <?php $totInvAmt +=$tech_row['inv_amount'];?>
                              </td>
                              <td align="center">{{ HTML::link('job/service_job_list/','$'.number_format($tech_row['tax_amount'],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                              <?php $totTaxAmt +=$tech_row['tax_amount'];?>
                              </td>
                              <td align="center">{{ HTML::link('job/service_job_list/','$'.number_format($tech_row['invoice_amount_net'],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                              <?php $totInvAmtNet += $tech_row['invoice_amount_net'];?>
                              </td>
                              <td align="center">{{ HTML::link('job/service_job_list/','$'.number_format($tech_row['mat_cost'],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                              <?php $totMatCost +=$tech_row['mat_cost'];?>
                              </td>
                              <td align="center">{{ HTML::link('job/service_job_list/','$'.number_format($tech_row['labor_cost'],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                              <?php $totLaborCost +=$tech_row['labor_cost'];?>
                              </td>
                              <td align="center">{{ HTML::link('job/service_job_list/','$'.number_format($tech_row['cost_to_date'],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                              <?php $totCostToDate +=$tech_row['cost_to_date'];?>
                              </td>
                            </tr>
                           <?php 
                            $fg = !$fg;
                         } 
                       } 
                       if($flag1==true) { ?>
                        <tr bgcolor="<?php echo ($fg?"#FFFFCC":"#FFFFFF") ?>">
                          <td height="25" ><strong>Totals</strong></td>
                          <td align="center"><strong><?php echo $totJob;?></strong></td>
                          <td align="center"> <strong><?php echo '$'.number_format($totContractAmt,2)?></strong></td>
                          <td align="center"> <strong><?php echo '$'.number_format($totInvAmt,2)?></strong></td>
                          <td align="center"> <strong><?php echo '$'.number_format($totTaxAmt,2)?></strong></td>
                          <td align="center"> <strong><?php echo '$'.number_format($totInvAmtNet,2)?></strong></td>
                          <td align="center"> <strong><?php echo '$'.number_format($totMatCost,2)?></strong></td>
                          <td align="center"> <strong><?php echo '$'.number_format($totLaborCost,2)?></strong></td>
                          <td align="center"> <strong><?php echo '$'.number_format($totCostToDate,2)?></strong></td>
                        </tr>
                      <?php } else { ?>
                        <tr bgcolor="#FFFFFF">
                          <td align="center" colspan="9" >No Records Found</td>
                        </tr>
                      <?php } ?>
                </tbody>
            </table>
            <?php } 
              if (isset($_REQUEST['__open_cus'])){ ?>
                <h4>CUSTOMER HAVING OPEN JOBS</h4>
                <table class="table table-bordered table-striped table-condensed cf" >
                <thead class="cf">
                <tr>
                    <th>Open Customer</th>
                    <th># of Jobs</th>
                    <th>PM</th>
                    <th>QT</th>
                    <th>TC</th>
                </tr>
                </thead>
                <tbody class="cf">
                  <?php 
                        $fg= false;
                        $flag2= false;
                        foreach ($totalCustomer as $key => $value3)
                        {
                          $customerRow = (array)$value3;
                          $flag2= true;
                          ?>
                          <tr bgcolor="<?php echo ($fg?"#FFFFCC":"#FFFFFF") ?>">
                          <td width="42%" height="20" ><?php echo ($customerRow['customer']!=''?$customerRow['customer']:"Customer is not Selected");?></td>
                          <td width="14%" align="center">{{ HTML::link('job/service_job_list/',$customerRow['count'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                          <td width="15%" align="center">{{ HTML::link('job/service_job_list/',$customerRow['pm_count'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                          <td width="15%" align="center">{{ HTML::link('job/service_job_list/',$customerRow['qt_count'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                          <td width="14%" align="center">{{ HTML::link('job/service_job_list/',$customerRow['tc_count'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                          </tr>
                          <?php 
                          $fg= !$fg;
                       }
                       if($flag2!=true)
                       {
                       ?>
                       <tr bgcolor="#FFFFFF">
                        <td align="center" colspan="5" >No More Records</td>
                        </tr> 
                       <?php } ?>
                </tbody>
                </table>
            <?php }
              if(isset($_REQUEST['__comp_work'])) { ?>
                <h4>COMPLETED WORK ORDERS</h4>
                <table class="table table-bordered table-striped table-condensed cf" >
                <thead class="cf">
                <tr>
                    <th>Work Order # </th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Amount Billed</th>
                </tr>
                </thead>
                <tbody class="cf">
                <?php 
                  $fg = false;
                  $totalCompletedAmount = 0;
                  foreach ($completed_workorders as $key => $completed_workordersRow){
                    $totalCompletedAmount = $totalCompletedAmount + $completed_workordersRow['invoice_sum'];
                ?>
                  <tr bgcolor="<?php echo ($fg?"#FFFFCC":"#FFFFFF"); ?>">
                    <td align="center" height="20" ><?php echo $completed_workordersRow['job_num'];?></td>
                    <td  align="left"><?php echo $completed_workordersRow['customer'];?></td>
                    <td  align="left"><?php echo $completed_workordersRow['location'];?></td>
                    <td  align="center"><?php echo '$'.number_format($completed_workordersRow['invoice_sum'],2);?></td>
                  </tr>
                <?php 
                  $fg= !$fg;
                  } ?> 
                  <tr>
                    <td  height="29" colspan="3" bgcolor="#FFFFCC" align="center"><strong>TOTALS</strong></td>
                    <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalCompletedAmount,2);?></strong></td>
                  </tr>
                </tbody>
                </table>
            <?php  }
              if (isset($_REQUEST['__comp_work_tech'])) { ?>
                <h4>COMPLETED WORK ORDERS BY PARTICULAR TECHNICIAN</h4>
                <table class="table table-bordered table-striped table-condensed cf" >
                <thead class="cf">
                <tr>
                    <th>Work Order #</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Amount Billed</th>
                    <th>Technician</th>
                    <th>Hours Worked</th>
                </tr>
                </thead>
                <tbody class="cf">
                    <?php 
                        $fg=false;
                        $totalTechAmount = 0;
                      foreach ($completed_workorders_tech as $key => $completed_workorders_techRow){
                          $totalTechAmount = $totalTechAmount + $completed_workorders_techRow['invoice_sum'];
                        ?>
                        <tr bgcolor="<?php echo ($fg?"#FFFFCC":"#FFFFFF"); ?>">
                          <td align="center" height="20" ><?php echo $completed_workorders_techRow['job_num'];?></td>
                          <td  align="left"><?php echo $completed_workorders_techRow['customer'];?></td>
                          <td  align="left"><?php echo $completed_workorders_techRow['location'];?></td>
                          <td  align="center" ><?php echo '$'.number_format($completed_workorders_techRow['invoice_sum'],2)?></td>
                          <td  align="left">
                          <?php 
                          $timesheet_employee = DB::select(DB::raw("select b.GPG_employee_Id,time_diff_dec, (select name from gpg_employee where id=b.GPG_employee_Id) as technician from gpg_timesheet_detail a , gpg_timesheet b where b.id = a.gpg_timesheet_id and a.job_num = '".$completed_workorders_techRow['job_num']."' $queryPartTimesheet group by b.GPG_employee_Id"));
                          $hours_worked='';
                          $techs_completed_workorders_techRow='';
                          foreach ($timesheet_employee as $key => $value4)
                          {
                            $timesheet_employeeRow = (array)$value4;
                            $techs_completed_workorders_techRow.=" ".$timesheet_employeeRow['technician'].",";
                            $hours_worked= $hours_worked + $timesheet_employeeRow['time_diff_dec'];
                          }
                          echo $techs_completed_workorders_techRow;
                          ?>                        
                          </td>
                          <td  align="center"><?php echo $hours_worked;?></td>
                        </tr>
                         <?php 
                         $fg = !$fg;
                         } ?> 
                        <tr>
                          <td  height="29" colspan="3" bgcolor="#FFFFCC" align="center"><strong>TOTALS</strong></td>
                          <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalTechAmount,2);?></strong></td>
                          <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>
                          <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>                       
                        </tr>
                </tbody>
                </table>
            <?php  }
               if (isset($_REQUEST['__comp_work_sold'])) { ?>
                <h4>WHO SOLD THE WORK</h4>
                <table class="table table-bordered table-striped table-condensed cf" >
                <thead class="cf">
                <tr>
                    <th>Work Order #</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Amount Billed</th>
                    <th>Technician</th>
                    <th>Hours Worked</th>
                    <th>Sales Person</th>
                </tr>
                </thead>
                <tbody class="cf">
                  <?php 
                    $fg= false;
                    $totalSalesPeronAmount = 0;
                    foreach ($completed_workorders_salesperson as $key => $completed_workorders_salespersonRow){
                      $totalSalesPeronAmount = $totalSalesPeronAmount + $completed_workorders_salespersonRow['invoice_sum'];
                  ?>
                    <tr bgcolor="<?php echo ($fg?"#FFFFCC":"#FFFFFF") ?>">
                      <td  height="20" align="center"><?php echo $completed_workorders_salespersonRow['job_num'];?></td>
                      <td align="left"><?php echo $completed_workorders_salespersonRow['customer'];?></td>
                      <td  align="left"><?php echo $completed_workorders_salespersonRow['location'];?></td>
                      <td align="center"><?php echo '$'.number_format(@$completed_workorders_salespersonRow['invoice_sum'],2);?></td>
                      <td  align="left">
                  <?php 
                      $timesheet_employee_salesperson = DB::select(DB::raw("select b.GPG_employee_Id,time_diff_dec, (select name from gpg_employee where id=b.GPG_employee_Id) as technician from gpg_timesheet_detail a , gpg_timesheet b where b.id = a.gpg_timesheet_id and a.job_num = '".$completed_workorders_salespersonRow['job_num']."' $queryPartTimesheet group by b.GPG_employee_Id"));
                      $hours_worked='';
                      $techs_salesperson='';
                      foreach ($timesheet_employee_salesperson as $key => $value5)
                      {
                        $timesheet_employee_salespersonRow = (array)$value5;
                        $techs_salesperson.=" ".$timesheet_employee_salespersonRow['technician'].",";
                        $hours_worked= $hours_worked + $timesheet_employee_salespersonRow['time_diff_dec'];
                      }
                      echo $techs_salesperson;
                  ?>  </td>
                      <td  align="center"><?php echo $hours_worked;?></td>
                      <td align="left"><?php echo $completed_workorders_salespersonRow['sales_person'];?></td>
                    </tr>
                  <?php 
                       $fg = !$fg;
                  } ?> 
                    <tr>
                      <td  height="29" colspan="3" bgcolor="#FFFFCC" align="center"><strong>TOTALS</strong></td>
                      <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalSalesPeronAmount,2);?></strong></td>
                      <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>
                      <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>
                      <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>
                    </tr>
                </tbody>
                </table>
            <?php }
            if (isset($_REQUEST['__comp_work_profit'])) { ?>
                <h4>% OF JOBS PROFITABLE</h4>
                <table class="table table-bordered table-striped table-condensed cf" >
                <thead class="cf">
                <tr>
                    <th>Work Order #</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Amount Billed</th>
                    <th>Technician</th>
                    <th>Hours Worked</th>
                    <th>Sales Person</th>
                    <th>Labor Cost</th>
                    <th>Material Cost</th>
                    <th>Total Cost</th>
                    <th>Margin</th>
                    <th>Margin %</th>
                </tr>
                </thead>
                <tbody class="cf">
                  <?php 
                    $fg=false;
                    $totalProfitAmount = 0;
                    $totalProfitLabor = 0;
                    $totalProfitMaterial = 0;
                    $totalProfitTotal = 0;
                    $totalProfitMargin = 0;
                    foreach ($completed_workorders_profit as $key => $completed_workorders_profitRow){
                      $totalProfitAmount = $totalProfitAmount + $completed_workorders_profitRow['invoice_sum'];
                      $totalProfitLabor = $totalProfitLabor + $completed_workorders_profitRow['labor_cost'];
                      $totalProfitMaterial = $totalProfitMaterial + $completed_workorders_profitRow['material_cost'];
                    ?>
                    <tr bgcolor="<?php echo ($fg?"#FFFFCC":"#FFFFFF") ?>">
                      <td  height="20" align="center"><?php echo $completed_workorders_profitRow['job_num']?></td>
                      <td align="left"><?php echo wordwrap($completed_workorders_profitRow['customer'],10,"<br \> \n", 1)?></td>
                      <td  align="left"><?php echo wordwrap($completed_workorders_profitRow['location'],10,"<br \> \n", 1)?></td>
                      <td align="center"><?php echo '$'.number_format($completed_workorders_profitRow['invoice_sum'],2)?></td>
                    <?php
                      $timesheet_employee_profit=DB::select(DB::raw("select b.GPG_employee_Id,time_diff_dec, (select name from gpg_employee where id=b.GPG_employee_Id) as technician from gpg_timesheet_detail a , gpg_timesheet b where b.id = a.gpg_timesheet_id and a.job_num = '".$completed_workorders_profitRow['job_num']."' $queryPartTimesheet group by b.GPG_employee_Id"));
                      $hours_worked=0.0;
                      $techs = '';
                      foreach ($timesheet_employee_profit as $key => $value6){
                        $timesheet_employee_profitRow = (array)$value6;
                        $techs.=" ".$timesheet_employee_profitRow['technician'].",";
                        $hours_worked= $hours_worked + $timesheet_employee_profitRow['time_diff_dec'];
                      }
                    ?>
                      <td  align="left"><?php echo $techs;?></td>
                      <td  align="center"><?php echo $hours_worked;?></td>
                      <td align="left"><?php echo $completed_workorders_profitRow['sales_person'];?></td>
                      <td align="center"><?php echo '$'.number_format($completed_workorders_profitRow['labor_cost'],2);?></td>
                      <td align="center"><?php echo '$'.number_format($completed_workorders_profitRow['material_cost'],2);?></td>
                      <td align="center"><?php $profit_total_cost = $completed_workorders_profitRow['labor_cost']+$completed_workorders_profitRow['material_cost'];
                        $totalProfitTotal = $totalProfitTotal + $profit_total_cost; 
                        echo '$'.number_format($profit_total_cost,2);             
                    ?></td>
                    <?php 
                        $profit_margin = $completed_workorders_profitRow['invoice_sum']-$profit_total_cost;
                        $totalProfitMargin = $totalProfitMargin + $profit_margin;                         
                        ?>
                        <td align="center"><?php echo '$'.number_format($profit_margin,2);?></td>
                        <?php $perc_margin= @($profit_margin / $completed_workorders_profitRow['invoice_sum']) * 100; ?>
                        <td align="center"><?php echo number_format($perc_margin,2)."%";?></td>
                      </tr>
                    <?php 
                      $fg= !$fg;
                      } ?> 
                      <tr>
                        <td  height="29" colspan="3" bgcolor="#FFFFCC" align="center"><strong>TOTALS</strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProfitAmount,2);?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>
                        <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>
                        <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProfitLabor,2);?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProfitMaterial,2);?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProfitTotal,2);?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProfitMargin,2);?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>
                      </tr> 
                </tbody>
                </table>
                <?php }
                  if (isset($_REQUEST['__comp_work_sale_productivity'])) { ?>
                      <h4>Salesperson Productivity Report</h4>
                      <table class="table table-bordered table-striped table-condensed cf" >
                      <thead class="cf">
                      <tr>
                          <th>Sales Person</th>
                          <th>Job Type</th>
                          <th>Amount Billed</th>
                          <th>Labor Cost</th>
                          <th>Material Cost</th>
                          <th>Total Cost</th>
                          <th>Margin</th>
                          <th>Margin %</th>
                      </tr>
                      </thead>
                      <tbody class="cf">
                        <?php 
                        $preSalesPerson="";
                        $totalProductivityAmount = 0;
                        $totalProductivityLabor = 0;
                        $totalProductivityMaterial = 0;
                        $totalProductivityTotal = 0;
                        $totalProductivityMargin = 0;
                        $fg=true;
                        foreach ($comp_work_sale_productivity as $key => $comp_work_sale_productivityRow){
                          if ($preSalesPerson!=$comp_work_sale_productivityRow['sales_person']){
                            $fg=!$fg;
                          }
                          $totalProductivityAmount = $totalProductivityAmount + $comp_work_sale_productivityRow['invoice_amount_net'];
                          $totalProductivityLabor = $totalProductivityLabor + $comp_work_sale_productivityRow['labor_cost'];
                          $totalProductivityMaterial = $totalProductivityMaterial + $comp_work_sale_productivityRow['material_cost'];
                        ?>
                        <tr bgcolor="<?php echo ($fg?"#FFFFCC":"#FFFFFF")?>">
                          <td  height="20" align="center"><?php echo ($preSalesPerson==$comp_work_sale_productivityRow['sales_person']?(empty($comp_work_sale_productivityRow['sales_person'])?"No Sales Person":""):$comp_work_sale_productivityRow['sales_person'])?></td>
                          <td align="left" nowrap="nowrap"><?php echo str_replace('JOBS',$comp_work_sale_productivityRow['PM_job'],$comp_work_sale_productivityRow['job_type']);
                          ?>
                          </td>
                          <td align="center">{{ HTML::link('job/service_job_list/','$'.number_format($comp_work_sale_productivityRow['invoice_amount_net'],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                          <td align="center"><?php echo '$'.number_format($comp_work_sale_productivityRow['labor_cost'],2);?></td>
                          <td align="center"><?php echo '$'.number_format($comp_work_sale_productivityRow['material_cost'],2);?></td>
                          <?php
                           $total_cost=$comp_work_sale_productivityRow['material_cost']+$comp_work_sale_productivityRow['labor_cost'];
                           $totalProductivityTotal = $totalProductivityTotal + $total_cost;
                        ?>
                          <td align="center"><?php echo '$'.number_format($total_cost,2);?></td>
                        <?php 
                          $margin=$comp_work_sale_productivityRow['invoice_amount_net']-$total_cost;
                          $totalProductivityMargin = $totalProductivityMargin + $margin;
                        ?>
                          <td align="center"><?php echo '$'.number_format($margin,2);?></td>
                          <td align="center"><?php echo number_format($prec_margin=@(($margin/$comp_work_sale_productivityRow['invoice_amount_net'])*100),2)."%";?></td>
                        </tr>
                       <?php 
                       $preSalesPerson = $comp_work_sale_productivityRow['sales_person'];
                       }
                      ?>
                      <tr>
                        <td  height="29" colspan="2" bgcolor="#FFFFCC" align="center"><strong>TOTALS</strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProductivityAmount,2);?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProductivityLabor,2);?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProductivityMaterial,2);?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProductivityTotal,2);?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($totalProductivityMargin,2)?></strong></td>
                        <td  align="center" bgcolor="#FFFFCC"><div align="center"><strong></strong></div></td>
                       </tr> 
                      </tbody>
                      </table>
                <?php }?>