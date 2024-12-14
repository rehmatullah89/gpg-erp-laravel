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
      <!-- page start-->
          <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
                 SERVICE JOBS LIST 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>SERVICE JOBS LISTING</i></b>
                </header>
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('job/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:8.7px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:8.7px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Start Date Start:">
                                    {{Form::label('SDate2', 'Start Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SDate2','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate2')) }}
                                  </td>
                                  <td data-title="Invoice Date End:">
                                    {{Form::label('EDate2', 'Invoice Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EDate2','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate2')) }}
                                  </td>
                                  <td data-title="Date Job Won Start:">
                                    {{Form::label('JobWonSDate', 'Date Job Won Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JobWonSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobWonSDate')) }}
                                  </td>
                                  <td data-title="Date Job Won End:">
                                    {{Form::label('JobWonEDate', 'Date Job Won End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('JobWonEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'JobWonEDate')) }}
                                  </td>
                                  <td data-title="Invoice Date Start:">
                                    {{Form::label('InvoiceSDate', 'Invoice Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceSDate')) }}
                                  </td>
                                  <td data-title="Invoice Date End:">
                                    {{Form::label('InvoiceEDate', 'Invoice Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceEDate')) }}
                                  </td>
                                  <td data-title="Date Parts Ordered Start:">
                                    {{Form::label('PartsOrderedSDate', 'Date Parts Ordered Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PartsOrderedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsOrderedSDate')) }}
                                  </td>
                                  <td data-title="Date Parts Ordered End:">
                                    {{Form::label('PartsOrderedEDate', 'Date Parts Ordered End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PartsOrderedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsOrderedEDate')) }}
                                  </td>
                                </tr>
                                <tr>
                                  <td data-title="Date Parts Recieved Start:">
                                    {{Form::label('PartsRecievedSDate', 'Date Parts Recieved Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PartsRecievedSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsRecievedSDate')) }}
                                  </td>
                                  <td data-title="Date Parts Recieved End:">
                                    {{Form::label('PartsRecievedEDate', 'Date Parts Recieved End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PartsRecievedEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsRecievedEDate')) }}
                                  </td>
                                  <td data-title="Date Parts Scheduled Start:">
                                    {{Form::label('PartsScheduledSDate', 'Date Parts Scheduled Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PartsScheduledSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsScheduledSDate')) }}
                                  </td>
                                  <td data-title="Date Parts Scheduled End:">
                                    {{Form::label('PartsScheduledEDate', 'Date Parts Scheduled End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('PartsScheduledEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'PartsScheduledEDate')) }}
                                  </td>
                                  <td data-title="Date Scheduled Start:">
                                    {{Form::label('ScheduledSDate', 'Date  Scheduled Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('ScheduledSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ScheduledSDate')) }}
                                  </td>
                                  <td data-title="Date Scheduled End:">
                                    {{Form::label('ScheduledEDate', 'Date Scheduled End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('ScheduledEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ScheduledEDate')) }}
                                  </td>
                                  <td data-title="Job Number Start:">
                                    {{Form::label('SJobNumber', 'Job Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SJobNumber','', array('class' => 'form-control', 'id' => 'SJobNumber')) }}
                                  </td>
                                  <td data-title="Job Number End (optional):">
                                    {{Form::label('EJobNumber', 'Job Number End(opt.):', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EJobNumber','', array('class' => 'form-control', 'id' => 'EJobNumber')) }}
                                  </td>
                                </tr>
                                <tr>
                                  <td data-title="Order By:">
                                    {{Form::label('sort_order', 'Order By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('sort_order', array('job_created_date'=>'Job Created Date','customer'=>'Customer','recommendation_days'=>'Recommendation Days'), null, ['id' => 'sort_order', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Order Type:">
                                    {{Form::label('sort_type', 'Order By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('sort_type', array('ASC'=>'Ascending','DESC'=>'Descending'), null, ['id' => 'sort_type', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Job Cost Status:">
                                    {{Form::label('optJobCostStatus', 'Job Cost Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobCostStatus', array(''=>'ALL','no_labor' => 'Have no Labor Cost', 'no_mat' => 'Have no Material Cost', 'no_both' => 'Have no Labor and Material Cost'), null, ['id' => 'optJobCostStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="National Account/Subcontractor:" >
                                    {{Form::label('optJobAccount', 'National Account/Subcontractor:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobAccount', array(''=>'ALL',"national_account"=>"National Account Jobs","sub_contractor"=>"Subcontractor Jobs"), null, ['id' => 'optJobAccount', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Jobs Having:">
                                    {{Form::label('optJobHaving', 'Jobs Having:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobHaving', array(''=>'ALL',"po"=>"PO Records","cost"=>"Material Cost Records","timesheet"=>"Timesheet Records"), null, ['id' => 'optJobHaving', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Recommendation/Quote:">
                                    {{Form::label('optRecQuote', 'Recommendation/Quote:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optRecQuote', array("R"=>"Job(s) have Recommendation","Q"=>"Job(s) have Quote","noQuote"=>"Job(s) don't have Quote","quoteNoRec"=>"Job(s) have Recommendations with no Quote","recNoQuote"=>"Job(s) have Quotes with no Recommendation"), null, ['id' => 'optRecQuote', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Won Job(s):">
                                    {{Form::label('optWonJob', 'Won Job(s):', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optWonJob', array("R"=>"Job(s) have Recommendation","Q"=>"Job(s) have Quote","noQuote"=>"Job(s) don't have Quote","quoteNoRec"=>"Job(s) have Recommendations with no Quote","recNoQuote"=>"Job(s) have Quotes with no Recommendation"), null, ['id' => 'optWonJob', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Job Status:">
                                    {{Form::label('optJobStatus', 'Job Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobStatus', array(''=>'ALL',"completed"=>"Have been Completed","notcompleted"=>"Jobs Not Completed","invoiced"=>"Have been Invoiced","comp_inv"=>"Have been Invoiced and Completed","not_comp_inv"=>"Have been Invoiced but Not Completed","not_invoiced"=>"Have Not been Invoiced","completed_not_invoiced"=>"Completed but Have Not been Invoiced","completed_not_closed"=>"Completed Not Closed","closed_not_completed"=>"Closed Not Completed"), null, ['id' => 'optJobStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                </tr>
                                <tr>
                                  <td data-title="Customer:">
                                    {{Form::label('optCustomer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optCustomer', $cust_arr, null, ['id' => 'optCustomer', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Sales Person:">
                                    {{Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optEmployee', $salesp_arr, null, ['id' => 'optEmployee', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Technician:">
                                    {{Form::label('optTech', 'Technician:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optTech', $technicians, null, ['id' => 'optTech', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Job Attached With Flist:" >
                                    {{Form::label('optAttachedFlist', 'Job Attached With Flist:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optAttachedFlist', array("attached"=>"Job(s) Attaced with Flist","notAttached"=>"Job(s) not Attaced with Flist"), null, ['id' => 'optAttachedFlist', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Assigned To::">
                                    {{Form::label('dropEmployee', 'Assigned To::', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('dropEmployee', $salesp_arr, null, ['id' => 'dropEmployee', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Rec. Category:">
                                    {{Form::label('RecCategoryValue', 'Rec. Category:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('RecCategoryValue', array(''=>'ALL','1'=>'Basic','2'=>'Intermediate','3'=>'Urgent'), null, ['id' => 'RecCategoryValue', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Cleared Reason:">
                                    {{Form::label('optClearedReason', 'Cleared Reason:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optClearedReason', $technicians, null, ['id' => 'optClearedReason', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Technicians Attached:">
                                    {{Form::label('optTechAtt', 'Technicians Attached:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optTechAtt', array(''=>'ALL',"both"=>"Single / Multiple","single"=>"Single Technician","multiple"=>"Multiple Technicians"), null, ['id' => 'optTechAtt', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                </tr>  
                                <tr>
                                  <td data-title="Ignore Boxes:" colspan="2">
                                    {{ Form::checkbox('ignoreCostDate','1','', array('id'=>'ignoreCostDate','class' => 'input-group','style'=>'display:inline;')) }}
                                    Ignore Date stamp  on Material Cost and Labor Cost.<br />
                                    {{ Form::checkbox('ignoreInvoiceDate','1','', array('id'=>'ignoreInvoiceDate','class' => 'input-group','style'=>'display:inline;')) }}
                                    Ignore Date stamp  on Invoice Amount.
                                  </td>
                                  <td data-title="Contract Number Start:">
                                    {{Form::label('SContractNumber', 'Contract Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SContractNumber','', array('class' => 'form-control', 'id' => 'SContractNumber')) }}
                                  </td>
                                  <td data-title="Contract Number End (opt.):">
                                    {{Form::label('EContractNumber', 'Contract Number End (opt.):', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EContractNumber','', array('class' => 'form-control', 'id' => 'EContractNumber')) }}
                                  </td>
                                  <td data-title="Invoice#:">
                                    {{Form::label('InvNumber', 'Invoice#:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvNumber','', array('class' => 'form-control', 'id' => 'InvNumber')) }}
                                  </td>
                                  <td data-title="Regarding:">
                                    {{Form::label('optRegarding', 'Regarding:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('optRegarding','', array('class' => 'form-control', 'id' => 'optRegarding')) }}
                                  </td>
                                  <td data-title="Actions" colspan="2">
                                    {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
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
              <div class="btn-group" style="padding:20px;">
                 {{ HTML::link('job/service_customer_view/', 'Customer View' , array('target'=>'_blank','class'=>'btn btn-success'))}}
                 {{ HTML::link('job/contract_view/', 'Contract View' , array('target'=>'_blank','class'=>'btn btn-primary'))}}
              </div>
              <div class="adv-table">
              <section id="flip-scroll" >
                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;" data-title="">Delete</th>
                            <th style="text-align:center;" data-title="">Edit &nbsp;
                            {{ Form::checkbox('checkThis', '','', array('class' => 'input-group', 'style'=>'display:inline;','onclick'=>'toggle(this)')) }}
                            </th>
                            <th style="text-align:center;" data-title="">Cleared Reason</th>
                            <th style="text-align:center;" data-title="">Cmpd. Date</th>
                            <th style="text-align:center;" data-title="">Closing Date</th>
                            <th style="text-align:center;" data-title="">Created Date</th>
                            <th style="text-align:center;" data-title="">Schedule Date</th>
                            <th style="text-align:center;" data-title="">FSW Status</th>
                            <th style="text-align:center;" data-title="">Technicians</th>
                            <th style="text-align:center;" data-title="">Contract Number</th>
                            <th style="text-align:center;" data-title="">Job Number</th>
                            <th style="text-align:center;" data-title="">Regarding</th>
                            <th style="text-align:center;" data-title="">Allocated Hours</th>
                            <th style="text-align:center;" data-title="">Company</th>
                            <th style="text-align:center;" data-title="">Location</th>
                            <th style="text-align:center;" data-title="">Contract Amount</th>
                            <th style="text-align:center;" data-title="">Invoice Date</th>
                            <th style="text-align:center;" data-title="">Inv'd Amount</th>
                            <th style="text-align:center;" data-title="">Tax</th>
                            <th style="text-align:center;" data-title="">Inv'd Amount Net</th>
                            <th style="text-align:center;" data-title="">Invoice#</th>
                            <th style="text-align:center;" data-title="">Material Cost</th>
                            <th style="text-align:center;" data-title="">Labor Cost</th>
                            <th style="text-align:center;" data-title="">Cost to Date</th>
                            <th style="text-align:center;" data-title="">Address</th>
                            <th style="text-align:center;" data-title="">Zip</th>
                            <th style="text-align:center;" data-title="">State</th>
                            <th style="text-align:center;" data-title="">City</th>
                            <th style="text-align:center;" data-title="">Scheduled With</th>
                            <th style="text-align:center;" data-title="">Contact Ph./ext</th>
                            <th style="text-align:center;" data-title="">Special Instructions</th>
                            <th style="text-align:center;" data-title="">Assigned To</th>
                            <th style="text-align:center;" data-title="">Rec. Doc Upload</th>
                            <th style="text-align:center;" data-title="">Rec. Category</th>
                            <th style="text-align:center;" data-title="">Rec. Upload Date</th>
                            <th style="text-align:center;" data-title="">Quote Upload</th>
                            <th style="text-align:center;" data-title="">Quote Date Upload</th>
                            <th style="text-align:center;" data-title="">Rec. Days</th>
                            <th style="text-align:center;" data-title="">Date Job Won</th>
                            <th style="text-align:center;" data-title="">Won Days</th>
                            <th style="text-align:center;" data-title="">Date Parts Ordered</th>
                            <th style="text-align:center;" data-title="">Days Ordered   </th>
                            <th style="text-align:center;" data-title="">Date Parts Received</th>
                            <th style="text-align:center;" data-title="">Days Received</th>
                            <th style="text-align:center;" data-title="">Date Job Scheduled For</th>
                            <th style="text-align:center;" data-title="">Days Job Scheduled</th>
                            <th style="text-align:center;" data-title="">Attach Job Num</th>
                            <th style="text-align:center;" data-title="">Notes</th>
                          </tr>
                        </thead>
                      <tbody>
                        @foreach($query_data as $getRow)
                          <tr>
                            <td data-title="Del:" style="padding-bottom:8.7px">{{ Form::checkbox('delChk[]',$getRow['id'],'', array('id'=>'delChk[]','class' => 'input-group')) }}</td>
                            <td data-title="Edit:"  style="padding-bottom:8.7px">{{ Form::checkbox('editChk[]',$getRow['job_num'],'', array('id'=>'editChk[]','class' => 'input-group')) }}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{($getRow['cleared_reason'])?$getRow['cleared_reason']:'-'}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{(!empty($getRow['date_completion'])?date('m/d/Y',strtotime($getRow['date_completion'])):'-')}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{(!empty($getRow['closing_date'])?date('m/d/Y',strtotime($getRow['closing_date'])):'-')}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{($getRow['created_on']!=""?date('m/d/Y',strtotime($getRow['created_on'])):"-")}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{($getRow['schedule_date']!=""?date('m/d/Y',strtotime($getRow['schedule_date'])):"-")}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{Form::select('fsw_drop', array(''=>'-')+$FSWStatusArray, $getRow['fws_status'], ['class'=>'form-control','id'=>'fsw_drop','fsw_id'=>$getRow['id']])}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{$getRow['technicians_str']}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{isset($getRow['contract_number'])?$getRow['contract_number']:'-'}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{ HTML::link('job/job_form/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}} </td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{substr($getRow['task'],0,30)."..."}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php 
                                if(preg_match('/QT/',$getRow['job_num'])) {
                                  $QTallocatedHours0 = DB::select(DB::raw("SELECT (SUM(shop)+SUM(labor)+SUM(lbt)+SUM(ot)+SUM(sub_con)) AS total_hr FROM gpg_field_service_work_labor,gpg_field_service_work WHERE gpg_field_service_work_labor.gpg_field_service_work_id = gpg_field_service_work.id AND gpg_field_service_work.GPG_attach_job_num = '".$getRow['job_num']."' AND (TYPE = 'A' OR TYPE = 'S')")); 
                                  if (isset($QTallocatedHours0[0]->total_hr))
                                    $QTallocatedHours = $QTallocatedHours0[0]->total_hr;
                                  else
                                    $QTallocatedHours = 0;
                                  echo ($QTallocatedHours)?$QTallocatedHours:"-";
                                }     
                                elseif(preg_match('/PM/',$getRow['job_num'])) {
                                  if(isset($AllocatedHoursArray[strtolower($getRow['task'])])){
                                      echo ($AllocatedHoursArray[strtolower($getRow['task'])])?$AllocatedHoursArray[strtolower($getRow['task'])]:"-";
                                  }else{
                                    echo "-";
                                  }
                                }else{
                                    echo '-';
                                }
                              ?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{substr($getRow['customer_name'],0,30)."..."}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{substr($getRow['location'],0,30)."..."}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{'$'.number_format(isset($getRow['contract_amount'])?$getRow['contract_amount']:0,2)}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                            <?php
                              $invoiceData = array();
                              $invoiceData = preg_split("/#~#/",$getRow['invoice_data']);
                              if(isset($invoiceData[4]) && isset($invoiceData[2])){
                                echo (@$invoiceData[4]>1?"Multiple":(@$invoiceData[2]!=""?date('m/d/Y',strtotime(@$invoiceData[2])):"-"));
                              }else{
                                echo "-";
                              }
                            ?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php
                                if(isset($invoiceData[1]) && !empty($invoiceData[1])){
                                   echo '$'.number_format($invoiceData[1],2);
                                }else{
                                   echo "-";
                                }
                              ?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php 
                                if(isset($invoiceData[3]) && !empty($invoiceData[3])){
                                  echo '$'.number_format($invoiceData[3],2);
                              }else{
                                echo "-";
                              }
                              ?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php
                                $invAmt=""; //new defined
                                if(isset($invoiceData[1]) && isset($invoiceData[3])){
                                  $invAmt = ($invoiceData[1] - $invoiceData[3]);
                                  echo '$'.number_format($invAmt,2);  
                                }else{
                                  echo "-";
                                }
                               ?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php
                                if(isset($invoiceData[4]) && isset($invoiceData[0])){
                                  echo preg_replace("/,/",", ",($invoiceData[4]>1?"Multiple":$invoiceData[0]));
                                }else
                                  echo "-";
                                ?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{'$'.number_format($getRow['material_cost'],2)}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{'$'.number_format($getRow['labor_cost'],2)}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{'$'.number_format($getRow['cost_to_dat'],2)}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{substr($getRow['address1'],0,25)."..."}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{substr($getRow['zip'],0,25)."..."}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{isset($getRow['state']) && !empty($getRow['state'])?$getRow['state']:'-'}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{isset($getRow['city']) && !empty($getRow['city'])?$getRow['city']:'-'}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{isset($getRow['sales_person_name'])?$getRow['sales_person_name']:'-'}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{isset($getRow['phone']) && !empty($getRow['phone'])?$getRow['phone']:'-'}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">{{substr($getRow['sub_task'],0,25)."..."}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                            <a href="#myModal5" name="manage_attaches" id="{{$getRow['id']}}" field="date_parts_ordered" class="btn btn-link btn-xs" data-toggle='modal' style='margin:-8.7px;'><i class="fa fa-pencil-square-o"></i></a> -{{$getRow['emp_name']}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px"><a href="#myModal5" name="manage_attaches" id="{{$getRow['id']}}" field="date_parts_ordered" class="btn btn-link btn-xs" data-toggle='modal' style='margin:-8.7px;'><i class="fa fa-pencil-square-o"></i></a></td>
                            <td data-title=":"  style="padding-bottom:8.7px">-{{$getRow['RecCategory']}}</td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php 
                                $recDate = "";          
                                if(isset($docVar[1])){
                                  $recDate = $docVar[1]; 
                                }else
                                  echo "-";
                              ?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px"><a href="#myModal5" name="manage_attaches" id="{{$getRow['id']}}" field="date_parts_ordered" class="btn btn-link btn-xs" data-toggle='modal' style='margin:-8.7px;'><i class="fa fa-pencil-square-o"></i></a></td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php 
                              $quoteDate = '';
                              if(isset($docVar[1])){
                                echo $quoteDate = $docVar[1];
                              }else
                                echo "-";
                              ?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php 
                                $recDate = "";          
                                if(isset($docVar[1])){
                                  $recDate = $docVar[1]; 
                                }else
                                  echo "-";
                              ?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php echo $wonDate=($getRow['date_job_won']!=""?date('m/d/Y',strtotime($getRow['date_job_won'])):"-");?>
                            </td>
                            <td data-title=":"  style="padding-bottom:8.7px">
                              <?php if ($quoteDate!="" && $wonDate!="") { 
                                  echo $wonDate;
                              }else echo "-"; 
                              ?>
                            </td>
                            <td ata-title=":"  style="padding-bottom:8.7px">
                            <a href="#myModal4" name='job_schedule_for' id="{{$getRow['id']}}" field='date_parts_ordered' class='btn btn-link btn-xs' data-toggle="modal" style='margin:-8.7px;'><i class="fa fa-calendar"></i></a>&nbsp;<?php echo (!empty($getRow['date_parts_ordered'])?$datePartsOrdered=date('m/d/Y',strtotime($getRow['date_parts_ordered'])):'-');?></td>
                            <td ata-title=":"  style="padding-bottom:8.7px">
                              <?php if ($wonDate!="" && $getRow['date_parts_ordered']!="") { 
                                   echo (!empty($getRow['date_parts_ordered'])?$datePartsOrdered=date('m/d/Y',strtotime($getRow['date_parts_ordered'])):'-');
                                 }else
                                    echo "-";
                              ?>
                            </td>
                            <td ata-title=":"  style="padding-bottom:8.7px"><a href="#myModal4" name='job_schedule_for' id="{{$getRow['id']}}" field='date_parts_recieved' class='btn btn-link btn-xs' data-toggle="modal" style='margin:-8.7px;'><i class="fa fa-calendar"></i></a>&nbsp;<?php echo (!empty($getRow['date_parts_recieved'])?$datePartsRecieved=date('m/d/Y',strtotime($getRow['date_parts_recieved'])):'-');?></td>
                            <td ata-title=":"  style="padding-bottom:8.7px">
                              <?php if ($getRow['date_parts_ordered']!="" && $getRow['date_parts_recieved']!="") { 
                                    echo (!empty($getRow['date_parts_recieved'])?$datePartsRecieved=date('m/d/Y',strtotime($getRow['date_parts_recieved'])):'-');
                              }else
                                  echo "-";
                             ?>
                            </td>
                            <td ata-title=":"  style="padding-bottom:8.7px"><a href="#myModal4" name='job_schedule_for' id="{{$getRow['id']}}" field='date_job_scheduled_for' class='btn btn-link btn-xs' data-toggle="modal" style='margin:-8.7px;'><i class="fa fa-calendar"></i></a><?php echo (!empty($getRow['date_job_scheduled_for'])?date('m/d/Y',strtotime($getRow['date_job_scheduled_for'])):'-');?></td>
                            <td ata-title=":"  style="padding-bottom:8.7px">
                              <?php if ($getRow['date_parts_recieved']!="" && $getRow['date_job_scheduled_for']!="") { 
                                   echo $getRow['date_job_scheduled_for'];
                               }else echo "-"; ?>
                            </td>
                            <td ata-title=":"  style="padding-bottom:8.7px">
                            <a href="#myModal3" style='margin:-8.7px;' name='attach_jobs' id="{{$getRow['id']}}" class='btn btn-link btn-xs' data-toggle='modal'><i class="fa fa-anchor"></i></a>&nbsp;{{isset($getRow['attach_job_num'])?$getRow['attach_job_num']:'-'}}</td>
                            <td ata-title=":"  style="padding-bottom:8.7px">{{HTML::link('#myModal', 'Notes' , array('name'=>'view_update_notes','id'=>$getRow['id'],'emp'=>(!empty($getRow['GPG_employee_id'])?$getRow['GPG_employee_id']:'0'),'class' => 'btn btn-link btn-xs','data-toggle'=>'modal','style'=>'margin:-8.7px;'))}}
                             &nbsp;{{DB::table('gpg_job_note')->where('gpg_job_id','=',$getRow['id'])->orderBy('dated','DESC')->pluck('notes')}}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
                  {{HTML::link('#myModal2', 'Update Selected Jobs' , array('class' => 'btn btn-info','data-toggle'=>'modal'))}}
                  {{ Form::button('Delete Selected Jobs', array('class' => 'btn btn-danger', 'id'=>'delete_records')) }}
                  <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>GRAND TOTALS</i></b>
                </header>
                </section>
                <section id="no-more-tables"  style="padding:8.7px;">
                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr><th>Contract Amount</th><th>Invoice Amount</th><th>Tax</th><th>Invoice Amount Net</th><th>Material Costs </th><th>Labor Costs </th><th>COST TO DATE</th></tr>
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
                         </tr>
                        </tbody>
                  </table>
                 {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
              </section>
                        <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            {{ Form::open(array('before' => 'csrf' ,'id'=>'notes_form','url'=>route('job/creatUpdataNotes'),'files'=>true, 'method' => 'post')) }}
                                  {{Form::hidden('cjob_id','',array('id'=>'cjob_id'))}}
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">NOTES DETAILS:</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="form-group">
                                              <section id="no-more-tables">
                                                <table class="table table-bordered table-striped table-condensed cf">
                                                  <thead>
                                                    <tr>
                                                      <th>Notes</th>
                                                      <th>Action</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody class="cf" id="display_notes">
                                                  </tbody>
                                                </table>  
                                                <br/>
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                  <tbody class="cf">
                                                  <tr>
                                                    <th>Dated:</th><td>{{ Form::text('CDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'CDate')) }}</td>
                                                  </tr>
                                                  <tr>
                                                    <th>Entered By:</th><td>{{Form::select('contactPerson', $salesp_arr, null, ['class'=>'form-control','id'=>'contactPerson'])}}</td>
                                                  </tr>
                                                  <tr>
                                                    <th>Notes:</th><td>{{ Form::textarea('contactDetails','',['class'=>'form-control']) }}</td>
                                                  </tr>
                                                  </tbody>
                                                </table>
                                            </section>
                                            </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-primary','data-dismiss'=>'modal','id'=>'save_notes_data'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                        {{Form::close()}}
                                      </div>
                                  </div>
                              </div>

                          </div>
                        <!-- modal -->
                         <!-- Modal#3 -->
                          <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            {{ Form::open(array('before' => 'csrf' ,'id'=>'attach_job','url'=>route('job/attachJobNum'),'files'=>true, 'method' => 'post')) }}
                                  {{Form::hidden('vjob_id','',array('id'=>'vjob_id'))}}
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">NOTES DETAILS:</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="form-group">
                                              <section id="no-more-tables">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                  <tbody class="cf">
                                                  <tr>
                                                    <th>Job Num:</th><td>{{ Form::text('jobNumberFind','', array('class' => 'form-control', 'id' => 'jobNumberFind')) }}</td>
                                                  </tr>
                                                  </tbody>
                                                </table>
                                            </section>
                                            </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-primary','data-dismiss'=>'modal','id'=>'save_attach_job'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                        {{Form::close()}}
                                      </div>
                                  </div>
                              </div>

                          </div>
                        <!-- modal#3 end -->
                        <!-- Modal#4 -->
                          <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            {{ Form::open(array('before' => 'csrf' ,'id'=>'date_attach_job','url'=>route('job/attachJobDate'),'files'=>true, 'method' => 'post')) }}
                                  {{Form::hidden('jjob_id','',array('id'=>'jjob_id'))}}
                                  {{Form::hidden('field_name','',array('id'=>'field_name'))}}
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Enter Date:</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="form-group">
                                              <section id="no-more-tables">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                  <tbody class="cf">
                                                  <tr>
                                                    <th>Enter Date:</th><td>{{ Form::text('get_date','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'get_date')) }}</td>
                                                  </tr>
                                                  </tbody>
                                                </table>
                                            </section>
                                            </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-primary','data-dismiss'=>'modal','id'=>'save_attach_date'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                        {{Form::close()}}
                                      </div>
                                  </div>
                              </div>

                          </div>
                        <!-- modal#4 end -->
                        <!-- Modal#5 -->
                          <div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            {{ Form::open(array('before' => 'csrf' ,'id'=>'files_form','url'=>route('job/creatUpdataJobFiles'),'files'=>true, 'method' => 'post')) }}
                                  {{Form::hidden('fjob_id','',array('id'=>'fjob_id'))}}
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title"> RECOMMENDATION:</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="form-group">
                                              <section id="no-more-tables">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                  <tbody class="cf">
                                                  <tr>
                                                    <th>Add Attachment:</th><td>{{ Form::file('attachment', ['class' => 'form-control', 'id' => 'file_attachment_id']) }}<span style="display:inline;" id="file_show_sp"></span></td>
                                                  </tr>
                                                  <tr>
                                                    <th>Assign To:</th><td>{{Form::select('dropEmployee', $salesp_arr, null, ['class'=>'form-control','id'=>'drop2Employee'])}}</td>
                                                  </tr>
                                                  <tr>
                                                    <th>Rec. Category:</th><td>{{Form::select('RecCategoryValue', array(''=>'ALL','1'=>'Basic','2'=>'Intermediate','3'=>'Urgent'), null, ['class'=>'form-control','id'=>'Rec2CategoryValue'])}}</td>
                                                  </tr>
                                                  </tbody>
                                                </table>
                                            </section>
                                            </div>
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save / Update', array('class' => 'btn btn-primary','data-dismiss'=>'modal','id'=>'save_files_data'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                        {{Form::close()}}
                                      </div>
                                  </div>
                              </div>

                          </div>
                        <!-- modal#5 -->
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
                                              <section id="no-more-tables"  style="padding:8.7px;">
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
              $('#SDate2').val("");
              $('#EDate2').val("");
              $('#JobWonSDate').val("");
              $('#JobWonEDate').val("");
              $('#InvoiceSDate').val("");
              $('#InvoiceEDate').val("");
              $('#PartsOrderedSDate').val("");
              $('#PartsOrderedEDate').val("");            
              $('#PartsRecievedSDate').val("");
              $('#PartsRecievedEDate').val("");
              $('#PartsScheduledSDate').val("");
              $('#PartsScheduledEDate').val("");
              $('#ScheduledSDate').val("");
              $('#ScheduledEDate').val("");
              $('#SJobNumber').val("");
              $('#EJobNumber').val("");
              $('#SContractNumber').val("");
              $('#EContractNumber').val("");
              $('#InvNumber').val("");
              $('#optRegarding').val("");
              $('#sort_order').val("");
              $('#sort_type').val("");
              $('#optJobCostStatus').val("");
              $('#optJobAccount').val("");
              $('#optJobHaving').val("");
              $('#optRecQuote').val("");
              $('#optWonJob').val("");
              $('#optTechAtt').val("");
              $('#optJobStatus').val("");
              $('#optCustomer').val("");
              $('#optEmployee').val("");
              $('#optTech').val("");
              $('#optAttachedFlist').val("");
              $('#dropEmployee').val("");
              $('#RecCategoryValue').val("");
              $('#optClearedReason').val("");
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

      $('select[name=fsw_drop]').change(function(){
        var vl = $(this).val();
        var gpg_id =  $(this).attr('fsw_id');
          $.ajax({
              url: "{{URL('ajax/updateFWS')}}",
              data: {
              'gpg_id' : gpg_id,
              'status' : vl
              },
              success: function (data) {
            },
          });

      });
      function toggle(source) {
            checkboxes = document.getElementsByName('editChk[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
            }
      }
    $('a[name=view_update_notes]').click(function(){
        var id = $(this).attr('id');
        var emp = $(this).attr('emp');
        $('#cjob_id').val(id);
        $.ajax({
              url: "{{URL('ajax/getnShowNotes')}}",
              data: {
              'id' : id
              },
              success: function (data) {
                $('#display_notes').html(data);
            },
        });
    });  

    $('#save_notes_data').click(function(){
      if($('#contactPerson').val() != '' && $('#CDate').val() != '' && $('#cjob_id').val() != ''){
        $('#notes_form').submit();
      }else{
        alert('PLease fill required data!');
        return false;
      }
    });
    $('a[name=attach_jobs]').click(function(){
        var id = $(this).attr('id');
        $('#vjob_id').val(id);
    });
    $('#save_attach_job').click(function(){
      if($('#jobNumberFind').val() != '')
        $('#attach_job').submit();
      else
        alert('Please fill Job number field!');
    });
    $('a[name=job_schedule_for]').click(function(){
      var id = $(this).attr('id');
      var field = $(this).attr('field');
        $('#jjob_id').val(id);
        $('#field_name').val(field);
    });
    $('#save_attach_date').click(function(){
       if ($('#get_date').val() != '') {
          $('#date_attach_job').submit();
       } else{
        alert('Please Fill required field!');
      }
    });
    $('a[name=manage_attaches]').click(function(){
      var id = $(this).attr('id');
      $('#fjob_id').val(id);
      $.ajax({
              url: "{{URL('ajax/getSJAttaches')}}",
              data: {
              'id' : id
              },
              success: function (data) {
                $('#file_show_sp').html(data.file);
                $('#Rec2CategoryValue').val(data.cat);
                $('#drop2Employee').val(data.emp);
            },
      });

    });
    $('#save_files_data').click(function(){
      var attVal = $('#file_attachment_id').val();
      if (attVal == ''){
        alert('Please Select File first');
        return false; 
      }else{
        $('#files_form').submit();
      }
    });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop