@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<style type="text/css">
   #flip-scroll .cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
    #flip-scroll * html .cf { zoom: 1; }
    #flip-scroll *:first-child+html .cf { zoom: 1; }
    #flip-scroll table { width: 100%; border-collapse: collapse; border-spacing: 0; }

    #flip-scroll th,
    #flip-scroll td { margin: 0; vertical-align: top; }
    #flip-scroll th { text-align: left; }
    #flip-scroll table { display: block; position: relative; width: 100%; }
    #flip-scroll thead { display: block; float: left; }
    #flip-scroll tbody { display: block; width: auto; position: relative; overflow-x: auto; white-space: nowrap; }
    #flip-scroll thead tr { display: block; }
    #flip-scroll th { display: block; text-align: right; }
    #flip-scroll tbody tr { display: inline-block; vertical-align: top; }
    #flip-scroll td { display: block; min-height: 1.25em; text-align: left; }


    /* sort out borders */

    #flip-scroll th { border-bottom: 0; border-left: 0; }
    #flip-scroll td { border-left: 0; border-right: 0; border-bottom: 0; }
    #flip-scroll tbody tr { border-left: 1px solid #babcbf; }
    #flip-scroll th:last-child,
    #flip-scroll td:last-child { border-bottom: 1px solid #babcbf; }
