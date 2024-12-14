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
                  ENGINE kW PRICING 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>  Dates / Filters</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                              <?php
                                $range_start = Input::get("range_start");
                                $range_end = Input::get("range_end");
                                $interval = Input::get("interval");
                                $start_date = Input::get("start_date");
                                $end_date = Input::get("end_date");
                                $engine_filter = Input::get("engine_filter");
                                if(empty($start_date))
                                  $start_date = "2006-01-01";
                                if(empty($end_date))
                                  $end_date = date("Y-m-d", strtotime("-1 month") ) ;
                                if(empty($range_start))
                                  $range_start = 0;
                                if(empty($range_end))
                                  $range_end = 3000;
                                if(empty($interval))
                                  $interval = 500;
                              ?>
                                  <td data-title="Contract Start Date:">
                                    {{Form::label('start_date', 'Contract Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('start_date',$start_date, array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'start_date')) }}
                                   </td>
                                   <td data-title="Contract End Date:">
                                    {{Form::label('end_date', 'Contract End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('end_date',$end_date, array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'end_date')) }}
                                   </td>
                                   <td data-title="kW Range Start:">
                                    {{Form::label('range_start', 'kW Range Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('range_start',$range_start, array('class' => 'form-control', 'id' => 'range_start')) }}
                                   </td>
                                   <td data-title="kW Range End:">
                                    {{Form::label('range_end', 'kW Range End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('range_end',$range_end, array('class' => 'form-control', 'id' => 'range_end')) }}
                                   </td>
                                   <td data-title="Range Interval:">
                                    {{Form::label('interval', 'Range Interval:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('interval',$interval, array('class' => 'form-control', 'id' => 'interval')) }}
                                   </td>
                                   <td data-title="Show contracts having:">
                                    {{Form::label('engine_filter', 'Show contracts having:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::select('engine_filter',array(''=>'ALL','both'=>'Both Generator and Engine Info','no_engine'=>'No Engine Info','no_generator'=>'No Generator Info'),$engine_filter, array('class' => 'form-control', 'id' => 'engine_filter')) }}
                                   </td>
                                </tr>
                            </tbody>
                          </table>
                          {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                          {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
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
                <tbody>
                  <tr>
                    <td colspan="<?php echo sizeof($arr_eng)+1?>" bgcolor="#FFFFFF" height="30px"></td>
                  </tr>
                  <tr>
                    <td width="200px" height="30" bgcolor="#EEEEEE"><div align="center"><strong>kW Range</strong></div></td>
                    <?php
                    foreach($arr_eng as $key => $value) {
                    ?>
                      <td width="400px" height="30" bgcolor="#EEEEEE"><div align="center"><strong><?php echo str_replace("~","<br>",$value)?></strong></div></td>
                    <? } ?>
                  </tr>
                  <? 
                  $bgcolor = "#FFFFFF";
                  $srno = 1;
                  if(sizeof($kw_array)>0) {
                    foreach($kw_array as $key => $value) {
                      $srno++;
                  ?>
                    <tr bgcolor="<?php echo $bgcolor?>" height="25px">
                      <td id="<?php echo $srno?>_0_cell"><?php echo $value["range"];?></td><?
                      $sr_no_col = 0;
                      $inv_sum = 0;
                      foreach($value["engines"] as $keys => $values) {
                        $inv_sum = 0;
                        $sr_no_col++;
                        $temp_gen_name_arr = explode('~',$keys);                
                        $str_contracts = ""; 
                        if(is_array(@$values['contract_num'])) {
                          foreach(@$values['contract_num'] as $cont_nums => $cont_nums2) {
                            $inv_sum0 = DB::select(DB::raw("SELECT SUM(gpg_job_invoice_info.invoice_amount) as invc_sum
                                        FROM
                                        gpg_job gj,
                                        gpg_job_invoice_info
                                        WHERE
                                        gpg_job_invoice_info.job_num = gj.job_num
                                        AND gj.contract_number = '".$cont_nums."'
                                        AND (gj.job_num LIKE 'BO%' OR gj.job_num LIKE 'PM%')"));
                            $inv_sum += @$inv_sum0[0]->invc_sum;
                            $str_contracts .= $cont_nums."_";
                          }
                          $str_contracts = substr($str_contracts,0,strlen($str_contracts)-1);
                        }
                        $total_sum = number_format(@$values["sum_cost"]+@$values["sum_contract"]+$inv_sum+@$values["lab_sum"]+@$values["mat_sum"],2);
                    ?>
                    <td id="<?php echo $srno."_".$sr_no_col?>_cell" align="center"  onmousemove="<? if($total_sum>0){?>DG('divTaskDetail').innerHTML = DG('div<?php echo $srno?>_<?php echo $sr_no_col?>').innerHTML;showDiv();<? }?>cross_highlight(this);" onmouseout="closeDiv();cross_highlight_clear(this)">
                    {{ HTML::link('job/service_job_list',($total_sum>0?$total_sum:""), array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}} 
                    <div style="display:none" id="div<?php echo $srno?>_<?php echo $sr_no_col?>">
                    <table width="100%">
                      <tr>
                        <td><strong>Generator</strong></td><td><strong><?php echo strlen($temp_gen_name_arr[0])==0?"-":$temp_gen_name_arr[0]?></strong></td>
                      </tr>
                      <tr>
                        <td><strong>Engine</strong></td><td><strong><?php echo strlen($temp_gen_name_arr[1])==0?"-":$temp_gen_name_arr[1]?></strong></td>
                      </tr>
                      <tr>
                        <td><strong>kW Range</strong></td><td><strong><?php echo $value["range"]?></strong></td>
                      </tr>
                      <tr>
                        <td>Cost to Date</td><td><?php echo number_format(@$values["sum_cost"],2)?></td>
                      </tr>
                      <tr>
                        <td>Contract Amount</td><td><?php echo number_format(@$values["sum_contract"],2)?></td>
                      </tr>
                      <tr>
                        <td>Invoice</td><td><?php echo number_format($inv_sum,2)?></td>
                      </tr>
                      <tr>
                        <td width="50%">Labor Cost</td><td width="50%"><?php echo number_format(@$values["lab_sum"],2)?></td>
                      </tr>
                      <tr>
                        <td>Material Cost</td><td><?php echo number_format(@$values["mat_sum"],2)?></td>
                      </tr>
                    </table>
                  </div>
                  </td><? }?>
                  </tr> <? } } ?>
                </tbody>
              </table>
              {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
              <br/>
              {{ HTML::link("qc_reports/excelEngineKWPricingExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
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
          $('#start_date').val("");
          $('#end_date').val("");
          $('#range_start').val("");
          $('#range_end').val("");
          $('#interval').val("");
          $('#engine_filter').val("");
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop