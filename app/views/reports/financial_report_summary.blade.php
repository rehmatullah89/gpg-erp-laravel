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
                  FINANCIAL REPORT SUMMARY
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b>Search by:</b><i> Dates/ Fileters</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf','id'=>'submit_this_form' ,'url'=>route('reports/financial_report_summary'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                  <thead>
                                    <tr>
                                      <?php
                                         $reportYear = Input::get('y');
                                         if(empty($reportYear))
                                            $reportYear = date('Y');
                                      ?>
                                      <th><h4>{{$reportYear}}</h4></th>
                                      <th>{{Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}</th>
                                      <th>
                                        {{ Form::select('optEmployee',$salesp_arr,'', array('class' => 'form-control', 'id' => 'optEmployee')) }}
                                      </th>
                                      <th>
                                        <input type="submit" value="GO" class="form-control"></th><th>
                                        <input type="button" value="<<" onclick="get_pre_year_data" class="form-control"></th><th>
                                        <select name="y" id="change_me" class="form-control">
                                            <?php
                                              $year_display = 20;
                                              $thisyear = date('Y');
                                              for($year = $thisyear - $year_display; $year <= $thisyear + $year_display; $year++){
                                                $selected = ($year == $reportYear) ? " selected" : "";
                                                echo("<option value=\"$year\"$selected>".date('Y', mktime(0,0,0,1,1,$year))."</option>");
                                              }
                                            ?>
                                        </select></th><th>
                                        <input type="submit" value="GO" class="form-control"></th><th>
                                        <input type="button" value=">>" onclick="get_next_year_data" class="form-control">
                                      </th>
                                    </tr>
                                  </thead>
                                </table>
                              </section>
                            {{ Form::close() }}
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables">
                <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                  <tbody>
                    <tr bgcolor="#F2F2F2">
                      <?php 
                      $elecYearInvoiceTotal =0;
                      for ($i=0; $i<=12; $i++) { ?>
                        <td width="<?php echo ($i==0?200:105); ?>" height="30" align="center" class="smallblack"><?php echo ($i>0?date('F',strtotime($reportYear.'-'.$i.'-01')):'') ?></td>
                      <?php } ?>
                        <td width="150" align="center" class="smallblack" ><strong>Totals</strong></td>
                        <td width="150" align="center" class="smallblack" ><strong>Year Totals</strong></td>
                    </tr>
                    <?php 
                      for ($i=1; $i<=21; $i++) { 
                        $totalLine = 0;
                    ?>
                    <tr bgcolor="<?php echo ((($i>=4 && $i<=6) || ($i>=10 && $i<=12) || ($i>=16 && $i<=18))?"#FFFFCC":"#FFFFFF") ?>" >
                    <td height="35" class="smallblack" align="center"><?php echo $valuesMonth['label_'.$i] ?></td> 
                    <?php for ($j=1; $j<=12; $j++) { 
                      $spl = explode('~',$valuesMonth['ref_'.$i]);
                    ?>
                    <td class="smallblack"  align="center" ><?php  
                      echo HTML::link('job/financial_report/','$'.number_format(@$valuesMonth[$j][$spl[0]][$spl[1]],2), array('target'=>'_blank','class'=>'btn btn-link btn-xs'));
                      $totalLine += @$valuesMonth[$j][$spl[0]][$spl[1]];
                    if($j==18){
                      $elecYearInvoiceTotal = @$valuesMonth[$j][$spl[0]]['year'][$spl[1]];
                      }
                    ?>
                    </td>
                    <?php } ?>
                    <td align="center" class="smallblack" ><?php echo '$'.number_format($totalLine,2)?></td>
                    <td align="center" class="smallblack" >
                    <?php echo HTML::link('job/financial_report/','$'.number_format(@$elecYearInvoiceTotal,2), array('target'=>'_blank','class'=>'btn btn-link btn-xs')); ?></td>
                  </tr>
                    <?php if ($i==18) { ?>
                  <tr bgcolor="#F2F2F2">
                    <td  colspan="15" height="30" align="left" class="smallblack"><strong>&nbsp;&nbsp;TOTALS</strong></td>
                  </tr>
                  <?php } ?>
                  <?php } ?>
                   
                  </tbody>
                </table>
                <br/>
                {{ HTML::link("reports/excelFinRepSumryExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
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
    //$('#change_me').val($('#change_me').val());
    $('#get_pre_year_data').click(function(){
      var vl = $('#change_me').val();
      $('#change_me').val(vl-1);
      $('#submit_this_form').submit();
    });
    $('#get_next_year_data').click(function(){
      var vl = $('#change_me').val();
      $('#change_me').val(vl+1);
      $('#submit_this_form').submit();
    });
});   
</script>
<script src="{{asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/common-scripts.js')}}"></script>
@stop