@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
              <!-- page start-->
          <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">    
                  WIP REPORT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Dates / Filters</i></b>
                </header>
                  @if (isset($errors) && ($errors->any()))
                              <div class="alert alert-danger">
                                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                                  <h4>Error</h4>
                                     <ul>
                                      {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                                     </ul>
                              </div>
                          @endif
                          @if(@Session::has('success'))
                              <div class="alert alert-success alert-block">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                                 <h4>Success</h4>
                                  <ul>
                                  {{ Session::get('success') }}
                                 </ul>
                              </div>
                          @endif
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
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
                                  </td>
                                  <td data-title="Invoice Date End:">
                                    {{Form::label('InvoiceEDate', 'Invoice Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('InvoiceEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceEDate')) }}
                                  </td>
                                  <td data-title="Job Number Start:">
                                    {{Form::label('SJobNumber', 'Job Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SJobNumber','', array('class' => 'form-control', 'id' => 'SJobNumber')) }}
                                  </td>
                                  <td data-title="Job Number End:">
                                    {{Form::label('EJobNumber', 'Job Number End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EJobNumber','', array('class' => 'form-control', 'id' => 'EJobNumber')) }}
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="2">
                                    <input type="checkbox" name="ignoreCostDate" id="ignoreCostDate" value="1" />
                                    Ignore Date stamp  on Material Cost and Labor Cost.<br />
                                    <input type="checkbox" name="ignoreInvoiceDate" id="ignoreInvoiceDate" value="1" />Ignore Date stamp  on Invoice Amount.<br />
                                    <input type="checkbox" name="jobActivity" id="jobActivity" value="1"  />Jobs Having Activity
                                  </td>
                                  <td data-title="Customer:">
                                    {{Form::label('optCustomer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('optCustomer', array(''=>'Select Customer')+$customer_arr,'', ['id' => 'optCustomer', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Class:">
                                    {{Form::label('optJobClass', 'Class:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('optJobClass', array(''=>'ALL',"service"=>"Service Jobs","electrical"=>"Electrical Jobs"),'ALL', ['id' => 'optJobClass', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Jobs Having:">
                                    {{Form::label('optJobHaving[]', 'Jobs Having:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{Form::select('optJobHaving[]',array("all"=>"All","laborCost"=>"Labor Cost","materialCost"=>"Material Cost"),null, ['id' => 'optJobHaving', 'class'=>'form-control m-bot15','multiple'])}}
                                  </td>
                                  <td>
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
              <section class="panel">
              
              <div class="panel-body">
              <section id="flip-scroll" >
              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Created Date</th>
                  <th>Job Number</th>
                  <th>Customer</th>
                  <th>Class</th>
                  <th>Contractor</th>
                  <th>Total Revised Contract Amt</th>
                  <th>Est. Total Cost at Comp.</th>
                  <th>Est. Profit at Comp.</th>
                  <th>Material Cost</th>
                  <th>Labor Cost</th>
                  <th>Cost Incured to Date</th>
                  <th>% COMP</th>
                  <th>Profit to Date</th>
                  <th>Amt Eearned to Date</th>
                  <th>Amt Billed to Date</th>
                  <th>Cost in Excess of Billings</th>
                  <th>Billings in Excess of Cost</th>
                  <th>Est. Cost to Comp.</th>
                  <th>Contract Balance</th>
              </tr>
              </thead>
              <tbody class="cf">
               @foreach($query_data as $getRow)
                <tr>
                  <td>{{($getRow['created_on']!=""?date('m/d/Y',strtotime($getRow['created_on'])):"-")}}</td>
                  <td>{{ HTML::link('job/job_form/'.$getRow['id'].'/'.$getRow['job_num'].'', $getRow['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs', 'id'=>$getRow['id'],'j_num'=>$getRow['job_num']))}} </td>
                  <td title="{{$getRow['customer_name']}}">{{substr($getRow['customer_name'],0,20).'..'}}</td>
                  <td>{{($getRow['GPG_job_type_id']==5?"Electrical":"Service")}}</td>
                  <td>{{"Global Power Group, Inc"}}</td>
                  <td>{{'$'.number_format($totRevisedContractAmt = ($getRow["contract_amount"]!=0?$getRow["contract_amount"]:$getRow["fixed_price"]),2)}}</td>
                  <td>{{'$'.number_format($estTotalCost = $getRow["budgeted_material"]+$getRow["budgeted_labor"],2)}}</td>
                  <td>{{'$'.number_format($estProfit = $totRevisedContractAmt-$estTotalCost,2)}}</td>
                  <td>{{'$'.number_format($getRow["material_cost"],2)}}</td>
                  <td>{{'$'.number_format($getRow["labor_cost"],2)}}</td>
                  <td>{{'$'.number_format($costIncuredToDate =$getRow["material_cost"]+$getRow["labor_cost"],2)}}</td>
                  <td>{{'$'.round(number_format(($percComp =@($costIncuredToDate/$estTotalCost))*100,2))."%"}}</td>
                  <td>{{'$'.number_format($profitToDate = $percComp*$estProfit,2)}}</td>
                  <td>{{'$'.number_format($amtEarnedToDate = $profitToDate + $costIncuredToDate,2)}}</td>
                  <td>{{'$'.number_format($amtBilledToDate = $getRow["inv_amount"],2)}}</td>
                  <td>{{(($amtEarnedToDate-$amtBilledToDate)>0?'$'.number_format($amtEarnedToDate-$amtBilledToDate,2):'$'.number_format(0,0))}}</td>
                  <td>{{(($amtBilledToDate-$amtEarnedToDate)>0?'$'.number_format($amtBilledToDate-$amtEarnedToDate,2):'$'.number_format(0,0))}}</td>
                  <td>{{'$'.number_format($estCostToComplete = $estTotalCost-$costIncuredToDate,2)}}</td>
                  <td>{{'$'.number_format($contractBalance = $totRevisedContractAmt - $amtBilledToDate,2)}}</td>
                </tr>
               @endforeach
              </tbody>
              </table>
            </section>
            <section id="no-more-table" >
              <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                <th>Revised Contract Amount</th>
                <th>Est. Total Cost at Completion</th>
                <th>Est. Profit at Completion</th>
                <th>Mat. Cost</th>
                <th>Labor Cost</th>
                <th>Cost Incured to Date</th>
                <th>Profit to Date</th>
                <th>Amt Eearned to Date</th>
                <th>Amt Billed to Date</th>
                <th>Cost in Excess of Billings</th>
                <th>Billings in Excess of Cost</th>
                <th>Est. Cost to Complete</th>
                <th>Contract Balance</th>
              </tr>
              </thead>
              <tbody>
                <?php
                    $totContarctAmt =0;
                    $totEstimateCost=0;
                    $totEstimateProfitAtComp=0;
                    $totMatCost=0;
                    $totLabCost=0;
                    $totCostIncuredToDate=0;
                    $totProfitToDate=0;
                    $totAmtEarnedToDate=0;
                    $totAmtBilledToDate=0;
                    $totCostinExcessofBillings=0;
                    $totBillingsinExcessofCost=0;
                    $totEstCostToComplete=0;
                    $totContractBalance=0;

                  foreach ($totalsArr as $key => $getRow) {
                    $revisedContractAmt = ($getRow["contract_amount"]!=0?$getRow["contract_amount"]:$getRow["fixed_price"]);
                    $totContarctAmt += $revisedContractAmt;
                    $estCost = $getRow["budgeted_material"] + $getRow["budgeted_labor"];
                    $totEstimateCost += $estCost;
                    $estProfit = $revisedContractAmt-$estCost;
                    $totEstimateProfitAtComp += $estProfit;
                    $totMatCost += $getRow["material_cost"];
                    $totLabCost += $getRow["labor_cost"] ;
                    $costIncuredToDate = $getRow["material_cost"] + $getRow["labor_cost"];
                    $totCostIncuredToDate += $costIncuredToDate;
                    $percComp =@($costIncuredToDate/$estCost);
                    $profitToDate = $percComp * $estProfit;
                    $totProfitToDate += $profitToDate;
                    $amtEarnedToDate = $profitToDate + $costIncuredToDate;
                    $totAmtEarnedToDate += $amtEarnedToDate;
                    $amtBilledToDate = $getRow["inv_amount"];
                    $totAmtBilledToDate += $amtBilledToDate;
                    $totCostinExcessofBillings += (($amtEarnedToDate-$amtBilledToDate)>0?$amtEarnedToDate-$amtBilledToDate:0);
                    $totBillingsinExcessofCost += (($amtBilledToDate-$amtEarnedToDate)>0?$amtBilledToDate-$amtEarnedToDate:0);
                    $totEstCostToComplete += ($estCost-$costIncuredToDate);
                    $totContractBalance += ($revisedContractAmt - $amtBilledToDate);
                  }
                ?>
               <tr>
                  <td><strong>{{ '$'.number_format($totContarctAmt,2) }}</strong></td> 
                  <td><strong>{{ '$'.number_format($totEstimateCost,2) }}</strong></td> 
                  <td><strong>{{ '$'.number_format($totEstimateProfitAtComp,2) }}</strong></td> 
                  <td><strong>{{ '$'.number_format($totMatCost,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totLabCost,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totCostIncuredToDate,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totProfitToDate,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totAmtEarnedToDate,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totAmtBilledToDate,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totCostinExcessofBillings,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totBillingsinExcessofCost,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totEstCostToComplete,2) }}</strong></td>
                  <td><strong>{{ '$'.number_format($totContractBalance,2) }}</strong></td>
                  </tr>
              </tbody>
              </table>
            {{ HTML::link("reports/excelWIPReportExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
              <br/>
             {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
            </div>  
          </section>
        </div>
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
          $('#SJobNumber').val("");
          $('#EJobNumber').val("");
          $('#ignoreCostDate').val("");
          $('#ignoreInvoiceDate').val("");
          $('#jobActivity').val("");
          $('#optCustomer').val("");
          $('#optJobClass').val("");
          $('#optJobHaving').val(null);
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop