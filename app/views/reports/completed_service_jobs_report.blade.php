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
                  COMPLETED JOBS REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Completion Date</i></b>
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
                                   <td data-title="Date Start:">
                                    {{Form::label('SDate', 'Date Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                   </td>
                                   <td data-title="Date End:">
                                    {{Form::label('EDate', 'Date End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                   </td>
                                   <td data-title="Job Number:">
                                    {{Form::label('jobNum', 'Job Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::text('jobNum','', array('class' => 'form-control', 'id' => 'jobNum')) }}
                                   </td>
                                   <?php
                                      $SDate = Input::get("SDate");
                                      $EDate = Input::get("EDate");
                                      $elecJobCheck = Input::get("elecJobCheck");
                                      $grassivyJobCheck = Input::get("grassivyJobCheck");
                                      $specialProjectJobCheck = Input::get("specialProjectJobCheck");
                                      $serviceJobCheck = Input::get("serviceJobCheck");
                                      $shopJobCheck = Input::get("shopJobCheck");
                                      $rentalJobCheck = Input::get("rentalJobCheck");
                                      $billonlyJobCheck = Input::get("billonlyJobCheck"); 
                                      $job_num = Input::get("jobNum");
                                      $check_box = Input::get('check_box');
                                   ?>
                                   <td colspan="3" bgcolor="#FFFFFF">All <input type="checkbox" name="checkAll" onclick="SelectAll()" value="1" id="checkAll" />&nbsp;&nbsp;
                                      Service Jobs <input type="checkbox" id="serviceJobCheck" name="serviceJobCheck" value="1" <?php echo ($serviceJobCheck=="1" || $check_box=='1' ?'checked="checked"':'')?> />
                                      &nbsp;Electrical Jobs <input type="checkbox" name="elecJobCheck" id="elecJobCheck" value="1"  <?php echo ($elecJobCheck=="1" || $check_box=='1' ?'checked="checked"':'')?> />
                                      &nbsp;Grassivy Jobs <input type="checkbox" name="grassivyJobCheck" id="grassivyJobCheck" value="1"  <?php echo ($grassivyJobCheck=="1" || $check_box=='1' ?'checked="checked"':'')?> />
                                      &nbsp;Special Project Jobs <input type="checkbox" name="specialProjectJobCheck" id="specialProjectJobCheck" value="1"  <?php echo ($specialProjectJobCheck=="1" || $check_box=='1' ?'checked="checked"':'')?> />
                                      &nbsp;Shop Jobs <input type="checkbox" id="shopJobCheck" name="shopJobCheck" value="1"  <?php echo ($shopJobCheck=="1"  || $check_box=='1' ?'checked="checked"':'')?> />&nbsp;
                                      Rental Jobs <input type="checkbox" id="rentalJobCheck" name="rentalJobCheck" value="1"  <?php echo ($rentalJobCheck=="1"  || $check_box=='1' ?'checked="checked"':'')?> />
                                      Bill Only Jobs <input type="checkbox" id="billonlyJobCheck" name="billonlyJobCheck" value="1"  <?php echo ($billonlyJobCheck=="1"  || $check_box=='1' ?'checked="checked"':'')?> />
                                      <input type="hidden" id="chkBox" name="check_box" value="<?php echo $check_box?>" />
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
              <section id="no-more-tables" >
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
             {{ HTML::link("reports/excelCSJReportExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
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
          $('#SDate').val("");
          $('#EDate').val("");
          $('#jobNum').val("");
          $("#checkAll").prop("checked", false);
          $("#elecJobCheck").prop("checked", false);
          $("#grassivyJobCheck").prop("checked", false);
          $("#specialProjectJobCheck").prop("checked", false);
          $("#serviceJobCheck").prop("checked", false);
          $("#shopJobCheck").prop("checked", false);
          $("#rentalJobCheck").prop("checked", false);
          $("#billonlyJobCheck").prop("checked", false);
      });

    function SelectAll(){
      if ($("#checkAll").is(':checked')) {
          $("#elecJobCheck").prop("checked", true);
          $("#grassivyJobCheck").prop("checked", true);
          $("#specialProjectJobCheck").prop("checked", true);
          $("#serviceJobCheck").prop("checked", true);
          $("#shopJobCheck").prop("checked", true);
          $("#rentalJobCheck").prop("checked", true);
          $("#billonlyJobCheck").prop("checked", true);
        } else {
          $("#elecJobCheck").prop("checked", false);
          $("#grassivyJobCheck").prop("checked", false);
          $("#specialProjectJobCheck").prop("checked", false);
          $("#serviceJobCheck").prop("checked", false);
          $("#shopJobCheck").prop("checked", false);
          $("#rentalJobCheck").prop("checked", false);
          $("#billonlyJobCheck").prop("checked", false);
        }
    }
    
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop