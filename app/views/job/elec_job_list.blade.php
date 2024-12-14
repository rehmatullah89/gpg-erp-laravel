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
                 {{$page_heading['main_heading']}} 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>{{$page_heading['sub_heading']}} </i></b>
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
                            <th style="text-align:center;" data-title="">Edit &nbsp;
                            {{ Form::checkbox('checkThis', '','', array('class' => 'input-group', 'style'=>'display:inline;','onclick'=>'toggle(this)')) }}
                            </th>
                            <th style="text-align:center;" data-title="">Invoice</th>
                            <th style="text-align:center;" data-title="">Comp. Date</th>
                            <th style="text-align:center;" data-title="">Closing Date</th>
                            <th style="text-align:center;" data-title="">Created Date</th>
                            <th style="text-align:center;" data-title="">Schedule Date</th>
                            <th style="text-align:center;" data-title="">Job Number</th>
                            <th style="text-align:center;" data-title="">Job Type</th>
                            <th style="text-align:center;" data-title="">Job Name/Location</th>
                            <th style="text-align:center;" data-title="">Technicians</th>
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
                            <th style="text-align:center;" data-title="">Download</th>
                          </tr>
                        </thead>
                      <tbody>
                        @foreach($query_data as $data)
                          <tr>
                            <td data-title="Del:" style="padding-bottom:8.2px">{{ Form::checkbox('delChk[]',$data['id'],'', array('id'=>'delChk[]','class' => 'input-group')) }}</td>
                            <td data-title="Edit:"  style="padding-bottom:8.2px">{{ Form::checkbox('editChk[]',$data['job_num'],'', array('id'=>'editChk[]','class' => 'input-group')) }}</td>
                            <td data-title=""  style="padding-bottom:8.2px">
                            
                            {{ HTML::link('job/getPdffile/'.$data['id'].'', 'pdf' , array('class'=>'btn btn-danger btn-xs'))}} 
                            </td>
                            <td data-title="Comp. Date:"  style="padding-bottom:8.2px">
                              {{(($data['date_completion']!='-' && $data['date_completion']!='0000-00-00' && !empty($data['date_completion']))? date('m/d/Y',strtotime($data['date_completion'])): '-')}}  
                            </td>
                            <td data-title="Closing Date:"  style="padding-bottom:8.2px">
                              {{(($data['closing_date'] != '-' && $data['closing_date'] != '0000-00-00' && !empty($data['closing_date']))? date('m/d/Y',strtotime($data['closing_date'])): '-')}}  
                            </td>
                            <td data-title="Date Created:"  style="padding-bottom:8.2px">
                              {{(($data['created_on']!=''  && $data['created_on'] != '0000-00-00' && !empty($data['created_on']))? date('m/d/Y',strtotime($data['created_on'])): '-')}}  
                            </td>
                            <td data-title="Schedule Date:"  style="padding-bottom:8.2px">
                              {{(($data['schedule_date']!='-'  && $data['created_on'] != '0000-00-00' && !empty($data['created_on']))? date('m/d/Y',strtotime($data['schedule_date'])): '-')}}
                            </td>

                            <td data-title="Job Num:"  style="padding-bottom:8.2px">
                              {{ HTML::link('job/job_form/'.$data['id'].'/'.$data['job_num'].'', $data['job_num'] , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$data['id'],'j_num'=>$data['job_num']))}} 
                            </td>
                            <td data-title="Job Type:"  style="padding-bottom:8.2px">{{$data['elec_job_type']}}</td>
                            <td data-title="Location:"  style="padding-bottom:8.2px">{{$data['location']}}</td>
                            <td data-title="Technician:" title="{{$data['technicians']}}">{{$data['technicians']}}</td>
                            <td data-title="customer Name:"  style="padding-bottom:8.2px">{{$data['customer_name']}}</td>
                            <td data-title="Contract Amt:"  style="padding-bottom:8.2px">{{$data['contract_amount']}}</td>
                            <td data-title="Invoice Date:"  style="padding-bottom:8.2px">
                            @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                                <?php $val_this = ($invoiceData[4]>1?'Multiple':($invoiceData[2]!=''?date('m/d/Y',strtotime($invoiceData[2])):'-')); ?>
                              {{ HTML::link('#myModal', $val_this , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}}  
                              @else
                                {{ HTML::link('#myModal', '--' , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}}  
                              @endif
                            @endif
                            </td>
                            <td data-title="Inv'd Amount:"  style="padding-bottom:8.2px">
                            @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                                <?php $val_this = '$'.number_format($invoiceData[1],2);?>
                                {{ HTML::link('#myModal', $val_this , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}} 
                              @else
                                {{ HTML::link('#myModal', '$0.00' , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}}
                              @endif
                            @endif
                            </td>
                            <td data-title="Tax:"  style="padding-bottom:8.2px">
                            @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                                <?php $val_this =  '$'.number_format($invoiceData[3],2); ?> 
                                {{ HTML::link('#myModal', $val_this , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}}
                              @else
                                {{ HTML::link('#myModal', '$0.00' , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}}
                              @endif
                            @endif
                            </td>
                            <td data-title="Inv'd Amount Net:"  style="padding-bottom:8.2px">
                            @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                                 <?php $val_this =  '$'.number_format($invoiceData[1] - $invoiceData[3],2); ?> 
                                {{ HTML::link('#myModal', $val_this , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}}
                              @else
                                {{ HTML::link('#myModal', '$0.00' , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}}
                              @endif
                            @endif 
                            </td>
                            <td data-title="Inv Data:"  style="padding-bottom:8.2px">
                             @if(!empty($data['invoice_data']))
                              <?php $invoiceData = explode("#~#",$data['invoice_data']); ?>
                              @if($invoiceData[0] != '-')
                              <?php $val_this =  preg_replace("/,/",", ",($invoiceData[4]>1?"Multiple":$invoiceData[0])); ?> 
                                {{ HTML::link('#myModal', $val_this , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}}
                              @else
                                {{ HTML::link('#myModal', '--' , array('data-toggle'=>'modal','job_Nm'=>$data['job_num'],'name'=>'modalInfo', 'id'=>$data['id']))}}
                              @endif
                            @endif  
                            </td>
                            <td data-title="Material Cost:"  style="padding-bottom:8.2px">
                             @if($data['material_cost'] != '-')
                                {{'$'.number_format($data['material_cost'],2)}}
                              @else
                                {{'$0.00'}}
                              @endif 
                            </td>
                            <td data-title="Labor Cost:"  style="padding-bottom:8.2px">
                              @if($data['labor_cost'] != '-')
                                {{'$'.number_format($data['labor_cost'],2)}}
                              @else
                                {{'$0.00'}}
                              @endif
                            </td>
                            <td data-title="Cost to Date:"  style="padding-bottom:8.2px">
                               @if($data['labor_cost'] != '-')
                                {{'$'.number_format($data['cost_to_dat'],2)}}
                              @else
                                {{'$0.00'}}
                              @endif
                            </td>
                            <td  style="padding-bottom:8.2px" data-title="Sales Person:">{{$data['sales_person_name']}}</td>
                            <td  style="padding-bottom:8.2px" data-title="Sales Person:">{{$data['estimator_name']}}</td>
                            <td  style="padding-bottom:8.2px" data-title="Sales Person:">{{($data['complete']==1?"Completed":"-")}}</td>
                            <td  style="padding-bottom:8.2px" data-title="Attachments:">
                            @if(isset($files_arr[$data['tracking_id']]))
                              {{$files_arr[$data['tracking_id']]}}
                            @else
                              {{'-'}}  
                            @endif
                            </td>
                          </tr>
                        @endforeach  
                      </tbody>
                  </table>
                  {{HTML::link('#myModal2', 'Update Selected Jobs' , array('class' => 'btn btn-info','data-toggle'=>'modal'))}}
                  {{ HTML::link("job/excelExport?table=$uriSegment&".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}                   
                   {{ Form::button('Delete Selected Jobs', array('class' => 'btn btn-danger', 'id'=>'delete_records')) }}
                  <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>GRAND TOTALS</i></b>
                </header>
                </section>
                <section id="no-more-tables"  style="padding:10px;">
                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr><th>Contract Amount</th><th>Invoice Amount</th><th>Tax</th><th>Invoice Amount Net</th><th>Material Costs </th><th>Labor Costs </th><th>COST TO DATE</th></tr>
                        </thead>
                        <tbody>
                        @foreach($totals_qry as $data2)
                          {{"<tr><td data-title='Contract Amount:'>".'$'.number_format($data2->contract_amount,2)."</td><td data-title='Invoice Amount:'>".'$'.number_format($data2->inv_amount,2)."</td><td data-title='Tax:'>".'$'.number_format($data2->tax_amount,2)."</td><td data-title='Invoice Amount Net:'>".'$'.number_format($data2->invoice_amount_net,2)."</td><td data-title='Material Costs:'>".'$'.number_format($data2->mat_cost,2)."</td><td data-title='Labor Costs:'>".'$'.number_format($data2->lab_cost,2)."</td><td data-title='COST TO DATE:'>".'$'.number_format($data2->cost_to_date,2)."</td></tr>"}}
                        @endforeach  
                        </tbody>
                  </table>
                  {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
              </section>
                        <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Invoice Info:<b id="JobNum"></b></h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group" id="display_invoice_info">
                                           
                                              </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                              </div>
                          </div>
                        <!-- modal -->
                      <!-- Modal#2 -->
                        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Edit Jobs</h4>
                                          </div>
                                          <div class="modal-body">
                                             <div class="form-group">
                                                <!-- ...code here.... -->
                                              <section id="no-more-tables"  style="padding:10px;">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                <tbody class="cf">
                                                 <tr><th>Technicians</th><td>{{Form::select('technician[]', $tech_arr, null, ['multiple','class'=>'form-control','id'=>'technician_id'])}} </td></tr>
                                                 <tr><th>Scheduled Date</th><td>{{ Form::text('date_scheduled','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'date_scheduled')) }}</td></tr>
                                                 <tr><th>Status</th><td>
                                                   <div class="radio">
                                                      <label>
                                                      {{ Form::radio('job_status', 2, false, ['id' => 'radio1']) }} No Change
                                                      </label>
                                                  </div>
                                                  <div class="radio">
                                                      <label>
                                                      {{ Form::radio('job_status', 1, false, ['id' => 'radio2']) }} Completed
                                                      </label>
                                                  </div>
                                                  <div class="radio">
                                                      <label>
                                                      {{ Form::radio('job_status', 0, false, ['id' => 'radio3']) }} Incomplete
                                                      </label>
                                                  </div>
                                                 </td></tr>
                                                 <tr><th id="label_onChange"></th><td id="value_onChange"></td></tr>
                                                  </tbody>
                                                </table>
                                              </section> 
                                              </div>
                                          </div>
                                      <div class="btn-group" style="padding:20px;">
                                        {{Form::submit('Submit', array('class' => 'btn btn-success', 'id'=>'submit_job_updates','data-dismiss'=>'modal'))}}
                                        {{Form::button('Reset', array('class' => 'btn btn-warning','id'=>'reset_form'))}}
                                        {{Form::button('Cancel', array('class' => 'btn btn-primary','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                              </div>
                          </div>
                      <!-- modal # 2 end-->      
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
      $('a[name=modalInfo]').on('click',function(){
        $('#JobNum').html($(this).attr('job_Nm'));
        $.ajax({
            url: "{{URL('ajax/getInvoiceInfo')}}",
              data: {
               'job_id' : $(this).attr('id')
              },
            success: function (data) {
              $('#display_invoice_info').html(data);
            },
        });
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

      $("#submit_job_updates").click(function(){
        checkboxes = document.getElementsByName('editChk[]');
        var count2 =0;
          for(var i=0, n=checkboxes.length;i<n;i++) {
              if (checkboxes[i].checked == 1){
                count2++;
              }
          }
          if (count2 > 0){
            var result = confirm("Are you sure! you want to Edit this(these): "+count2+" job(s) ....?");
          }else{
              alert("No Item Selected to Update");            
          }
          if (result){
            for(var i=0, n=checkboxes.length;i<n;i++) {
                if (checkboxes[i].checked == 1){
                  tech_ids = "";
                  nodeList = document.getElementById("technician_id");
                  for (var index = 0; index < nodeList.length; index++) {
                    if (nodeList.options[index].selected) 
                        tech_ids += (nodeList.options[index].value)+",";
                  }
                  if(tech_ids == "")
                    tech_ids = 0;
                  var date_sched = $('#date_scheduled').val();
                  if (date_sched == "")
                    date_sched = 0;
                  var date_compl = $('#date_completed').val();
                  if (date_compl == "")
                    date_compl = 0;
                  var job_stat = $('input[type=radio][name=job_status]:checked').val();
                  if (job_stat == "")
                    job_stat = 2;
                  $.ajax({
                      url: "{{URL('ajax/updateJobs')}}",
                        data: {
                          'job_num' : $(checkboxes[i]).val(),
                          'technecian' : tech_ids,
                          'date_schd' : date_sched,
                          'status' : job_stat,
                          'date_comp': date_compl
                        },
                        success: function (data) {
                          if (data == 1){     
                            alert("Updated Successfully!");
                            location.reload();
                          }
                          else
                            alert('Error while updating record(s)!')
                      },
                  });
              }
            }
            $('#reset_form').click();
          }
      });
      
      $('input[type=radio][name=job_status]').change(function(){
          if ($(this).attr("id") == 'radio2'){
            $('#label_onChange').html("Completed Date:");
            $('#value_onChange').html('<input class="form-control form-control-inline input-medium default-date-picker"  size="16" name="date_completed" id="date_completed" type="text" value="" />');
            $('.default-date-picker').datepicker({
                format: 'yyyy-mm-dd'
            });
          }else{
            $('#label_onChange').html("");
            $('#value_onChange').html("");
          }

      });

      $('#reset_form').click(function(){
        $('#technician_id').val("");
        $('#date_scheduled').val("");
        $('input[type=radio][name=job_status]:checked').val("");
        $('#date_completed').val("");
      });

      function toggle(source) {
            checkboxes = document.getElementsByName('editChk[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
            }
      }

    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop