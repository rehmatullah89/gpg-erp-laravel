<section id="main-content">
      <section id="wrapper">
         

                <table class="table table-bordered table-striped table-condensed cf">
                    <tbody class="cf">
		              <tr>
		              	<td data-title="Job Number:"><?php echo str_replace('_', ' ', ucfirst($table));?> Qt #: {{$job_num}}</td>
                   <td data-title="Go to:">Go to:<?php print_r($quote_ids_arr); ?></td>  
                   <td data-title="Po Number:">Po Number:{{$jobElectricalQuoteTblRow['po_number']}}</td> 
                   <td data-title="Status:">Status:<br/><span style="color:red; font-weight:bold;"> {{$jobElectricalQuoteTblRow['jobTypeStatus']}}</span></td>     
		              <td data-title="Date:">Date:{{($jobElectricalQuoteTblRow['schedule_date']!=""?date('Y-m-d',strtotime($jobElectricalQuoteTblRow['schedule_date'])):date('Y-m-d'))}}</td>
		              <td data-title="Time:">Time: <div class="input-group bootstrap-timepicker">{{$jobElectricalQuoteTblRow['schedule_time']}} </div></td>
                  </tr>
                  <tr>
                    <td data-title="Stage:">Stage:{{$jobElectricalQuoteTblRow["qote_stage_id"]}}</td>
                    <td data-title="Job Type:">Job Type:{{(isset($jobElectricalQuoteTblRow["quote_type"])?$jobElectricalQuoteTblRow["quote_type"]:'-')}}</td>
                    <td data-title="Prob.%:">Prob.%:{{($jobElectricalQuoteTblRow['probability'])?$jobElectricalQuoteTblRow['probability']:''}}</td>
                    <td data-title="Electrical Qt. Status:"><?php echo str_replace('_', ' ', ucfirst($table));?> Qt. Status:{{(isset($jobElectricalQuoteTblRow['quote_status'])?$jobElectricalQuoteTblRow['quote_status']:'-')}}</td>
                    <td data-title="Est. Close Date:" colspan="2">Est. Close Date:{{($jobElectricalQuoteTblRow['estimated_close_date']!=""?date('Y-m-d',strtotime($jobElectricalQuoteTblRow['estimated_close_date'])):'')}}</td>
                  </tr>
                    </tbody>
                   </table>
              
                <div class="row"  id="show_hide_billing_info">
                  <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Customer Billing Address 
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Customer Name:</td><td colspan="3">{{$jobElectricalQuoteTblRow['GPG_customer_id']}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address 1:</td><td>{{$jobElectricalQuoteTblRow['customer_info']['address']}}</td><td style="background-color:#FFFFCC;">Address 2:</td><td>{{$jobElectricalQuoteTblRow['customer_info']['address2']}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">City:</td><td>{{$jobElectricalQuoteTblRow['customer_info']['city']}}</td><td style="background-color:#FFFFCC;">State:</td><td>{{$jobElectricalQuoteTblRow['customer_info']['state']}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Zip:</td><td>{{$jobElectricalQuoteTblRow['customer_info']['zipcode']}}</td><td style="background-color:#FFFFCC;">Phone:</td><td>{{$jobElectricalQuoteTblRow['customer_info']['phone_no']}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Sales Person:</td><td>{{$jobElectricalQuoteTblRow['GPG_customer_id']}}
                                        </td><td style="background-color:#FFFFCC;">Estimator:</td><td>{{$jobElectricalQuoteTblRow['GPG_estimator_id']}}
                                        </td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>        
                          </div>
                        </div>
                      </section>
                    </div>
                     <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Job Site Address [{{Form::button('COPY CUSTOMER DATA', array('onClick'=>'autoFill();','class' => 'btn btn-link btn-xs'))}}]
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Project Name:</td><td colspan="3">{{$jobElectricalQuoteTblRow['project_name']}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address:</td><td>{{$jobElectricalQuoteTblRow['project_address']}}</td><td style="background-color:#FFFFCC;">City:</td><td>{{$jobElectricalQuoteTblRow['project_city']}}</td></tr><tr><td style="background-color:#FFFFCC;">State:</td><td>{{$jobElectricalQuoteTblRow['project_state']}}</td><td style="background-color:#FFFFCC;">Zip:</td><td>{{$jobElectricalQuoteTblRow['project_zip']}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Contact:</td><td>{{$jobElectricalQuoteTblRow['project_contact']}}</td><td style="background-color:#FFFFCC;">Phone:</td><td>{{$jobElectricalQuoteTblRow['project_phone']}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Estimator Address:</td><td>{{$jobElectricalQuoteTblRow["contact_info_id"]}}
                                        </td><td style="background-color:#FFFFCC;">Terms and Conditions:</td><td>{{$jobElectricalQuoteTblRow['terms_and_conditions_id']}}
                                        </td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                        </div>
                      </section>
                    </div>
                  </div>  
               <div class="row">
                  <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="Equipment">
                                        <thead class="cf">
                                        <tr><th colspan="13">Equipments</th></tr>
                                        <tr><th>Del</th><th>Vendor</th><th>Quantity</th><th>Description</th><th>Cost</th><th>Sell Price</th><th>Margin</th><th>Calculated Sell Price</th><th>Total Cost</th><th>Margin $</th><th>Taxable?</th><th>Inc. in Bill of Mat.</th><th>Order</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                        <?php $i=0;?>
                                        @foreach($getElectricalEquipmentPricing as $getEEProw)
                                        <tr><?php $i++;?>
                                        <td data-title="Del">Del</td>
                                        <td data-title="">
                                          {{$getEEProw['gpg_vendor_id']}}
                                        </td>
                                        <td data-title="Eqp Qnty:">{{$getEEProw['equipment_quantity']}}</td>
                                        <td data-title="Eqp. Desc:">{{$getEEProw['equipment_description']}}</td>
                                        <td data-title="Cost:">{{$getEEProw['equipment_cost']}}</td>
                                        <td data-title="Sell Price:">{{(($getEEProw['equipment_sell_price_cost']=='0.00')?'':number_format($getEEProw['equipment_sell_price_cost'],2))}}</td>
                                        <td data-title="Margin:">{{(($getEEProw['equipment_margin_percent']=='0.00')?'':$getEEProw['equipment_margin_percent'])}}</td>
                                        <td data-title="Eqp Sell Price:">{{number_format($getEEProw['equipment_sell_price'],2)}}</td>
                                        <td data-title="">{{number_format($getEEProw['equipment_total_cost'],2)}}</td>
                                        <td data-title="Margin $:">{{number_format($getEEProw['equipment_margin'],2)}}</td>
                                        <td data-title="Taxable:">{{($getEEProw['equipment_include_tax']=='1')?1:0}}</td>
                                        <td data-title="Inc. in Bill:">{{$getEEProw['id']}}</td>
                                        <td data-title="Order:">{{$getEEProw['equipment_order']}}</td>
                                        </tr>
                                        @endforeach
                                        <tr><td colspan="7"></td>
                                        <td>{{(isset($Totals['equipment_sell_price_total'])?$Totals['equipment_sell_price_total']:'')}}</td>
                                          <td>{{(isset($Totals['equipment_total_cost_total'])?number_format($Totals['equipment_total_cost_total'],2):'')}}</td>
                                          <td>{{(isset($Totals['equipment_margin_total'])?number_format($Totals['equipment_margin_total'],2):'')}}</td><td></td><td></td><td></td>
                                        </tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </section>
                  </div>
                  <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="Labor">
                                        <thead class="cf">
                                        <tr><th colspan="11">Labor</th></tr>
                                        <tr><th>Del</th><th>Quantity</th><th>Description</th><th>Cost:{{Form::text('labor_cost','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'labor_cost'])}}</th><th>Sell Price</th><th>Margin:{{Form::text('labor_margin','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'labor_margin'])}}</th><th>Calculated Sell Price</th><th>Total Cost</th><th>Margin $</th><th>Inc. in Bill of Mat.</th><th>Order</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                        <?php $i=0;?>
                                        @foreach($getELP as $getELProw)
                                        <tr><?php $i++;?>
                                         {{Form::hidden('Labor_id_'.$i,$getELProw['id'])}}
                                        <td data-title="Del"></td>
                                        <td data-title="Lbr Qnty:">{{$getELProw['labor_quantity']}}</td>

                                        <td data-title="Eqp. Desc:">{{$getELProw['labor_description']}}</td>

                                        <td data-title="Cost:">{{$getELProw['labor_cost']}}</td>

                                        <td data-title="Sell Price:">{{(($getELProw['labor_sell_price_cost']=='0.00')?'':number_format($getELProw['labor_sell_price_cost'],2))}}</td>

                                        <td data-title="Margin:">{{(($getELProw['labor_margin_percent']=='0.00')?'':$getELProw['labor_margin_percent'])}}</td>

                                        <td data-title="Eqp Sell Price:">{{number_format($getELProw['labor_sell_price'],2)}}</td>
                                        <td data-title="">{{number_format($getELProw['labor_total_cost'],2)}}</td>

                                        <td data-title="Margin $:">{{number_format($getELProw['labor_margin'],2)}}</td>

                                        <td data-title="Inc. in Bill:">{{$getELProw['id']}}</td>

                                        <td data-title="Order:">{{$getELProw['labor_order']}}</td>
                                        </tr>
                                        @endforeach
                                        <tr><td colspan="6"></td>
                                        <td>{{(isset($Totals['labor_sell_price_total'])?number_format($Totals['labor_sell_price_total'],2):'')}}</td>
                                          <td>{{(isset($Totals['labor_total_cost_total'])?number_format($Totals['labor_total_cost_total'],2):'')}}</td>
                                          <td>{{(isset($Totals['labor_margin_total'])?number_format($Totals['labor_margin_total'],2):'')}}</td><td></td><td></td>
                                        </tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </section>
                  </div>
                   <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="Misc">
                                        <thead class="cf">
                                        <tr><th colspan="13">Misc</th></tr>
                                        <tr><th>Del</th><th>Vendor</th><th>Quantity</th><th>Description</th><th>Cost:{{Form::text('misc_cost','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'misc_cost'])}}</th><th>Sell Price</th><th>Margin:{{Form::text('misc_margin','', ['class'=>'form-control','style'=>'width:40px; display:inline;','id'=>'misc_margin'])}}</th><th>Calculated Sell Price</th><th>Total Cost</th><th>Margin $</th><th>Taxable?</th><th>Inc. in Bill of Mat.</th><th>Order</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                        <?php $i=0;?>
                                        @foreach($getEMP as $getEMProw)
                                        <tr><?php $i++;?>
                                        <td data-title="Del"></td>
                                        <td data-title="">
                                          {{$gpg_vendor[$getEMProw['gpg_vendor_id']]}}
                                        </td>
                                        <td data-title="Eqp Qnty:">{{$getEMProw['misc_quantity']}}</td>
                                        <td data-title="Eqp. Desc:">{{$getEMProw['misc_description']}}</td>
                                        <td data-title="Cost:">{{$getEMProw['misc_cost']}}</td>
                                        <td data-title="Sell Price:">{{(($getEMProw['misc_sell_price_cost']=='0.00')?'':number_format($getEMProw['misc_sell_price_cost'],2))}}</td>
                                        <td data-title="Margin:">{{(($getEMProw['misc_margin_percent']=='0.00')?'':$getEMProw['misc_margin_percent'])}}</td>
                                        <td data-title="Eqp Sell Price:">{{number_format($getEMProw['misc_sell_price'],2)}}</td>
                                        <td data-title="">{{number_format($getEMProw['misc_total_cost'],2)}}</td>
                                        <td data-title="Margin $:">{{number_format($getEMProw['misc_margin'],2)}}</td>
                                        <td data-title="Taxable:">{{($getEMProw['misc_include_tax']=='1')?1:0}}</td>
                                        <td data-title="Inc. in Bill:">{{$getEMProw['id']}}</td>
                                        <td data-title="Order:">{{$getEMProw['misc_order']}}</td>
                                        </tr>
                                        @endforeach
                                        <tr><td colspan="7"></td>
                                        <td>{{(isset($Totals['misc_sell_price_total'])?number_format($Totals['misc_sell_price_total'],2):'')}}</td>
                                        <td>{{(isset($Totals['misc_total_cost_total'])?number_format($Totals['misc_total_cost_total'],2):'')}}</td>
                                        <td>{{(isset($Totals['misc_margin_total'])?number_format($Totals['misc_margin_total'],2):'')}}</td><td></td><td></td><td></td>
                                        </tr>
                                        <?php
                                        $grand_total_sale_price  = (isset($Totals['equipment_sell_price_total'])?$Totals['equipment_sell_price_total']:0)+(isset($Totals['labor_sell_price_total'])?$Totals['labor_sell_price_total']:0)+(isset($Totals['misc_sell_price_total'])?$Totals['misc_sell_price_total']:0);
                                        $grand_total_cost = (isset($Totals['equipment_total_cost_total'])?$Totals['equipment_total_cost_total']:0)+(isset($Totals['labor_total_cost_total'])?$Totals['labor_total_cost_total']:0)+(isset($Totals['misc_total_cost_total'])?$Totals['misc_total_cost_total']:0);
                                        $grand_total_margin = (isset($Totals['equipment_margin_total'])?$Totals['equipment_margin_total']:0) + (isset($Totals['labor_margin_total'])?$Totals['labor_margin_total']:0) + (isset($Totals['misc_margin_total'])?$Totals['misc_margin_total']:0);
                                        $sale_price_tax = ((isset($Totals['sales_tax'])?$Totals['sales_tax']:0)/100) * $TotalTaxable;
                                        ?>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </section>
                  </div>
              <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td> Scope Of Work</td><td>Exclusions</td><td>SUBTOTAL</td><td > {{$grand_total_sale_price}}</td><td>{{$grand_total_cost}}</td><td>{{$grand_total_margin}}</td></tr>
                                        <tr><td rowspan="3">{{$jobElectricalQuoteTblRow['scope_of_work']}}</td><td rowspan="3">{{$jobElectricalQuoteTblRow['exclusions']}}</td><td>SALES TAX {{(isset($Totals['sales_tax'])?$Totals['sales_tax']:0)}}</td><td >{{number_format($sale_price_tax)}}</td><td>{{number_format($sale_price_tax)}}</td><td>{{''}}</td></tr>
                                        <tr><td>SUBTOTAL:</td><td>{{number_format($sale_price_tax + $grand_total_sale_price,2)}}</td><td>{{number_format($sale_price_tax + $grand_total_cost,2)}}</td><td>{{number_format($grand_total_margin,2)}}</td></tr>
                                        <tr><td colspan="4">{{''}}</td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </section>
                  </div>
              </div>       
    </section>
  </section>