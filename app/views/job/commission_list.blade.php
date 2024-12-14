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
                 COMMISSION LISTING 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>View COMMISSION LISTING </i></b>
                </header>
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Invoice Date Start:">
                                    {{Form::label('InvoiceSDate', 'Invoice Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceSDate')) }}
                                  </td><td data-title="Invoice Date End:">
                                    {{Form::label('InvoiceEDate', 'Invoice Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceEDate')) }}
                                  </td>
                                  <td data-title="Date Job Won Start:">
                                    {{Form::label('JobWonSDate', 'Date Job Won Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JobWonSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobWonSDate')) }}
                                  </td>
                                  <td data-title="Date Job Won End:">
                                    {{Form::label('JobWonEDate', 'Date Job Won End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JobWonEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobWonEDate')) }}
                                  </td>
                                  <td data-title="Date Order Placed Start:">
                                    {{Form::label('EqpOrderedSDate', 'Date Order Placed Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EqpOrderedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EqpOrderedSDate')) }}
                                  </td>
                                  <td data-title="Date Order Placed End:">
                                    {{Form::label('EqpOrderedEDate', 'Date Order Placed End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EqpOrderedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EqpOrderedEDate')) }}
                                  </td>
                                  <td data-title="Order Confrm. SDate:">
                                    {{Form::label('EqpEngagedSDate', 'Order Confrm. SDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EqpEngagedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EqpEngagedSDate')) }}
                                  </td>
                                  <td data-title="Order Confrm. EDate:">
                                    {{Form::label('EqpEngagedEDate', 'Order Confrm. EDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EqpEngagedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EqpEngagedEDate')) }}
                                  </td>
                                </tr>
                                <tr> <!-- Second Row-->
                                  <td data-title="Date Permit Ordered Start:">
                                    {{Form::label('PermitOrderedSDate', 'Permit Ord. SDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PermitOrderedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PermitOrderedSDate')) }}
                                  </td><td data-title="Date Permit Ordered End:">
                                    {{Form::label('PermitOrderedEDate', 'Permit Ord. EDate :', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PermitOrderedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PermitOrderedEDate')) }}
                                  </td>
                                  <td data-title="Date Permit Expected Start:">
                                    {{Form::label('PermitExpectedSDate', 'Permit Expect. SDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PermitExpectedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PermitExpectedSDate')) }}
                                  </td>
                                  <td data-title="Date Permit Expected End:">
                                    {{Form::label('PermitExpectedEDate', 'Permit Expect. EDate:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PermitExpectedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PermitExpectedEDate')) }}
                                  </td>
                                  <td data-title="Date Completed Start:">
                                    {{Form::label('CompletedSDate', 'Date Completed Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CompletedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CompletedSDate')) }}
                                  </td>
                                  <td data-title="Date Completed End:">
                                    {{Form::label('CompletedEDate', 'Date Completed End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CompletedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CompletedEDate')) }}
                                  </td>
                                  <td data-title="Date Created Start:">
                                    {{Form::label('CreatedSDate', 'Date Created Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CreatedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CreatedSDate')) }}
                                  </td>
                                  <td data-title="Date Created End:">
                                    {{Form::label('CreatedEDate', 'Date Created End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('CreatedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CreatedEDate')) }}
                                  </td>
                                </tr>
                                <tr>
                                <td colspan="8">
                                  <span class="smallblack"><strong>Note:</strong> Leave blank for viewing records from all days. Fill start date only if want to see the records for a perticular date. Same note for all date fields given below.</span><br/>
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  {{ Form::checkbox('ignoreCostDate','1','', array('id'=>'ignoreCostDate','class' => 'input-group','style'=>'display:inline;')) }}
                                  Ignore Date stamp  on Material Cost and Labor Cost.<br />
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  {{ Form::checkbox('ignoreInvoiceDate','1','', array('id'=>'ignoreInvoiceDate','class' => 'input-group','style'=>'display:inline;')) }}
                                  Ignore Date stamp  on Invoice Amount.
                                </td>
                                <tr>
                                <tr> <!-- 4th Row-->
                                  <td data-title="Job Number Start:">
                                    {{Form::label('SJobNumber', 'Job Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SJobNumber','', array('class' => 'form-control', 'id' => 'SJobNumber')) }}
                                  </td>
                                  <td data-title="Job Number End (optional):">
                                    {{Form::label('EJobNumber', 'Job Number End(opt.):', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EJobNumber','', array('class' => 'form-control', 'id' => 'EJobNumber')) }}
                                  </td>
                                  <td data-title="Invoice#:">
                                    {{Form::label('InvNumber', 'Invoice#:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvNumber','', array('class' => 'form-control', 'id' => 'InvNumber')) }}
                                  </td>
                                  <td data-title="Customer:">
                                    {{Form::label('optCustomer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optCustomer', $cust_arr, null, ['id' => 'optCustomer', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Sales Person:" colspan="2">
                                    {{Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optEmployee', $salesp_arr, null, ['id' => 'optEmployee', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Estimator:" colspan="2">
                                    {{Form::label('optEstimator', 'Estimator:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optEstimator', $salesp_arr, null, ['id' => 'optEstimator', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                </tr>
                                <tr>
                                  <td data-title="Job Cost Status:">
                                    {{Form::label('optJobCostStatus', 'Job Cost Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobCostStatus', array(''=>'ALL','no_labor' => 'Have no Labor Cost', 'no_mat' => 'Have no Material Cost', 'no_both' => 'Have no Labor and Material Cost'), null, ['id' => 'optJobCostStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Job Status:">
                                    {{Form::label('optJobStatus', 'Job Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobStatus', array(''=>'ALL',"completed"=>"Have been Completed","notcompleted"=>"Jobs Not Completed","invoiced"=>"Have been Invoiced","comp_inv"=>"Have been Invoiced and Completed","not_comp_inv"=>"Have been Invoiced but Not Completed","not_invoiced"=>"Have Not been Invoiced","completed_not_invoiced"=>"Completed but Have Not been Invoiced","completed_not_closed"=>"Completed Not Closed","closed_not_completed"=>"Closed Not Completed"), null, ['id' => 'optJobStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Jobs Having:">
                                    {{Form::label('optJobHaving', 'Jobs Having:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobHaving', array(''=>'ALL',"po"=>"PO Records","cost"=>"Material Cost Records","timesheet"=>"Timesheet Records"), null, ['id' => 'optJobHaving', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Job Type:">
                                    {{Form::label('elecJobType', 'Job Type:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('elecJobType', $jobtype_arr, null, ['id' => 'elecJobType', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="National Account/Subcontractor:" colspan="2">
                                    {{Form::label('optJobAccount', 'National Account/Subcontractor:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobAccount', array(''=>'ALL',"national_account"=>"National Account Jobs","sub_contractor"=>"Subcontractor Jobs"), null, ['id' => 'optJobAccount', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Technicians Attached:" colspan="2">
                                    {{Form::label('optTechAtt', 'Technicians Attached:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optTechAtt', array(''=>'ALL',"both"=>"Single / Multiple","single"=>"Single Technician","multiple"=>"Multiple Technicians"), null, ['id' => 'optTechAtt', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                </tr>
                              </tbody>
                          </table>
                                    <br/>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::button('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
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
                            <th style="text-align:center;" data-title="">Delete</th>
                            <th style="text-align:center;" data-title="">Created Date</th>
                            <th style="text-align:center;" data-title="">Job Number</th>
                            <th style="text-align:center;" data-title="">Contract Number</th>
                            <th style="text-align:center;" data-title="">Job Name/Location</th>
                            <th style="text-align:center;" data-title="">Customer</th>
                            <th style="text-align:center;" data-title="">Contract Amount</th>
                            <th style="text-align:center;" data-title="">Invoice Date</th>
                            <th style="text-align:center;" data-title="">Inv'd Amount</th>
                            <th style="text-align:center;" data-title="">Tax</th>
                            <th style="text-align:center;" data-title="">Inv'd Amount Net</th>
                            <th style="text-align:center;" data-title="">Invoice#</th>
                            <th style="text-align:center;" data-title="">Material Cost</th>
                            <th style="text-align:center;" data-title="">Labor Cost</th>
                            <th style="text-align:center;" data-title="">Cost to Date</th>
                            <th style="text-align:center;" data-title="">Sales Person</th>
                            <th style="text-align:center;" data-title="">Estimator</th>
                            <th style="text-align:center;" data-title="">Job Status</th>
                            <th style="text-align:center;" data-title="">AR</th>
                            <th style="text-align:center;" data-title="">AP</th>
                            <th style="text-align:center;" data-title="">Calc Margin</th>
                            <th style="text-align:center;" data-title="">Sales Commission</th>
                            <th style="text-align:center;" data-title="">Sales Comm. Paid</th>
                            <th style="text-align:center;" data-title="">Date Sales Comm. Paid</th>
                            <th style="text-align:center;" data-title="">Sales Comm. Balance</th>
                            <th style="text-align:center;" data-title="">Estimator Commission</th>
                            <th style="text-align:center;" data-title="">Estimate Comm. Paid</th>
                            <th style="text-align:center;" data-title="">Date Estimate Comm. Paid</th>
                            <th style="text-align:center;" data-title="">Estimate Comm. Balance</th>
                            <th style="text-align:center;" data-title="">Net Margin</th>
                            <th style="text-align:center;" data-title="">Download</th>
                          </tr>
                        </thead>
                      <tbody>
                      @foreach($query_data as $getRow)
                       <tr>
                          <td data-title="Del:" style="padding-bottom:8.3px">{{ Form::checkbox('delChk[]',$getRow['id'],'', array('id'=>'delChk[]','class' => 'input-group')) }}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{($getRow['created_on']!=""?date('m/d/Y',strtotime($getRow['created_on'])):"-")}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{ HTML::link('job/job_form/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}} </td>
                          <td data-title=":" style="padding-bottom:8.3px">{{(strlen($getRow['contract_number'])>0)?'<strong>'.$getRow['contract_number'].'</strong>':'-'}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{substr($getRow['location'],0,15).'...'}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{substr($getRow['customer_name'],0,15).'...'}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">
                            <?php
                                  if ($getRow["GPG_job_type_id"]=="5" or $getRow["GPG_job_type_id"]=="12" or $getRow["GPG_job_type_id"]=="13") { // added
                                    if($getRow['contract_amount']!="" and !is_string($getRow['contract_amount'])){ // added
                                        echo '$'.number_format(($getRow['fixed_price'] != "" ? $getRow['fixed_price'] : ($getRow['nte'] != ""?$getRow['nte']:($getRow['sub_nte']!=""?$getRow['sub_nte']:($getRow['contract_amount']!=""?$getRow['contract_amount']:0)))),2); 
                                      } else { echo '$'.number_format($getRow['contract_amount'],2);
                                    } 
                                  }else 
                                    echo "-";
                            ?>
                          </td>
                          <td data-title=":" style="padding-bottom:8.3px">
                            <?php             
                              $invoiceData = preg_split("/#~#/",$getRow['invoice_data']);
                              if(count(@$invoiceData)>1){
                                echo ($invoiceData[4] > 1 ? "Multiple": ( $invoiceData[2] != "" ? date('m/d/Y',strtotime( $invoiceData[2])) : "-"));
                              }else{ 
                                echo "-";
                              }
                              ?> 
                          </td>
                          <td data-title=":" style="padding-bottom:8.3px">{{isset($invoiceData[1])?'$'.number_format($invoiceData[1],2):"-"}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{isset($invoiceData[3])?'$'.number_format($invoiceData[3],2):"-"}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">
                            <?php  
                            $invAmt = 0;
                              if(count($invoiceData)>1){
                                $invAmt = ($invoiceData[1] - $invoiceData[3]);
                                echo '$'.number_format($invAmt,2);
                              }else{
                                echo '$'.number_format(0,2);
                              }
                            ?>
                          </td>
                          <td data-title=":" style="padding-bottom:8.3px">
                            <?php
                                if(count($invoiceData) > 1){
                                  echo preg_replace("/,/",", ",($invoiceData[4]>1?"Multiple":$invoiceData[0])); 
                                }else{
                                  echo "-";
                                }
                            ?>
                          </td>
                          <td data-title=":" style="padding-bottom:8.3px">{{'$'.number_format($getRow['material_cost'],2)}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{'$'.number_format($getRow['labor_cost'],2)}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{'$'.number_format($getRow['cost_to_dat'],2)}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{isset($getRow['sales_person_name'])?$getRow['sales_person_name']:'-'}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{isset($getRow['estimator_name'])?$getRow['estimator_name']:'-'}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{($getRow['complete']==1?"Completed":"-")}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{'$'.number_format($getRow['AR_on_job'],2)}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{'$'.number_format($getRow['AP_on_job'],2)}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">
                            <?php  $calMarg = $invAmt - $getRow['cost_to_dat']; 
                                echo '$'.number_format($calMarg,2);
                            ?>
                          </td>
                          <td data-title=":" style="padding-bottom:8.3px">
                            <?php
                              $saleCom = 0;
                              if ($calMarg>0) { 
                                $saleCom = ($calMarg*$getRow['sales_commission'])/100;               
                                if ($getRow['sales_commission']>0) {
                                 echo '$'.number_format($saleCom,2)." [".$getRow['sales_commission']."%]"; ?>
                                    <input type="hidden" id="calcSalesComm_{{$getRow['job_num']}}" value="<?php echo round($saleCom,2); ?>" />
                                 <?php } else { 
                   if(strlen($getRow['sales_person_name'])>0)
                     echo "<strong>No Comm. Set</strong>"; 
                   } 
                              }else
                                echo '-';
                            ?>
                          </td>
                          <td data-title=":" style="padding-bottom:8.3px">
                           {{ HTML::link('#myModal2','Edit-', array('data-toggle'=>'modal','job_num'=>$getRow['job_num'],'name'=>'ca_edit', 'id'=>$getRow['id']))}} 
                          {{isset($getRow['commData']['amt'])?'$'.number_format($getRow['commData']['amt'],2):'$0.00'}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{isset($getRow['commData']['cnt'])?($getRow['commData']['cnt']>1?"Multiple":($getRow['commData']['comm_date']!=""?date('d/m/Y',strtotime($getRow['commData']['comm_date'])):"-")):'-'}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{'$'.number_format(round($saleCom,2) - (isset($getRow['commData']['amt'])?round($getRow['commData']['amt'],2):0),2)}}</td>
                         <td data-title=":" style="padding-bottom:8.3px">
                            <?php 
                              $estCom = 0;
                              if ($calMarg>0 && $getRow['sales_person_name']!=$getRow['estimator_name'] && !empty($getRow['estimator_name'])) { 
                              $estCom = ($calMarg*$getRow['estimator_commission'])/100; 
                              if ($getRow['estimator_commission']>0) {
                               echo '$'.number_format($estCom,2)." [".$getRow['estimator_commission']."%]";
                               ?> <input type="hidden" id="calcEstComm_{{$getRow['job_num']}}" value="<?php echo round($estCom,2); ?>" />
                               <?php } else {
                                echo "<strong>No Comm. Set</strong>" ;
                               }
                             }else
                                  echo "-";
                             ?>
                          </td>
                          <td data-title=":" style="padding-bottom:8.3px"> 
                          <!-- modal here-->
                          {{ HTML::link('#myModal','Edit-', array('data-toggle'=>'modal','job_num'=>$getRow['job_num'],'name'=>'edit_ecp', 'id'=>$getRow['id']))}}
                          {{isset($getRow['estCommData']['amt'])?'$'.number_format($getRow['estCommData']['amt'],2):'-'}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{isset($getRow['estCommData']['cnt'])?($getRow['estCommData']['cnt']>1?"Multiple":($getRow['estCommData']['est_comm_date']!=""?date('m/d/Y',strtotime($getRow['estCommData']['est_comm_date'])):"-")):'-'}}</td>
                          <td data-title=":" style="padding-bottom:8.3px">{{'$'.number_format(round($estCom,2) - (isset($getRow['estCommData']['amt'])?round($getRow['estCommData']['amt'],2):'0'),2)}}</td>
                          <td data-title=":" style="padding-bottom:8.3px"><?php if ($calMarg>0) echo '$'.number_format(round($calMarg,2)-(isset($getRow['commData']['amt'])?round($getRow['commData']['amt'],2):'0')- (isset($getRow['estCommData']['amt'])?round($getRow['estCommData']['amt'],2):'0'),2); else echo '-'; ?></td>
                          <td data-title=":" style="padding-bottom:8.3px">
                              @if(isset($files_arr[$getRow['tracking_id']]))
                                {{@$files_arr[$getRow['tracking_id']]}}
                              @else
                              {{'-'}} 
                              @endif
                          </td> 
                  </tr>        
                      @endforeach  
                      </tbody>
                  </table>
                   {{ Form::button('Delete Selected Jobs', array('class' => 'btn btn-danger', 'id'=>'delete_records')) }}
                  <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>GRAND TOTALS</i></b>
                </header>
                </section>
                <section id="no-more-tables"  style="padding:10px;">
                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr><th>Contract Amount</th><th>Invoice Amount</th><th>Tax</th><th>Invoice Amount Net</th><th>Material Costs </th><th>Labor Costs </th><th>COST TO DATE</th><th>Calc Margin</th><th>Sales Commisson</th><th>Sales Comm. Paid</th><th>Sales Comm. Balance</th><th>Estimator Comm.</th><th>Estimate Comm. Paid</th><th> Estimate Comm. Balance</th><th> Net Margin </th></tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>{{'$'.number_format($totalsRow["contract_amount"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["inv_amount"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["tax_amount"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["invoice_amount_net"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["mat_cost"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["lab_cost"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["cost_to_date"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["totCalcMatgin"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["totSalesComm"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["totSalesCommPaid"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["totSalesComm"]-$totalsRow["totSalesCommPaid"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["totEstComm"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["totEstCommPaid"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["totEstComm"]-$totalsRow["totEstCommPaid"],2)}}</td>
                            <td>{{'$'.number_format($totalsRow["totNetMargin"],2)}}</td>
                          </tr>
                        </tbody>
                  </table>
                 {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
              </section>
                        <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_est_comm','url'=>route('job/postEstCommAmt'),'files'=>true, 'method' => 'post')) }}
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Est. Commission Paid: $<b id="comm_paid"></b></h4>
                                              <input type="hidden" value="" name="serv_id" id="serv_id">
                                              <input type="hidden" value="" name="job_num" id="job_num">
                                          </div>
                                          <div class="modal-body">
                                             <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                              <tbody>
                                                <tr>
                                                  <th>Est. Commission Amt:</th><td>{{Form::text('est_com_amt','0', array('class' => 'form-control', 'id' => 'est_com_amt')) }}</td>
                                                </tr>
                                                <tr>
                                                  <th>Est. Commission Date:</th><td>{{Form::text('est_com_date',date('Y-m-d'), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'est_com_date')) }}</td>
                                                </tr>
                                              </tbody>
                                             </table> 
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'submit_com_paid'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                       {{ Form::close() }}
                                      </div>
                                  </div>
                              </div>
                          </div>
                        <!-- modal -->
                <!-- Modal#2 -->
                          <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_comm_amt','url'=>route('job/postCommAmt'),'files'=>true, 'method' => 'post')) }}
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Est. Commission Paid: $<b id="paid_comm"></b></h4>
                                              <input type="hidden" value="" name="serv_id" id="Newjob_id">
                                              <input type="hidden" value="" name="job_num" id="Newjob_num">
                                          </div>
                                          <div class="modal-body">
                                             <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                              <tbody>
                                                <tr>
                                                  <th>Commission Amt:</th><td>{{Form::text('com_amt','0', array('class' => 'form-control', 'id' => 'com_amt')) }}</td>
                                                </tr>
                                                <tr>
                                                  <th>Commission Date:</th><td>{{Form::text('com_date',date('Y-m-d'), array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'com_date')) }}</td>
                                                </tr>
                                              </tbody>
                                             </table> 
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal','id'=>'com_paid_submit'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                       {{ Form::close() }}
                                      </div>
                                  </div>
                              </div>
                          </div>
                <!-- modal#2 -->
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
              $('#InvoiceSDate').val("");
              $('#InvoiceEDate').val("");
              $('#JobWonSDate').val("");
              $('#JobWonEDate').val("");
              $('#EqpOrderedSDate').val("");
              $('#EqpOrderedEDate').val("");
              $('#EqpEngagedSDate').val("");
              $('#EqpEngagedEDate').val("");
              $('#PermitOrderedSDate').val("");
              $('#PermitOrderedEDate').val("");
              $('#PermitExpectedSDate').val("");
              $('#PermitExpectedEDate').val("");
              $('#CompletedSDate').val("");
              $('#CompletedEDate').val("");
              $('#CreatedSDate').val("");
              $('#CreatedEDate').val("");
              $('#SJobNumber').val("");
              $('#EJobNumber').val("");
              $('#InvNumber').val("");

              $('#optCustomer').val("");
              $('#optEmployee').val("");
              $('#optEstimator').val("");
              $('#optJobCostStatus').val("");
              $('#optJobStatus').val("");
              $('#optJobHaving').val("");
              $('#elecJobType').val("");
              $('#optJobAccount').val("");
              $('#optTechAtt').val("");
      });
    
      $("#delete_records").click(function(){
          checkboxes = document.getElementsByName('delChk[]');
          var count =0;
          for(var i=0, n=checkboxes.length;i<n;i++) {
              if (checkboxes[i].checked == 1){
                count++;
              }
          }
          if (count > 0){
            var result = confirm("Are you sure! you want to delete this/these: "+count+" jobs ....?");
          }else{
              alert("No Item Selected");            
          }
          if (result){         
            for(var i=0, n=checkboxes.length;i<n;i++) {
                if (checkboxes[i].checked == 1){
                    $.ajax({
                        url: "{{URL('ajax/deleteJobs')}}",
                        data: {
                          'id' : $(checkboxes[i]).val()
                        },
                        success: function (data) {
                          if (data == 1){     
                            alert("Deleted Successfully!");
                            location.reload();
                          }
                          else
                            alert('Error while deleting record(s)!')
                      },
                    });
                }
            }
          }
      });
$('a[name=ca_edit]').click(function(){
 var id = $(this).attr('id');
 var job_num = $(this).attr('job_num');
 $.ajax({
      url: "{{URL('ajax/getCommAmt')}}",
      data: {
      'id' : id
    },
      success: function (data) {
        $('#paid_comm').html(data);
        $('#Newjob_id').val(id);
        $('#Newjob_num').val(job_num);
    },
  });
});

$('a[name=edit_ecp]').click(function(){
 var id = $(this).attr('id');
 var job_num = $(this).attr('job_num');
 $.ajax({
      url: "{{URL('ajax/getEstCommAmt')}}",
      data: {
      'id' : id
    },
      success: function (data) {
        $('#comm_paid').html(data);
        $('#serv_id').val(id);
        $('#job_num').val(job_num);
    },
  });

});
$('#submit_com_paid').click(function(){
  $('#submit_est_comm').submit();
});
$('#com_paid_submit').click(function(){
  $('#submit_comm_amt').submit();
});
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop