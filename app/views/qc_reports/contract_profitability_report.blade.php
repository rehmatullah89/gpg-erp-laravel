@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<?php
function format_price($num){
  return "<span style='float:right'>".'$'.round_figure($num,1)."</span>";
}
function round_figure($num,$type=0){
  if($type==1)
    return number_format($num,2);
  else
    return "<span style='float:right'>".number_format($num,2)."%</span>";
}
?>
              <!-- page start-->
          <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">    
                 CONTRACT PROFITABILITY REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Dates/ Contract Number/ Regardings</i></b>
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
                                  <td data-title="Start Date:" style="width:12.5%;">
                                    {{Form::label('SDate', 'Invoice Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td>
                                  <td data-title="End Date:" style="width:12.5%;">
                                    {{Form::label('EDate', 'Invoice End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Contract Number Start:" style="width:12.5%;">
                                    {{Form::label('c_num', 'Contract Number Start:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::text('c_num','', array('class' => 'form-control', 'id' => 'c_num')) }}
                                    <sub>complete or partial Contract number</sub>
                                  </td>
                                  <td data-title="Contract Number End:" style="width:12.5%;">
                                    {{Form::label('c_num_end', 'Contract Number End:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::text('c_num_end','', array('class' => 'form-control', 'id' => 'c_num_end')) }}
                                  </td>
                                  <td data-title="Regarding:" style="width:12.5%;">
                                    {{Form::label('regard', 'Regarding:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::select('regard',array(''=>'All')+$arr_regarding,NULL, array('class' => 'form-control', 'id' => 'regard','multiple')) }}
                                  </td>
                                  <td style="width:12.5%;">
                                    {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                    {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                  </td>
                                </tr>
                            </tbody>
                          </table>
                          <table class="table table-bordered table-striped table-condensed cf" >
                            <tbody>
                              <tr>
                                <td>
                                  <select name="export_type" id="export_type" class="form-control">
                                    <option value="1">Detailed export</option>
                                      <option value="2">Comprehensive export</option>
                                       <option value="3">Labor Cost export</option>
                                  </select>
                                </td>
                                <td>{{Form::button('Generate Excel Report', array('class' => 'btn btn-success', 'id'=>'generate_report'))}} </td>
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
              <tr>
                <td>
                  <table class="table table-bordered table-striped table-condensed cf"><?php 
                  foreach($query_data as $k => $v) {
                    $sum_inv_amnt = 0;
                    $sum_cost_to_date = 0;
                    $total_material_cost = 0;
                    $total_labor_cost = 0;
                  ?>
              <tr id="contract_<?php echo $k?>">
                <th colspan="3" align="left" onclick="$('.data_<?php echo $k?>').toggle()" style="cursor:pointer"><?php echo $k?></th>
              </tr>
              <tr class="data_<?php echo $k?> mydathead" >
                <th width="20px" >&nbsp;</th>
                <th width="20px" >&nbsp;</th>
                <th>Regarding</th>
                <th>Sum of Inv'd Amount Net</th>
                <th>Sum of Cost to Date</th>
                <th>Sum of Gross Profit / (Loss)</th>
                <th>Sum of % Margin</th>
              </tr><?php 
                $short = 0;
                if(sizeof($v)==0) {
                  ?><tr class="data_<?php echo $k?> mydat" ><td colspan="7" bgcolor="#FFFFCC">NO DATA</td></tr><?php 
                }
                else{
                  foreach($v as $k1=> $v1) {
                ?>
                <tr class="data_<?php echo $k?> mydat" >
                  <th width="20px" bgcolor="#FFC1C1">&nbsp;</th>
                  <th colspan="8" align="left"><?php echo $k1?></th>
                </tr><?php 
                foreach($v1 as $k2 => $v2) {
                ?>
                <tr  class="data_<?php echo $k?> mydat">
                  <td width="20px" bgcolor="#FFFFCC" >&nbsp;</td>
                  <td width="20px" bgcolor="#FFFFCC" >&nbsp;</td><?php 
                $sum_inv_amnt+= $v2['inv_amnt'];
                $sum_cost_to_date += $v2['cost_to_date'];
                $total_material_cost += $v2['material_cost'];
                $total_labor_cost += $v2['labor_cost'];
                ?>
                <td><?php echo $v2['regarding']?></td>
                <td><?php echo format_price($v2['inv_amnt'])?></td>
                <td>
                  <table class="table table-bordered table-striped table-condensed cf">
                    <tr>
                      <td>
                        <p class="phide border_1"><a href="javascript:;" onClick="callInvoice('<?php  echo $v2['contract_number']; ?>','invoiceAmountDiv','<?php echo $v2['regarding'] ?>', 'material','<?php echo $k1; ?>'); this.className='highlight'; return false;">
                        <span class="left">Material Cost</span>
                        <?php echo format_price($v2['material_cost'])?>
                        </a><br />
                        <a href="javascript:;" onClick="callInvoice('<?php  echo $v2['contract_number']; ?>','invoiceAmountDiv','<?php echo $v2['regarding'] ?>', 'labor', '<?php echo $k1; ?>'); this.className='highlight'; return false;">
                        <span class="left">Labor Cost</span>
                        <?php echo format_price($v2['labor_cost'])?>
                        </a></p>
                      </td>
                    </tr>
                    <tr>
                      <td class="bold">
                        <span class="total">Total</span>
                        <?php echo format_price($v2['cost_to_date'])?>
                      </td>
                    </tr>
                  </table>
                </td>
                <td><?php echo format_price($v2['profit'])?></td>
                <td><?php echo round_figure($v2['margin_percent'])?></td>
              </tr><?php  }                         
                  }?>
                  <script type="text/javascript" language="javascript"><?php 
                    $profit_total = $sum_inv_amnt - $sum_cost_to_date;
                    $margin_percent = ($sum_inv_amnt>0?(($profit_total / $sum_inv_amnt)*100):0);
                  ?>
                  html = "<th><span style='color:#999'>Sum of Invoiced Amount</span><?php echo format_price($sum_inv_amnt)?></th><th><table class='table table-bordered table-striped table-condensed cf'>";
                  html += "<tr><th><div class=\"phide\"><a href=\"javascript:;\" onClick=\"callInvoice('<?php  echo $k; ?>','invoiceAmountDiv','<?php echo $regardImplode; ?>', 'material',''); this.className=''; return false;\"><span class=\"material_cost\"><span>Material Cost</span><?php echo format_price($total_material_cost)?></span></a><br /><a href=\"javascript:;\" onClick=\"callInvoice('<?php  echo $k; ?>','invoiceAmountDiv','<?php echo $regardImplode; ?>', 'labor', ''); this.className=''; return false;\"><span class=\"material_cost\"><span>Labor Cost</span><?php echo format_price($total_labor_cost)?></span></a></div></th></tr>";
                  html += "<tr onclick=\"$(this).prev().find('.phide').slideToggle();\" class=\"links\"><th><span><a href='javascript:;'>Sum of Cost to Date</a></span><?php echo format_price($sum_cost_to_date)?></th></tr></table></th><th><span style='color:#999'>Sum of Gross Profit / (Loss)</span><?php echo format_price($profit_total)?></th><th><span style='color:#999'>Sum of % Margin</span><?php echo round_figure($margin_percent)?></th>";
                  $('#contract_<?php echo $k?>').append(html);
                  </script>
                      <?php 
                    }
                  }
                ?>
              </table>
              </td>
            </tr>
          </table> 
          {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
          <br/>
          {{ HTML::link("qc_reports/excelCustJobDetailRepExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
          </section>
         </div>
        </div>      
      </div>
    {{ HTML::link("qc_reports/contractProfitablityExport?".http_build_query(array_filter(Input::except('_token', 'page'))), '' , array('class'=>'btn btn-success','id'=>'link1'))}}
               
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
          $('#c_num').val("");
          $('#c_num_end').val("");
          $('#regard').val("NULL");
      });
      $('#generate_report').click(function(){
        var selval = $('#export_type option:selected').val();
        $('#link1')[0].click();
    });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop