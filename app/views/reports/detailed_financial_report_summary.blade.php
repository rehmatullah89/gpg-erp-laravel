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
                DETAILED FINANCIAL REPORT SUMMARY
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b>Search by:</b><i> Sales Person / Dates</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/detailed_financial_report_summary'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                      <th>
                                      {{Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                      <th>
                                        {{Form::label('mStart', 'From:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                      <th>
                                        {{Form::label('mEnd', 'To:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                      <th>
                                        {{Form::label('action', 'Action:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td data-title="Sales Person:">
                                        {{ Form::select('optEmployee',$salesp_arr,'', array('class' => 'form-control', 'id' => 'optEmployee')) }}
                                      </td>
                                      <td data-title="From:">
                                        <select name="mStart"  id="mStart" class="form-control" style="width:auto; display:inline;">
                                        <?php
                                        $reportMonthStart = Input::get('mStart');
                                        if (empty($reportMonthStart))
                                          $reportMonthStart = date('01');
                                        for($f=1; $f<=12; $f++){
                                          $selected = ($f == $reportMonthStart) ? " selected" : "";     
                                          echo("<option value=\"$f\"$selected>".date('F', mktime(0,0,0,$f,1,2000))."</option>");
                                        }
                                        ?>
                                        </select>
                                        <select name="yStart" id="yStart" class="form-control" style="width:auto; display:inline;">
                                        <?php
                                        $year_display = 20;
                                        $thisyear = Input::get('yStart');
                                        if (empty($thisyear))
                                          $thisyear = date('Y');
                                        for($year = $thisyear - $year_display; $year <= $thisyear + $year_display; $year++){
                                          $selected = ($year == $thisyear) ? " selected" : "";
                                          echo("<option value=\"$year\"$selected>".date('Y', mktime(0,0,0,1,1,$year))."</option>");
                                        }
                                        ?>
                                      </td>
                                      <td data-title="To:">
                                        <select name="mEnd" id="mEnd" class="form-control" style="width:auto; display:inline;">
                                        <?php
                                        $reportMonthEnd = Input::get('mEnd');
                                        if (empty($reportMonthEnd))
                                          $reportMonthEnd = date('m');
                                        for($f=1; $f<=12; $f++){
                                        $selected = ($f == $reportMonthEnd) ? " selected" : "";     
                                        echo("<option value=\"$f\"$selected>".date('F', mktime(0,0,0,$f,1,2000))."</option>");
                                        }
                                        ?>
                                      </select>
                                          <select name="yEnd" id="yEnd" class="form-control" style="width:auto; display:inline;">
                                        <?php
                                        $year_display = 20;
                                        $thisyear = Input::get('yEnd');
                                        if (empty($thisyear))
                                          $thisyear = date('Y');
                                        for($year = $thisyear - $year_display; $year <= $thisyear + $year_display; $year++){
                                        $selected = ($year == $thisyear) ? " selected" : "";
                                        echo("<option value=\"$year\"$selected>".date('Y', mktime(0,0,0,1,1,$year))."</option>");
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
                                          <th style="text-align:center;">SalesPerson</th>
                                          <th style="text-align:center;">Type</th>
                                          <th style="text-align:center;">Status</th>
                                          <?php
                                           $reportYearStart = Input::get('yStart');
                                           if (empty($reportYearStart))
                                            $reportYearStart = date('Y');
                                            $reportYearEnd = Input::get('yStart');
                                           if (empty($reportYearEnd))
                                            $reportYearEnd = date('Y'); 
                                           $monthCount = 12-$reportMonthStart + $reportMonthEnd + (($reportYearEnd-$reportYearStart-1)*12);
                                           $monCont = $reportMonthStart;
                                           $yearCont = $reportYearStart;
                                           $dateArray = array();
                                           for($i=0;$i<=$monthCount;$i++){
                                            $dateArray[$i] = date("m-Y",strtotime($yearCont.'-'.$monCont.'-01'));
                                            echo "<th align='center'><strong>".$dateArray[$i]."</strong><br>Count<br>Amount</th>";
                                            if ($monCont>=12) {
                                               $monCont-=12; 
                                               $yearCont++;
                                               }
                                            $monCont++;  
                                           }
                                          ?>
                                          <th style="text-align:center;"><strong>Total</strong><br>Count<br>Amount</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <?php 
                                        $preEmp = '';
                                        $preType = '';
                                        $preStatus = '';
                                        $fg=true; 
                                        foreach($query_data as $key => $value) { 
                                         ?>
                                         <tr>
                                        <?php
                                         $preType = '';
                                         echo "<td align= 'center' >".$key."</td>";
                                         foreach($value as $ke => $val) {
                                           $preStatus = ''; 
                                           echo (($preType==$ke or !empty($preType))?"<tr><td align= 'center'>-</td>":"");  
                                           echo "<td align= 'center'>".(($preType!=$ke or empty($preType))?ucwords($ke):"")."</td>";
                                           foreach($val as $k => $v) {
                                            $totAmt = 0;
                                            $totCount = 0;
                                            $lastEmpID = '';  
                                            echo (($preStatus==$k or !empty($preStatus))?"<tr><td align= 'center'>-</td><td align= 'center'>-</td>":"");
                                            echo "<td align= 'center'>".ucwords($k)."</td>";
                                            reset($dateArray);
                                           foreach ($dateArray as $dateKey => $dateVal) {
                                            $dateSp = explode('-',$dateVal);
                                            echo "<td align= 'center' height='40'>".(isset($v[$dateVal]['count']) && $v[$dateVal]['count']!=''?HTML::link('quote/elec_quote_list', number_format($v[$dateVal]['amount'],2) , array('target'=>'_blank','class'=>'btn btn-link')):"-")."</td>";
                                            if (!isset($v[$dateVal]['amount']))
                                               $v[$dateVal]['amount'] =0; 
                                            $totAmt = $totAmt+$v[$dateVal]['amount'];
                                            if (!isset($v[$dateVal]['count']))
                                              $v[$dateVal]['count'] =0;
                                            $totCount = $totCount+$v[$dateVal]['count'];
                                              if (isset($v[$dateVal]['employeeID']))
                                                $lastEmpID = $v[$dateVal]['employeeID'];
                                              }

                                            echo "<td align= 'center' height='40'>".($totCount!=''?HTML::link('quote/elec_quote_list', number_format($totAmt,2) , array('target'=>'_blank','class'=>'btn btn-link')):"-")."</td></tr>"; $preStatus = $k; }  echo "</tr>"; $preType = $ke; 
                                           } 
                                           echo "</tr>"; 
                                           $preEmp = $key; $fg=!$fg; 
                                          } ?>
                                      </tbody>
                                  </table><br/>
                                  {{ HTML::link("reports/excelDFRSExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
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
      $('#optEmployee').val("");
      $('#mStart').val("1");
      $('#yStart').val("{{date('Y')}}");
      $('#mEnd').val("{{date('m')}}".replace(/^0+/,'')); 
      $('#yEnd').val("{{date('Y')}}"); 
    });
});   
</script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>
@stop