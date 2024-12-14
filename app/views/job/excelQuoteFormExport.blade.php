            <table class="table table-bordered table-striped table-condensed cf">
              <tbody class="cf">
                  <tr>
                    <td data-title="Job Number:">Electrical Qt #: {{$job_num}}</td>
                   <td data-title="Po Number:">Po Number:{{$jobElectricalQuoteTblRow['po_number']}}</td> 
                   <td data-title="Status:">Status:<br/> <span style="color:red; font-weight:bold;"> {{$jobElectricalQuoteTblRow['electrical_status']}}</span></td>     
                  <td data-title="Date:">Date:{{($jobElectricalQuoteTblRow['schedule_date']!=""?date('Y-m-d',strtotime($jobElectricalQuoteTblRow['schedule_date'])):date('Y-m-d'))}}</td>
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
                                         Job Site Address
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

                 <table class="table table-bordered table-striped table-condensed cf"><tr><td>
                     <table class="table table-bordered table-striped table-condensed cf">
                        <tbody class="cf">
                            <tr><td>Generator:</td><td >{{$jobElectricalQuoteTblRow['gen_cost']}}</td></tr>
                            <tr><td>Automatic Transfer Switch:</td><td >{{$jobElectricalQuoteTblRow['gen_cost']}}</td></tr>
                            <tr><td>Materials Cost:</td><td >{{$jobElectricalQuoteTblRow['material_cost']}}</td></tr>
                            <tr><td>Total Hours @ $:&nbsp;{{$jobElectricalQuoteTblRow['labor_hour_rate']}}</td><td >{{$jobElectricalQuoteTblRow['labor_cost']}}</td></tr>
                            <tr><td>Miscellaneous:&nbsp;{{$jobElectricalQuoteTblRow['misc_percent']}}</td><td >{{$jobElectricalQuoteTblRow['misc_cost']}}</td></tr>
                            <tr><td><b>Total: </b></td><td >{{$jobElectricalQuoteTblRow['general_net_total']}}</td></tr>
                            <tr><td><b>Margin:&nbsp;{{$jobElectricalQuoteTblRow['general_margin']}}</b></td><td >{{$jobElectricalQuoteTblRow['general_margin']}}</td></tr>
                            <tr><td><b>Net Total: </b></td><td >{{$jobElectricalQuoteTblRow['general_net_total']}}</td></tr>
                            <tr><td  align="center"><b>Delivery Cost</b></td></tr>
                            <tr><td><b>Labor Hrs/$:&nbsp;{{$jobElectricalQuoteTblRow['delivery_labor_hour_rate']}}</b></td><td>{{$jobElectricalQuoteTblRow['delivery_labor_hour']}}</td><td>{{$jobElectricalQuoteTblRow['delivery_labor_hour_total']}}</td></tr><tr><td><b>Mileage/$:&nbsp;{{$jobElectricalQuoteTblRow['delivery_mileage_rate']}}</b></td><td>{{$jobElectricalQuoteTblRow['delivery_mileage']}}</td><td>{{$jobElectricalQuoteTblRow['delivery_mileage_total']}}</td></tr>
                         </tbody>
                      </table>
                  </td>
                  <td>
                    <table class="table table-bordered table-striped table-condensed cf">
                        <tbody class="cf">
                            <tr><td>Generator:</td><td >{{$jobElectricalQuoteTblRow['freight_gen_cost']}}</td></tr>
                            <tr><td>ATS:</td><td >{{$jobElectricalQuoteTblRow['freight_ats_cost']}}</td></tr>
                            <tr><td>Housing:</td><td >{{$jobElectricalQuoteTblRow['freight_housing_cost']}}</td></tr>
                            <tr><td>Tank:</td><td >{{$jobElectricalQuoteTblRow['freight_tank_cost']}}</td></tr>
                            <tr><td>Accessories:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$</td><td >{{$jobElectricalQuoteTblRow['freight_acc_cost']}}</td></tr>
                            <tr><td><b>Total: </b></td><td >{{$jobElectricalQuoteTblRow['freight_total_cost']}}</td></tr>
                            <tr><td>&nbsp;</td><td >{{''}}</td></tr>
                            <tr><td>&nbsp;</td><td >{{''}}</td></tr>
                            <tr><td  align="center"><b>Start Up Cost</b></td></tr>
                            <tr><td><b>Labor Hrs/$:{{$jobElectricalQuoteTblRow['startup_labor_hour_rate']}}</b></td><td>{{$jobElectricalQuoteTblRow['startup_labor_hour']}}</td><td>{{$jobElectricalQuoteTblRow['startup_labor_hour_total']}}</td></tr>
                            <tr><td><b>Mileage/$:{{$jobElectricalQuoteTblRow['startup_mileage_rate']}}</b></td><td>{{$jobElectricalQuoteTblRow['startup_mileage']}}</td><td>{{$jobElectricalQuoteTblRow['startup_mileage_total']}}</td></tr>
                        </tbody>
                      </table>
                  </td>
                  <td>
                    <table class="table table-bordered table-striped table-condensed cf">
                        <tbody class="cf">
                            <tr><td >Generator & ATS:</td><td >{{$jobElectricalQuoteTblRow['gen_ats_total']}}</td></tr>
                            <tr><td >Miscellaneous:</td><td  >{{$jobElectricalQuoteTblRow['misc_total']}}</td></tr>
                            <tr><td >Materials:</td><td  >{{$jobElectricalQuoteTblRow['material_total']}}</td></tr>
                            <tr><td >Labor:</td><td  >{{$jobElectricalQuoteTblRow['labor_total']}}</td></tr>
                            <tr><td >Delivery Labor & Milleage:</td><td  >{{$jobElectricalQuoteTblRow['delivery_total']}}</td></tr>
                            <tr><td >Start Up:</td><td  >{{$jobElectricalQuoteTblRow['startup_total']}}</td></tr>
                            <tr><td >Freight:{{$jobElectricalQuoteTblRow['freight_factor']}}</td><td  >{{$jobElectricalQuoteTblRow['freight_total']}}</td></tr>
                        </tbody>
                    </table>
                  </td>
                  </tr></table>
                
                  <section id="no-more-tables">
                            <table class="table table-bordered table-striped table-condensed cf" id="otherTable">
                                        <thead class="cf">
                                        <tr><th>&nbsp;&nbsp;QTY</th><th>Description</th><th>Cost Price</th><th>Other Charges</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                        <?php $i=0;?>
                                        @foreach($quote_other_info as $queryOtherChargeResRow)
                                        <tr><?php $i++;?>
                                        <td data-title="Qty">
                                       {{$queryOtherChargeResRow['other_charge_qty']}}
                                        </td><td data-title="Description:"><?php if(!in_array($queryOtherChargeResRow['other_charge_description'],array("Mileage","Freight"))) { ?>
                                        {{$queryOtherChargeResRow['other_charge_description']}}
                                        <?php } else { echo $queryOtherChargeResRow['other_charge_description']; } ?></td>
                                        <td data-title="Coast Price:">
                                        {{$queryOtherChargeResRow['other_charge_cost_price']}}</td><td  data-title="Other Charges:" style='width:300px;'> {{$queryOtherChargeResRow['other_charge_qty']}}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                              </table>
                    </section>
                <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td> Scope Of Work</td><td>Exclusions</td><td>Total Cost </td><td > {{$jobElectricalQuoteTblRow['cost_gross_total']}}</td></tr>
                                        <tr><td rowspan="3">{{$jobElectricalQuoteTblRow['scope_of_work']}}</td><td rowspan="3">{{$jobElectricalQuoteTblRow['exclusions']}}</td><td>Total Margin</td><td >{{$jobElectricalQuoteTblRow['margin_gross_total']}}</td></tr>
                                        <tr><td>TOTAL Sale with out Tax</td><td >{{$jobElectricalQuoteTblRow['grand_total_no_tax']}}</td></tr>
                                        <tr><td>Sales Tax  %:{{number_format((empty($jobElectricalQuoteTblRow['tax_amount'])?7.75:$jobElectricalQuoteTblRow['tax_amount']),2)}}</td><td >{{$jobElectricalQuoteTblRow['tax_cost_total']}}</td></tr>
                                         <tr><td >&nbsp;</td><td>TOTAL Sale with Tax :</td><td >{{$jobElectricalQuoteTblRow['grand_total']}}</td></tr>
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