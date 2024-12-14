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
 <div class="row">
            <div class="col-sm-12">
              <section class="panel">
                <header class="panel-heading">
                  {{$page_heading}} 
                  <span class="tools pull-right">
                  <a href="javascript:;" class="fa fa-chevron-down"></a>
                  </span>
                </header>
              </section>
                <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b><i>{{$page_heading}} AND MANAGEMENT</i></b>
                </header>
                <?php 
                if($table == 'Grassivy'){
                  $uri = 'quote/grassivy_quote_list';
                }elseif($table == 'Special Project'){
                  $uri = 'quote/specialproject_quote_list';
                }elseif($table == 'Shop Work'){  
                  $uri = 'quote/shop_work_quote_list';
                }else
                  $uri = 'quote/elec_quote_list';
                 $table;?>
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route($uri),'files'=>true, 'method' => 'post'))}}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Created Date Start:">
                                    {{Form::label('SDate2', 'Created Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SDate2','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate2')) }}
                                  </td><td data-title="Created Date End:">
                                    {{Form::label('EDate2', 'Created Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
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
                                  <td data-title="Invoice End Date:">
                                    {{Form::label('InvoiceEDate', 'Invoice End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('InvoiceEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceEDate')) }}
                                  </td>
                                </tr>
                                <tr>
                                <td colspan="6">
                                  <span class="smallblack"><strong>Note:</strong> Leave blank for viewing records from all days. Fill start date only if want to see the records for a perticular date. Same note for all date fields given below.</span><br/>
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  {{ Form::checkbox('ignoreCostDate','1','', array('id'=>'ignoreCostDate','class' => 'input-group','style'=>'display:inline;')) }}
                                  Ignore Date stamp  on Material Cost and Labor Cost.<br />
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  {{ Form::checkbox('ignoreInvoiceDate','1','', array('id'=>'ignoreInvoiceDate','class' => 'input-group','style'=>'display:inline;')) }}
                                  Ignore Date stamp  on Invoice Amount.
                                </td>
                                </tr>
                                <tr> <!-- 4th Row-->
                                  <td data-title="Electrical Quote Number:">
                                    {{Form::label('optJobNumber', 'Electrical Quote Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('optJobNumber','', array('class' => 'form-control', 'id' => 'optJobNumber')) }}
                                  </td>
                                  <td data-title="Sales Person:">
                                    {{Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optEmployee', $salesp_arr, null, ['id' => 'optEmployee', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Customer:">
                                    {{Form::label('optCustomer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optCustomer', $cust_arr, null, ['id' => 'optCustomer', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Electrical Quote Status:">
                                    {{Form::label('optStatus', 'Electrical Quote Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optStatus', array(''=>'ALL','Quote' => 'Quote', 'Won' => 'Won'), null, ['id' => 'optStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Job Status:">
                                    {{Form::label('optJobStatus', 'Job Status:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optJobStatus', array(''=>'ALL',"completed"=>"Have been Completed","notcompleted"=>"Jobs Not Completed","invoiced"=>"Have been Invoiced","comp_inv"=>"Have been Invoiced and Completed","not_comp_inv"=>"Have been Invoiced but Not Completed","not_invoiced"=>"Have Not been Invoiced","completed_not_invoiced"=>"Completed but Have Not been Invoiced","completed_not_closed"=>"Completed Not Closed","closed_not_completed"=>"Closed Not Completed"), null, ['id' => 'optJobStatus', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Sort By:">
                                    {{Form::label('optSort', 'Sort By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('optSort',array('' =>'ALL','customerAndDate' =>'Customer and Date' ,'salespersonAndDate'=>'Salesperson and Date' ), null, ['id' => 'optSort', 'class'=>'form-control m-bot15'])}}
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
             <!-- ////////////////////////////////////////// -->
              <div class="panel">
               <div class="panel-body">
              <div class="adv-table">
              <section id="flip-scroll" >
                  <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;" >Delete</th>
                            <th style="text-align:center;" >Created Date</th>
                            <th style="text-align:center;" >Customer</th>
                            <th style="text-align:center;" >Location</th>
                            <th style="text-align:center;" >Sales Person</th>
                            <th style="text-align:center;" >Lead Id </th>
                            <th style="text-align:center;" >{{$table}}&nbsp; Quote Number </th>
                            <th style="text-align:center;" >Job Type </th>
                            <th style="text-align:center;" >Quoted Amount</th>
                            <th style="text-align:center;" >Quoted Material Cost</th>
                            <th style="text-align:center;" >Quoted Labor Cost</th>
                            <th style="text-align:center;" >Projected Margin </th>
                            <th style="text-align:center;" >Index 1 </th>
                            <th style="text-align:center;" >Scope of Work </th>
                            <th style="text-align:center;" >{{$table}}&nbsp; Status</th>
                            <th style="text-align:center;" >Status</th>
                            <th style="text-align:center;" >Stage</th>
                            <th style="text-align:center;" >Probability </th>
                            <th style="text-align:center;" >Estimated Close Date </th>
                            <th style="text-align:center;" >Date Job Won </th>
                            <th style="text-align:center;" >Job Number </th>
                            <th style="text-align:center;" >Invoice Date</th>
                            <th style="text-align:center;" >Invoice Number  </th>
                            <th style="text-align:center;" >Invoice Amount   </th>
                            <th style="text-align:center;" >Sales Tax </th>
                            <th style="text-align:center;" >Labor Cost </th>
                            <th style="text-align:center;" >Marerial Cost </th>
                            <th style="text-align:center;" >Total Cost </th>
                            <th style="text-align:center;" >Net Margin </th>
                            <th style="text-align:center;" >Inedx 2 </th>
                            <th style="text-align:center;" >Comm. Owed </th>
                            <th style="text-align:center;" >Comm. Paid </th>
                            <th style="text-align:center;" >Date Comm. Paid </th>
                            <th style="text-align:center;" >Comm. Balance </th>
                            <th style="text-align:center;" data-title="">Attachments</th>
                          </tr>
                        </thead>
                      <tbody class="cf">
                      @foreach($query_data as $getRow)
                        <tr>
                          <td  data-title="Delete:" style="padding-bottom:8.2px;">{{ Form::checkbox('delChk[]',$getRow['id'],'', array('id'=>'delChk[]','class' => 'input-group')) }}</td>
                          <td  data-title="Created Date:" style="padding-bottom:8.2px;">{{date('m/d/Y',strtotime($getRow['created_on']))}}</td>
                          <td  data-title="Customer:" style="padding-bottom:8.2px;" style="white-space: nowrap !important;">{{$getRow['customer']}}</td>
                          <td  data-title="Location:" style="padding-bottom:8.2px;">{{(isset($getRow['eqp_location'])?$getRow['eqp_location']:(isset($getRow['location'])?$getRow['location']:'-'))}}</td>
                          <td  data-title="Sales Person:" style="padding-bottom:8.2px;">{{$getRow['salesPerson']}}</td>
                          <td  data-title="Lead Id:" style="padding-bottom:8.2px;">{{$getRow['gpg_sales_tracking_id']}}</td>
                          <td  data-title="Electrical Quote Number:" style="padding-bottom:8.2px;">
                          @if($table == 'Electrical')
                          {{ HTML::link('job/job_electrical_quote_frm/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}}
                          @elseif($table == 'Grassivy')
                          {{ HTML::link('job/job_grassivy_equipment_pricing_frm/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}}
                          @elseif($table == 'Shop Work')
                          {{ HTML::link('job/shop_work_quote_frm/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}}
                          @else
                          {{ HTML::link('job/job_special_project_equipment_pricing_frm/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}}
                          @endif
                          </td>
                          <td  data-title="Job Type:" style="padding-bottom:8.2px;">{{(isset($getRow['quote_type'])?$getRow['quote_type']:'-')}}</td>
                          <td  data-title="Quoted Amount:" style="padding-bottom:8.2px;">{{'$'.number_format((isset($getRow['grand_total'])?$getRow['grand_total']:$getRow['grand_list_total'])+(isset($getRow['subquote_total_cost'])?$getRow['subquote_total_cost']:0),2)}}</td>
                          <td  data-title="Quoted Material Cost:" style="padding-bottom:8.2px;">{{'$'.number_format((isset($getRow['grand_total_material'])?$getRow['grand_total_material']:$getRow['mat_cost_total'])+(isset($getRow['subquote_material_cost'])?$getRow['subquote_material_cost']:$getRow['comp_cost_total'])+(isset($getRow['freight'])?$getRow['freight']:0)+((isset($getRow['mat_cost_total'])?$getRow['mat_cost_total']:0)*((isset($getRow['tax_amount'])?$getRow['tax_amount']*.01:0))),2)}}</td>
                          <td  data-title="Quoted Labor Cost:" style="padding-bottom:8.2px;">{{'$'.number_format((isset($getRow['grand_total_labor'])?$getRow['grand_total_labor']:$getRow['labor_cost_total'])+(isset($getRow['subquote_labor_cost'])?$getRow['subquote_labor_cost']:0)+((isset($getRow['sub_cost_total'])?$getRow['sub_cost_total']:0)*((isset($getRow['hazmat'])?$getRow['hazmat']:0)*.01))+(isset($getRow['mileage'])?$getRow['mileage']:0),2)}}</td>
                          <td  data-title="Projected Margin:" style="padding-bottom:8.2px;">{{'$'.number_format((isset($getRow['margin_gross_total'])?$getRow['margin_gross_total']:0)+(isset($getRow['subquote_material_margin'])?$getRow['subquote_material_margin']:$getRow['grand_list_total'])-(isset($getRow['grand_cost_total'])?$getRow['grand_cost_total']:0),2)}}</td>
                          <td  data-title="Index 1:" style="padding-bottom:8.2px;">{{isset($getRow['labor_quantity'])?$getRow['labor_quantity']:$getRow['labor']}}</td>
                          <td  data-title="Scope of Work:" title="{{(isset($getRow['scope_of_work'])?$getRow['scope_of_work']:0)}}" style="padding-bottom:8.2px;"><?php $str=substr((isset($getRow['scope_of_work'])?$getRow['scope_of_work']:$getRow['task']),0,25); if(isset($getRow['scope_of_work']) && strlen( $getRow['scope_of_work'])>25) $str=$str."..."; if(isset($getRow['task']) && strlen( $getRow['task'])>25) $str=$str."..."; echo $str;?></td>
                          <td  data-title="Electrical Status:" title="{{$getRow['quote_status']}}" style="padding-bottom:8.2px;"><?php $str=substr( $getRow['quote_status'],0,25); if(strlen( $getRow['quote_status'])>25) $str=$str."..."; echo $str;?></td>
                          <td  data-title="Status:" style="padding-bottom:8.2px;">{{(isset($getRow['job_type_status'])?$getRow['job_type_status']:'-')}}</td>
                          <td  data-title="Stage:" style="padding-bottom:8.2px;">{{(isset($getRow['qote_stage_id'])?$getRow['qote_stage_id']:'-')}}</td>
                          <td  data-title="Probability:" style="padding-bottom:8.2px;">{{(isset($getRow['probability']) && $getRow['probability']=='-'?'0%':(isset($getRow['probability'])?$getRow['probability']:0)."%")}}</td>
                          <td  data-title="Estimated Close Date:" style="padding-bottom:8.2px;">{{(isset($getRow['estimated_close_date']) && $getRow['estimated_close_date']== '-'?'-':date('m/d/Y',strtotime((isset($getRow['estimated_close_date'])?$getRow['estimated_close_date']:date('Y-m-d')))))}}</td>
                          <td  data-title="Date Job Won:" style="padding-bottom:8.2px;">{{($getRow['date_job_won']!="-"?date('d/m/Y',strtotime($getRow['date_job_won'])):"-")}}</td>
                          <td  data-title="Job Number:" style="padding-bottom:8.2px;">
                          @if(isset($getRow['GPG_attach_job_num']))
                          {{$getRow['GPG_attach_job_num']}}
                          @else
                          {{'-'}}
                          @endif
                          </td>
                          <td  data-title="Invoice Date:" style="padding-bottom:8.2px;">
                          <?php $invoiceData = array();?>
                          @if(!empty($getRow['attachJobRes']))
                            @if($getRow['attachJobRes']['invoice_data'] != '1' && !empty($getRow['attachJobRes']['invoice_data']))
                              <?php $invoiceData = @explode("#~#",$getRow['attachJobRes']['invoice_data']); ?>
                              {{@($invoiceData[4]>1?"Multiple":($invoiceData[2]!=''?date('m/d/Y',strtotime($invoiceData[2])):"-"))}}
                            @else
                              {{'-'}}  
                            @endif
                          @else
                          {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Invoice Number:" style="padding-bottom:8.2px;">
                          @if(!empty($invoiceData))
                            {{@($invoiceData[4]>1?"Multiple":$invoiceData[0])}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Invoice Amount:" style="padding-bottom:8.2px;">
                          @if(!empty($invoiceData))
                            {{'$'.@number_format($invoiceData[1],2)}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Sales Tax :" style="padding-bottom:8.2px;">
                          @if(!empty($invoiceData))
                            {{'$'.@number_format($invoiceData[3],2)}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Labor Cost:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['attachJobRes']))
                            {{'$'.@number_format($getRow['attachJobRes']['labor_cost'],2)}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Marerial Cost:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['attachJobRes']))
                            {{'$'.@number_format($getRow['attachJobRes']['material_cost'],2)}}
                          @else
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Total Cost:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['attachJobRes']))
                          <?php $totalCost=0;?>
                          {{'$'.@number_format($totalCost = $getRow['attachJobRes']['material_cost']+$getRow['attachJobRes']['labor_cost'],2)}}
                          @else
                          <?php $totalCost=0;?>
                            {{'-'}}  
                          @endif
                          </td>
                          <td  data-title="Net MarginInedx 2:" style="padding-bottom:8.2px;">
                          @if(!empty($invoiceData) || isset($totalCost))
                          <?php $netMargin=0;?>
                          {{'$'.@number_format($netMargin = $invoiceData[1]-$invoiceData[3]-$totalCost,2)}}</td>
                          @else
                            <?php $netMargin=0;?>
                            {{'-'}}  
                          @endif
                          <td  data-title="Index2:" style="padding-bottom:8.2px;">
                          @if($getRow['time_diff_dec'] != '-' && !empty($getRow['time_diff_dec']))  
                          {{$getRow['time_diff_dec']}}
                          @else
                          {{'$0.00'}}
                          @endif
                          </td>
                          <td  data-title="Comm. Owed:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['commData']))
                          {{'$'.number_format($getRow['commData']['amt'],2)}}
                          @else
                          {{'-'}}
                          @endif
                          </td>
                          <td  data-title="Comm. Paid:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['attachJobRes']))
                          {{'$'.number_format($commOwed =  $saleCom = (@($netMargin*$getRow['attachJobRes']['sales_commission'])/100),2)}}
                          @else
                            {{'$0.00'}}  
                          @endif
                          </td>
                          <td  data-title="Date Comm. Paid:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['commData']))
                          {{($getRow['commData']['comm_date']!=""?date('m/d/Y',strtotime($getRow['commData']['comm_date'])):"-")}}
                          @else
                          {{'-'}}
                          @endif
                          </td>
                          <td  data-title="Comm. Balance:" style="padding-bottom:8.2px;">
                          @if(!empty($getRow['commData']))  
                          {{'$'.number_format($commOwed - $getRow['commData']['amt'],2)}}</td>
                          @else
                          {{'$0.00'}}
                          @endif
                          <td  data-title="Attachments:" style="padding-bottom:8.2px;">{{HTML::link('#myModal4', 'Manage Files' , array('class' => 'btn btn-link','data-toggle'=>'modal','name'=>'manage_files','id'=>$getRow['id'],'job_num'=>$getRow['job_num']))}}</td>
                         </tr>
                      @endforeach
                      </tbody>
                  </table>
                   {{ HTML::link("quote/excelQuoteExport?table=$table&".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}                   
                   {{ Form::button('Delete Selected Jobs', array('class' => 'btn btn-danger', 'id'=>'delete_records')) }}
                </section>   
                {{ $query_data->appends($allInputs)->links() }}
              </div>
              </div>     
              </div>
            </div>

</div>
 <!-- Modal# -->
           <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ATTACHMENT MANAGEMENT</h4>
                      </div>
                    <div class="modal-body">
                 {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('quote/manageQuoteFiles'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('fjob_id','',array('id' => 'change_job_id' ))}} {{Form::hidden('fjob_num','',array('id' => 'change_job_num' ))}}     <div class="form-group">
                                               <section id="no-more-tables"  style="padding:10px;">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                <thead class="cf">
                                                  <tr><th>#</th><th>Category Name </th><th>Action</th></tr>
                                                </thead>
                                                <tbody class="cf" id="display_quote_files">
                                                </tbody>
                                                </table>
                                              </section> 
                  <div style="display: inline;">
                   {{ Form::file('attachment', array('style'=>'float: left !important; display:inline !important; width:50%;' ,'id' => 'attachment')) }}
                          </div> </div>
                   {{Form::close()}}
                  <div class="btn-group" style="padding:20px;">
                    {{Form::button('Submit', array('class' => 'btn btn-success', 'id'=>'submit_attachments'))}}
                   {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# end--> 
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

     $("#delete_records").click(function(){
          result = false;
          checkboxes = document.getElementsByName('delChk[]');
          var count =0;
          for(var i=0, n=checkboxes.length;i<n;i++) {
              if (checkboxes[i].checked == 1){
                count++;
              }
          }
          if (count > 0){
            var result = confirm("Are you sure! you want to delete this/these: "+count+" quotes ....?");
          }else{
              alert("No Item Selected");            
          }
          if (result){         
            for(var i=0, n=checkboxes.length;i<n;i++) {
                if (checkboxes[i].checked == 1){
                    $.ajax({
                        url: "{{URL('ajax/deleteQuotes')}}",
                        data: {
                          'id' : $(checkboxes[i]).val(),
                          'table':'<?php echo $table ?>'
                        },
                        success: function (data) {
                         /* if (data == 1){     
                            alert("Deleted Successfully!");
                            location.reload(true);
                          }*/
                      },
                    });
                }
            }
            alert("Record(s) Deleted Successfully!");
            location.reload(true);
          }else{
            return false;
          }
      });  
      
      $('#submit_attachments').click(function(){
        $('#submit_file_form').submit();
      });
    
      $('#reset_search_form').click(function(){
              $('#SDate2').val("");
              $('#EDate2').val("");
              $('#JobWonSDate').val("");
              $('#JobWonEDate').val("");
              $('#InvoiceSDate').val("");
              $('#InvoiceEDate').val("");
              $('#optJobNumber').val("");
              $('#optEmployee').val("");
              $('#optCustomer').val("");
              $('#optStatus').val("");
              $('#optJobStatus').val("");
              $('#optSort').val("");
      });
      $('a[name=manage_files]').click(function(){
        var job_num = $(this).attr('job_num');
        var job_id = $(this).attr('id');
        $('#change_job_id').val(job_id);
        $('#change_job_num').val(job_num);
        $.ajax({
              url: "{{URL('ajax/getQuoteFiles')}}",
              data: {
                'id' : job_id,
                'num': job_num,
                'table':'<?php echo $table ?>'
              },
            success: function (data) {
              $('#display_quote_files').html(data);
               $('a[name=del_quote_file]').click(function(){
                var result = confirm("Are you sure! you want to delete....?");
                if(result){
                  $.ajax({
                        url: "{{URL('ajax/deleteQuoteFile')}}",
                        data: {
                          'id' : $(this).attr('id'),
                          'table':'<?php echo $table ?>'
                        },
                        success: function (data) {
                          if (data == 1){     
                            alert("Deleted Successfully!");
                            location.reload();
                          }
                      },
                  });
                }  
               });
              $('a[name=dld_quote_file]').click(function(){
                var id = $(this).attr('id');
                $('#download_id').val(id);
                $('#download_file').submit();
              });
            },
        });
      });
  </script>    
  <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
  <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop