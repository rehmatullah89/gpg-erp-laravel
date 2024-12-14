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
                  CONTRACT COMPARISON REPORT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b>Search by:</b><i>Month/Year First/Year Second </i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/contract_comparison_report'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                      <th>
                                      {{Form::label('month', 'Select Month:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                      <th>
                                        {{Form::label('SYear', 'Year First:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                      <th>
                                        {{Form::label('EYear', 'Year Second:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                      <th>
                                        {{Form::label('action', 'Action:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td data-title="Month:">
                                        <select name="month" id="month" style="width:110px;" class="form-control">
                                        <?php
                                          $month = date('m');
                                          for($f=1; $f<=12; $f++){
                                            $selected = ($f == $month) ? " selected" : "";      
                                            echo("<option value=\"$f\"$selected>".date('F', mktime(0,0,0,$f,1,2000))."</option>");
                                          }
                                        ?>
                                      </select>
                                      </td>
                                      <td data-title="From:">
                                        <select name="SYear" id="SYear" style="width:100px;" class="form-control">
                                          <?php
                                          $SYear = Input::get('SYear');
                                          if(empty($SYear))
                                            $SYear = date("Y",strtotime("-1 year"));
                                          $year_display = 10;
                                          $thisyear = date('Y');
                                          for($year = $thisyear - $year_display; $year <= $thisyear + $year_display; $year++){
                                            $selected = ($year == $SYear) ? " selected" : "";
                                            echo("<option value=\"$year\"$selected>".date('Y', mktime(0,0,0,1,1,$year))."</option>");
                                          }
                                          ?>
                                         </select>
                                      </td>
                                      <td data-title="To:">
                                        <select name="EYear" id="EYear" style="width:100px;" class="form-control">
                                        <?php
                                        $EYear = Input::get('EYear');
                                        if(empty($EYear))
                                          $EYear = date('Y');
                                        $year_display1 = 10;
                                        $thisyear1 = date('Y');
                                        for($year1 = $thisyear1 - $year_display1; $year1 <= $thisyear1 + $year_display1; $year1++){
                                          $selected = ($year1 == $EYear) ? " selected" : "";
                                          echo("<option value=\"$year1\"$selected>".date('Y', mktime(0,0,0,1,1,$year1))."</option>");
                                        }
                                        ?>
                                        </select>
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
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
     <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                        <th colspan="3" style="text-align:center;">[Againest Invoice Dates] Year {{$SYear}}</th>
                                        <th colspan="3" style="text-align:center;">[Againest Created Dates] Year {{$EYear}}</th>
                                        <th colspan="2" style="text-align:center;"></th>
                                      </tr>
                                      <tr>
                                          <th style="text-align:center;">Contract Number</th>
                                          <th style="text-align:center;">Job Number</th>
                                          <th style="text-align:center;">Invoice Amount</th>
                                          <th style="text-align:center;">Contract Number</th>
                                          <th style="text-align:center;">Job Number</th>
                                          <th style="text-align:center;">Invoice Amount</th>
                                          <th style="text-align:center;">Customer</th>
                                          <th style="text-align:center;">Total</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        <?php 
                                        $colcount=0;
                                        foreach ($yearFirstResult as $key => $yearFirstRow){ 
                                        ?>
                                          <tr  bgcolor="<?php echo ($colcount%2==0?"#FFFFCC":"#FFFFFF"); ?>">
                                            <td height="25" align="center" >&nbsp;<strong><?php echo $yearFirstRow['contract_number']?></strong></td>
                                        <td height="25" align="center" >&nbsp;<?php echo $yearFirstRow['job_num']?></td>
                                            <td height="25"  align="center" >&nbsp;<?php echo '$'.number_format($yearFirstRow['invoice_amt'],2)?></td>
                                         <?php 
                                        if(trim($yearFirstRow['contract_number']) == trim(@$yearSecondArray[$yearFirstRow['contract_number']]['contractNum']))
                                        { 
                                        ?>
                                        <td height="25" align="center" >&nbsp;<strong><?php echo @$yearSecondArray[$yearFirstRow['contract_number']]['contractNum']?></strong></td>
                                        <td height="25" align="center" >&nbsp;<?php echo @$yearSecondArray[$yearFirstRow['contract_number']]['jobNum'];?></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format(isset($yearSecondArray[$yearFirstRow['contract_number']]['invoiceAmt'])?$yearSecondArray[$yearFirstRow['contract_number']]['invoiceAmt']:0,2)?></td>
                                        <td height="25" align="center" >&nbsp;<?php echo @$yearSecondArray[$yearFirstRow['contract_number']]['customer']?></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format(isset($yearFirstRow['invoice_amt'])?$yearFirstRow['invoice_amt']:0+isset($yearSecondArray[$yearFirstRow['contract_number']]['invoiceAmt'])?$yearSecondArray[$yearFirstRow['contract_number']]['invoiceAmt']:0,2)?></td>
                                        </tr> 
                                        <?php 
                                        unset($yearSecondArray[$yearFirstRow['contract_number']]);
                                        } else {?>
                                        
                                        <td height="25" align="center" >&nbsp;</td>
                                        <td height="25" align="center" >&nbsp;</td>
                                            <td height="25" align="center" >&nbsp;</td>
                                        <td height="25" align="center" >&nbsp;<?php echo @$yearFirstRow['customer']?></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format($yearFirstRow['invoice_amt'],2)?></td>
                                        </tr>
                                        <?php }
                                        $colcount++;
                                        }
                                        if (is_array($yearSecondArray)) {
                                        foreach($yearSecondArray as $key=>$Value) {
                                        ?>
                                        <tr  bgcolor="<?php echo ($colcount%2==0?"#FFFFCC":"#FFFFFF"); ?>">
                                            <td height="25" align="center" >&nbsp;</td>
                                        <td height="25" align="center" >&nbsp;</td>
                                            <td height="25"  align="center" >&nbsp;</td>
                                        <td height="25" align="center" >&nbsp;<strong><?php echo $key;?></strong></td>
                                        <td height="25" align="center" >&nbsp;<strong><?php echo @$Value['jobNum'];?></strong></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format(isset($Value['invoiceAmt'])?$Value['invoiceAmt']:0,2);?></td>
                                        <td height="25" align="center" >&nbsp;<?php echo @$Value['customer']?></td>
                                            <td height="25" align="center" >&nbsp;<?php echo '$'.number_format(isset($Value['invoiceAmt'])?$Value['invoiceAmt']:0,2);?></td>
                                        </tr>
                                        <?php $colcount++; 
                                        } 
                                        } 
                                        ?>
                                      </tbody>
                                  </table><br/>
                                  {{ HTML::link("reports/excelCCRExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
                                  {{ $query_data->links() }}
              </section>
              </div>
              </div>
              </section>
              </div>
              </div>
              <!-- page end-->
<script>
$(document).ready(function(){
    $('#reset_search_form').click(function(){
      $('#month').val("{{date('m')}}");
      $('#SYear').val("{{date('Y',strtotime('-1 year'))}}");
      $('#EYear').val("{{date('Y')}}");
     });
});   
</script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>
@stop