</style>
<?php //header('Content-Type: application/pdf'); ?>
              <!-- page start-->
          <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">    
                FINANCIAL REPORT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                </header>
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10.1px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10.1px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                <td data-title="Date Created Start:">
                                    {{Form::label('CreatedSDate', 'Date Created Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CreatedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CreatedSDate')) }}
                                  </td>
                                  <td data-title="Date Created End:">
                                    {{Form::label('CreatedEDate', 'Date Created End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CreatedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CreatedEDate')) }}
                                  </td>
                                  <td data-title="Invoice Date Start:">
                                    {{Form::label('InvoiceSDate', 'Invoice Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceSDate')) }}
                                  </td><td data-title="Invoice Date End:">
                                    {{Form::label('InvoiceEDate', 'Invoice Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceEDate')) }}
                                  </td>
                                  <td data-title="Job Comp. Date Start:">
                                    {{Form::label('JobCompleteSDate', 'Job Comp. Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JobCompleteSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobCompleteSDate')) }}
                                  </td>
                                  <td data-title="Job Comp. Date End:">
                                    {{Form::label('JobWonEDate', 'Job Comp. Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JobWonEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobWonEDate')) }}
                                  </td>
                                </tr>  
                                <tr>
                                  <td>
                                    {{ Form::checkbox('ignoreCostDate','1','', array('id'=>'ignoreCostDate','class' => 'input-group','style'=>'display:inline;')) }}
                                    Ignore Date stamp  on Material Cost and Labor Cost.<br />
                                    {{ Form::checkbox('ignoreInvoiceDate','1','', array('id'=>'ignoreInvoiceDate','class' => 'input-group','style'=>'display:inline;')) }}
                                    Ignore Date stamp  on Invoice Amount.
                                  </td>
                                  <td data-title="Sales Person:">
                                    {{Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optEmployee', $salesp_arr, null, ['id' => 'optEmployee', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Job Status:">
                                    {{Form::label('jobStatus', 'Job Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('jobStatus', array(''=>'ALL',"completed"=>"Have been Completed","notcompleted"=>"Jobs Not Completed","invoiced"=>"Have been Invoiced","comp_inv"=>"Have been Invoiced and Completed","not_comp_inv"=>"Have been Invoiced but Not Completed","not_invoiced"=>"Have Not been Invoiced","completed_not_invoiced"=>"Completed but Have Not been Invoiced","completed_not_closed"=>"Completed Not Closed","closed_not_completed"=>"Closed Not Completed"), null, ['id' => 'jobStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Customer:">
                                    {{Form::label('optCustomer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optCustomer', $cust_arr, null, ['id' => 'optCustomer', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Estimator:">
                                    {{Form::label('optEstimator', 'Estimator:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optEstimator', $salesp_arr, null, ['id' => 'optEstimator', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Job Type:">
                                    {{Form::label('jobTypeTask', 'Job Type:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('jobTypeTask', $jobtype_arr, null, ['id' => 'jobTypeTask', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                </tr>
                                <tr>
                                  <td data-title="Job Number Start:">
                                    {{Form::label('SJobNumber', 'Job Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SJobNumber','', array('class' => 'form-control', 'id' => 'SJobNumber')) }}
                                  </td>
                                  <td data-title="Job Number End (optional):">
                                    {{Form::label('EJobNumber', 'Job Number End(opt.):', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EJobNumber','', array('class' => 'form-control', 'id' => 'EJobNumber')) }}
                                  </td>
                                  <td data-title="Contract Number:">
                                    {{Form::label('contract_number', 'Contract Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('contract_number','', array('class' => 'form-control', 'id' => 'contract_number')) }}
                                  </td>
                                  <td data-title="Job Type:">
                                    {{Form::label('optJobType[]', 'Job Type:(Ctrl+Click)', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobType[]', $job_types, null, ['multiple','class'=>'form-control','id'=>'optJobType'])}}
                                  </td>
                                   <td data-title="Jobs Having:">
                                    {{Form::label('optJobHaving[]', 'Jobs Having:(Ctrl+Click)', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobHaving[]',array("po"=>"PO Records","cost"=>"Material Cost Records","timesheet"=>"Timesheet Records","no_inv"=>"Have not been Invoiced","inv_cost"=>"Have Cost and Invoiced","no_labor"=>"Have no Labor Cost","no_mat"=>"Have no Material Cost"), null, ['multiple','class'=>'form-control','id'=>'optJobHaving'])}}
                                  </td>
                                  <td data-title="Actions">
                                    {{Form::submit('Submit', array('class' => 'btn btn-info'))}}<br/><br/>
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}}
                                  </td>
                                </tr>  
                              </tbody>
                          </table>
                      </section>
                               {{ Form::close() }}
              </section>
              </section>
              </div>
                <div class="row">
            <div class="col-sm-12">
               <!-- ////////////////////////////////////////// -->
              <div class="panel">
              <div class="adv-table">
              <section id="flip-scroll" >
                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;" data-title="">Customer</th>
                            <th style="text-align:center;" data-title="">Job Number</th>
                            <th style="text-align:center;" data-title="">Quote Number</th>
                            <th style="text-align:center;" data-title="">Contract Number  </th>
                            <th style="text-align:center;" data-title="">Job Type</th>
                            <th style="text-align:center;" data-title="">Job Status   </th>
                            <th style="text-align:center;" data-title="">Job Completion Date</th>
                            <th style="text-align:center;" data-title="">Invoice Date</th>
                            <th style="text-align:center;" data-title="">Index 1</th>
                            <th style="text-align:center;" data-title="">AR</th>
                            <th style="text-align:center;" data-title="">AP</th>
                            <th style="text-align:center;" data-title="">Estimated Revenue</th>
                            <th style="text-align:center;" data-title="">Estimated Materials</th>
                            <th style="text-align:center;" data-title="">Estimated Labor</th>
                            <th style="text-align:center;" data-title="">Index 2</th>
                            <th style="text-align:center;" data-title="">Inv'd Amount</th>
                            <th style="text-align:center;" data-title="">Tax</th>
                            <th style="text-align:center;" data-title="">Act. Revenue</th>
                            <th style="text-align:center;" data-title="">Material Cost  </th>
                            <th style="text-align:center;" data-title="">Labor Cost</th>
                            <th style="text-align:center;" data-title="">Total Costsn</th>
                            <th style="text-align:center;" data-title="">Net Margin</th>
                            <th style="text-align:center;" data-title="">PO's Issued</th>
                            <th style="text-align:center;" data-title="">PO's Received</th>
                            <th style="text-align:center;" data-title="">Point</th>
                            <th style="text-align:center;" data-title="">Filter</th>
                            <th style="text-align:center;" data-title="">Sales Person</th>
                            <th style="text-align:center;" data-title="">Estimator</th>
                          </tr>
                        </thead>
                      <tbody>
                      @if(!empty($query_data))
                      @foreach($query_data as $getRow)
                       <tr>
                         <td style="padding-bottom:10.1px;">{{substr($getRow['customer_name'],0,20).'...'}}</td>
                         <td style="padding-bottom:10.1px;">
                          {{ HTML::link('job/job_form/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}}
                         <td style="padding-bottom:10.1px;">{{isset($getRow['attach_job_num'])?$getRow['attach_job_num']:'-'}}</td>
                         <td style="padding-bottom:10.1px;">{{isset($getRow['contract_number'])?$getRow['contract_number']:'-'}}</td>
                         <td style="padding-bottom:10.1px;">
                            <?php
                            $contactAmountT = 0;
                            $chkEstimatedAmount =0;
                            $budgetMaterialT=0;
                            $budgetLaborT =0;
                            $invAmtTotal =0;
                            $taxAmtT =0;
                            $invAmtT=0;
                            $materialCostT=0;
                            $laborCostT =0;
                            $totalCostT=0;
                            $marginT=0;
                            $poQueryPart ="";
                            $poIssuedT=0;
                            $poReceivedT=0;
                            $point_total=0;
                            $index2=0;
                            if (preg_match("/GPG/i",$getRow['job_num']) || preg_match("/IG/i",$getRow['job_num']) || preg_match("/LK/i",$getRow['job_num'])) {
                                echo $elecJobTypeArray[$getRow['elec_job_type']];
                            }
                            elseif(preg_match("/SH/i",$getRow['job_num'])){
                                echo "-";
                            }
                            else{
                              if (!empty($getRow['task'])){
                                echo substr($getRow['task'],0,20);
                              }else
                                echo "-";
                            }
                          ?>
                         </td>
                         <td style="padding-bottom:10.1px;">{{($getRow['complete'] =='1')?'Completed':'Not Completed'}}</td>
                         <td style="padding-bottom:10.1px;"><?php
                           $completionDate = explode("-",$getRow['date_completion']);
                            echo (@$completionDate[2]!=""?date('m/d/Y',strtotime($getRow['date_completion'])):"-");
                         ?></td>
                         <td style="padding-bottom:10.1px;"><?php
                           $invoiceData = explode("#~#",$getRow['invoice_data']);
                            echo (@$invoiceData[4]>1?"Multiple":(@$invoiceData[2]!=""?date('m/d/Y',strtotime(@$invoiceData[2])):"-")); 
                         ?></td>
                         <td style="padding-bottom:10.1px;"><?php 
                         $ar_total = 0;
                         $ap_total = 0;
                         echo $getRow['AR_on_job']==0?"-":'$'.number_format($getRow['AR_on_job'],2); $ar_total+=$getRow['AR_on_job'];?></td>
                         <td style="padding-bottom:10.1px;"><?php echo $getRow['AP_on_job']==0?"-":'$'.number_format($getRow['AP_on_job'],2); $ap_total+=$getRow['AP_on_job'];?></td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                            $EstimatedMaterial = 0;
                            $EstimatedLabor = 0;
                            if (preg_match("/GPG/i",$getRow['job_num'])) {
                                $est_rev = (($getRow['fixed_price']) != "0" ? ($getRow['fixed_price']) : ($getRow['nte'] != "" ? ($getRow['nte']) : ($getRow['sub_nte'] != "" ? ($getRow['sub_nte']) : ($getRow['contract_amount'] != "" ? ($getRow['contract_amount']) : 0))));
                                if($est_rev=="" or $est_rev == 0 )
                                {
                                    if(isset($jobExist['GPG_attach_job_num'])){
                                        $EstimatedMaterial = $jobExist['grand_total_material'] + $jobExist['subquote_material_cost'] ;  
                                        $EstimatedLabor = $jobExist['grand_total_labor'] + $jobExist['subquote_labor_cost'];  
                                        $EstimatedAmount = $jobExist['grand_total_no_tax'] + $jobExist['subquote_total_cost'];

                                        if($EstimatedAmount !='' || $EstimatedAmount >0){
                                            $chkEstimatedAmount = 1;
                                            echo '$'.number_format($EstimatedAmount,2); $contactAmountT+=$EstimatedAmount;
                                        } else {
                                            echo '$'.number_format($getRow['contract_amount'],2); $contactAmountT+=$getRow['contract_amount'];
                                        }
                                    } else {
                                        echo '$'.number_format($getRow['contract_amount'],2); $contactAmountT+=$getRow['contract_amount'];
                                    }
                                }
                                else
                                {
                                    echo '$'.number_format($est_rev,2); $contactAmountT+=$est_rev;
                                }
                            } elseif ( preg_match("/SH/i",$getRow['job_num']) && strlen($getRow['job_num']) >=6 ){
                                  $ShopWorkQuote = DB::select(DB::raw("SELECT *  FROM gpg_shop_work_quote WHERE GPG_attach_job_num = '".$getRow['job_num']."'"));
                                  if(@$ShopWorkQuote[0]->GPG_attach_job_num){
                                    $freight0 = DB::select(DB::raw("SELECT sum(other_charge_cost_price*other_charge_qty) as totals_t FROM gpg_shop_work_quote_other WHERE other_charge_description='Freight' AND gpg_shop_work_quote_id = '".$ShopWorkQuote[0]->id."'"));
                                    if (isset( $freight0[0]->totals_t))
                                        $freight = $freight0[0]->totals_t;
                                    else
                                         $freight = 0;    
                                    $mileage0 = DB::select(DB::raw("SELECT sum(other_charge_cost_price*other_charge_qty) as totals_s FROM gpg_shop_work_quote_other WHERE other_charge_description='Mileage' AND gpg_shop_work_quote_id = '".$ShopWorkQuote[0]->id."'"));
                                    $mileage = $mileage0[0]->totals_s;
                                    $Material = $ShopWorkQuote[0]->mat_cost_total;
                                    $EstimatedMaterial =  $Material + ($Material* ($ShopWorkQuote[0]->tax_amount*.01)) + $freight;
                                    $Labor = $ShopWorkQuote->labor_cost_total;
                                    $EstimatedLabor =  $Labor + ($Labor* ($ShopWorkQuote->hazmat* .01)) + $mileage ;
                                    $EstimatedAmount = $ShopWorkQuote->sub_list_total;
                                    if($EstimatedAmount !='' || $EstimatedAmount >0){
                                        $chkEstimatedAmount = 1;
                                        echo '$'.number_format($EstimatedAmount,2); $contactAmountT+= $EstimatedAmount;
                                    } else {
                                        echo '$'.number_format($getRow['contract_amount'],2); $contactAmountT+=$getRow['contract_amount'];
                                    }

                                } else {
                                    echo '$'.number_format($getRow['contract_amount'],2); $contactAmountT+=$getRow['contract_amount'];
                                }


                            } else {
                                if(isset($field_service_quote['id']))
                                {
                                    $chkEstimatedAmount = 1;
                                    $field_freightQuery = DB::select(DB::raw("SELECT sum(other_charge_cost_price*other_charge_qty) as ress FROM gpg_field_service_work_other WHERE other_charge_description='Freight' AND gpg_field_service_work_id = '".$field_service_quote['id']."'"));
                                    $field_freightQuery = $field_freightQuery[0]->ress;
                                    $EstimatedMaterial = number_format($field_service_quote['mat_cost_total']+$field_service_quote['comp_cost_total']+($field_service_quote['mat_cost_total']*($field_service_quote['tax_amount']*.01))+$field_freight,2);
                                    $mileageQuery = DB::select(DB::raw("SELECT sum(other_charge_cost_price*other_charge_qty) as milll FROM gpg_field_service_work_other WHERE other_charge_description='Mileage' AND gpg_field_service_work_id = '".$field_service_quote['id']."'"));
                                    $mileage = $mileageQuery[0]->milll;
                                    $EstimatedLabor = number_format($field_service_quote['labor_cost_total']+($field_service_quote['sub_cost_total']*($field_service_quote['hazmat']*.01))+$mileage,2);
                                }
                                echo '$'.number_format($getRow['contract_amount'],2); $contactAmountT+=$getRow['contract_amount'];

                            }
                            ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                              if($chkEstimatedAmount){
                                  echo '$'.number_format($EstimatedMaterial,2); $budgetMaterialT+=$EstimatedMaterial;
                              }else{
                                  echo '$'.number_format($getRow['budgeted_material'],2); $budgetMaterialT+=$getRow['budgeted_material'];
                              }  
                            ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                            <?php
                              if($chkEstimatedAmount){
                                  echo '$'.number_format($EstimatedLabor,2);  $budgetLaborT+=$EstimatedLabor;
                              }else{
                                  echo '$'.number_format($getRow['budgeted_labor'],2);  $budgetLaborT+=$getRow['budgeted_labor'];
                            }?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                            $index0 = DB::select(DB::raw("SELECT FORMAT(SUM(time_diff_dec),2) index2 FROM gpg_timesheet_detail WHERE job_num = '".$getRow['job_num']."'")); 
                            if(isset($index0[0]->index2) && !empty($index0[0]->index2))
                              echo $index0[0]->index2;
                            else
                              echo "-";
                            ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                              echo '$'.number_format(@$invoiceData[1],2);
                              $invAmtTotal += @$invoiceData[1];
                            ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                               echo '$'.number_format(@$invoiceData[3],2);
                               $taxAmtT += @$invoiceData[3];
                           ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                            $invAmt = (@$invoiceData[1] - @$invoiceData[3]);
                            echo '$'.number_format($invAmt,2);
                            $invAmtT+=$invAmt;
                            ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                              $material_cost0 = DB::select(DB::raw("SELECT SUM(IFNULL(amount,0)) as matc FROM gpg_job_cost WHERE job_num='".$getRow['job_num']."'"));
                              if (!empty($material_cost0) && isset($material_cost0[0]->matc)) {
                                $material_cost = $material_cost0[0]->matc;
                              }else
                                $material_cost = 0;
                              echo '$'.number_format($material_cost,2); 
                              $materialCostT+=$material_cost;
                           ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                             $labor_cost = DB::select(DB::raw("SELECT sum(total_wage) as total_wage FROM gpg_timesheet,gpg_timesheet_detail WHERE gpg_timesheet.id = gpg_timesheet_detail.gpg_timesheet_id AND job_num = '".@$getRow['job_num']."'"));
                              
                             echo '$'.number_format(@$labor_cost[0]->total_wage,2); 
                             $laborCostT+=$labor_cost[0]->total_wage;
                             ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                            $totalCost = $material_cost + $labor_cost[0]->total_wage;
                            echo '$'.number_format($getRow['cost_to_dat'],2);
                            $totalCostT+=$getRow['cost_to_dat'];  ?>
                         </td>
                         <td style="padding-bottom:10.1px;"><?php echo '$'.number_format(($invAmt-$totalCost),2); $marginT+=($invAmt-$totalCost);  ?></td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                            $po_amounts_res = DB::select(DB::raw("SELECT po_quoted_amount,po_amount_to_dat FROM gpg_purchase_order, gpg_purchase_order_line_item WHERE gpg_purchase_order.id = gpg_purchase_order_line_item.gpg_purchase_order_id AND IFNULL(gpg_purchase_order.soft_delete,0) <> 1 AND gpg_purchase_order_line_item.GPG_job_id = '".$getRow['id']."' $poQueryPart GROUP BY gpg_purchase_order.id")); 
                            $quoted_total = 0;
                            $quoted_amnt_to_dat = 0;
                            foreach ($po_amounts_res as $key => $op_arr) {
                                $quoted_total += $op_arr->po_quoted_amount;
                                $quoted_amnt_to_dat += $op_arr->po_amount_to_dat;
                            }
                            $opAmounts = array();
                            $opAmounts[0] = $quoted_total;
                            $opAmounts[1] = $quoted_amnt_to_dat;
                            echo '$'.number_format(doubleval($opAmounts[0]),2); $poIssuedT+=$opAmounts[0];  ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php echo '$'.number_format($opAmounts[1],2); $poReceivedT+=$opAmounts[1];  ?>
                         </td>
                         <td style="padding-bottom:10.1px;">
                           <?php
                            if($invAmt <= 0 || $index2 <=0){
                                $point_total+= ($invAmt-$totalCost);
                                echo '$'.number_format(doubleval($invAmt-$totalCost),2);
                            }
                            else{
                                $point_total+= ($invAmt-$totalCost)/$index2;
                                echo '$'.number_format(doubleval(($invAmt-$totalCost)/$index2),2);
                            }
                            ?>
                         </td>
                         <td style="padding-bottom:10.1px;">{{isset($getRow['job_num'])?substr($getRow['job_num'],0,1):'-'}}</td>
                         <td style="padding-bottom:10.1px;">{{isset($getRow['sales_person_name'])?$getRow['sales_person_name']:'-'}}</td>
                         <td style="padding-bottom:10.1px;">{{isset($getRow['estimator_name'])?$getRow['estimator_name']:'-'}}</td>
                       </tr>
                       @endforeach
                       @endif
                      </tbody>
                  </table>
                </section>
                 {{ $query_data->appends(Input::except('_token'))->links() }}
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
      });
      $("section[mysection=hide_n_show]").hide();
      $('#togglerButton').click(function(){
         $("section[mysection=hide_n_show]").toggle("slow");
         if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
            $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
         else 
            $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
      }); 

      $('#reset_search_form').click(function(){
              $('#CreatedSDate').val("");
              $('#CreatedEDate').val("");
              $('#InvoiceSDate').val("");
              $('#InvoiceEDate').val("");
              $('#JobCompleteSDate').val("");
              $('#JobWonEDate').val("");
              $('#ignoreCostDate').val("");
              $('#ignoreInvoiceDate').val("");
              $('#optEmployee').val("");
              $('#jobStatus').val("");
              $('#optCustomer').val("");
              $('#optEstimator').val("");
              $('#jobTypeTask').val("");
              $('#SJobNumber').val("");
              $('#EJobNumber').val("");
              $('#contract_number').val("");
              $('#optJobType').val("");
              $('#optJobHaving').val("");      
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop