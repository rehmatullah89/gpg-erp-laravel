		<table class="table table-bordered table-striped table-condensed cf">
            <tbody class="cf">
               <tr>
               	<td style="background-color:#FFFFCC;">Customer Name:</td><td>{{$jobElectricalQuoteTblRow['GPG_customer_id']}}</td>
				<td style="background-color:#FFFFCC;">Address:</td><td>{{$jobElectricalQuoteTblRow['customer_info']['address']}}</td>               	
               </tr>
            </tbody>
        </table>
         <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                  <!--#1 -->            <tr><td colspan="2"><b>Additional Costs</b></td><td colspan="2"><b>Sale Summary Info</b></td><td colspan="2"><b>SDGE Rebate Summary</b></td><td colspan="2"><b>Totals Summary</b></td></tr>

                  <!--#2 -->            <tr><td style="background-color:#FFFFCC;">other mat- qu dis & wags:</td><td>{{$jobElectricalQuoteTblRow['other_wages']}}</td><td style="background-color:#FFFFCC;">Listed Material Cost:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Incentive Total:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Total Project Cost:</td><td>{{''}}</td></tr>

                  <!--#3 -->            <tr><td style="background-color:#FFFFCC;">Disposal:</td><td>{{$jobElectricalQuoteTblRow['disposal']}}</td><td style="background-color:#FFFFCC;">Material Margin:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Incentive Rate / Kw Saved:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Annual Energy Saving:</td><td>{{''}}</td></tr>

                  <!--#4 -->            <tr><td style="background-color:#FFFFCC;">Clean Up:</td><td>{{$jobElectricalQuoteTblRow['clean_up']}}</td><td style="background-color:#FFFFCC;">Sale Price of Materials:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">SPC On-Peak Demand Reduction Incentive:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Estimated Utility Incentive:</td><td>{{''}}</td></tr>

                  <!--#5 -->            <tr><td style="background-color:#FFFFCC;">Lift Rental:</td><td>{{$jobElectricalQuoteTblRow['lift_rental']}}</td><td style="background-color:#FFFFCC;">Additional Costs:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Qualifying kW Reduction {{(!empty($jobElectricalQuoteTblRow['reduction_constant'])?$jobElectricalQuoteTblRow['reduction_constant']:'0')}}:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Amount to be paid through OBF:</td><td>{{''}}</td></tr>

                  <!--#6 -->            <tr><td style="background-color:#FFFFCC;">Total:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Sale Price Labour:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Rate/ kw:</td><td>{{$jobElectricalQuoteTblRow['rate_per_kw']}}</td><td style="background-color:#FFFFCC;">Project Payback in Months:</td><td>{{''}}</td></tr>

                  <!--#7 -->            <tr><td colspan="2">&nbsp;</td><td style="background-color:#FFFFCC;">Applicable Sales Tax {{!empty($jobElectricalQuoteTblRow['sales_tax'])?$jobElectricalQuoteTblRow['sales_tax']:''}}:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Rebate Total:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Project Payback in Years:</td><td>{{''}}</td></tr>

                  <!--#8 -->            <tr><td colspan="2">&nbsp;</td><td style="background-color:#FFFFCC;">Sale Price:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Total SDGE Incentive with OBF{{empty($jobElectricalQuoteTblRow['incentive_obf'])?'':$jobElectricalQuoteTblRow['incentive_obf']}}:</td><td>{{''}}</td><td style="background-color:#FFFFCC;">Energy Reduction:</td><td>{{''}}</td></tr>

                  <!--#9 -->            <tr><td style="background-color:#FFFFCC;">Employee Assigned</td><td>{{$jobElectricalQuoteTblRow['gpg_employee_assigned']}}</td><td colspan="2">&nbsp;</td><td style="background-color:#FFFFCC;">Average House of Operations (AHO):</td><td>{{$jobElectricalQuoteTblRow['aho']}}</td><td style="background-color:#FFFFCC;">Five Year Energy Savings:</td><td>{{''}}</td></tr>

                  <!--#10 -->            <tr><td colspan="12"><b> Custom Fields</b></td></tr>
                  <!--#11 -->            <tr><td  style="background-color:#FFFFCC;">Reservation Number:</td><td>{{$jobElectricalQuoteTblRow['reservation_number']}}</td><td  style="background-color:#FFFFCC;">Tax ID number:</td><td>{{$jobElectricalQuoteTblRow['tax_id']}}</td><td  style="background-color:#FFFFCC;">Tax Status:</td><td>{{$jobElectricalQuoteTblRow['tax_status']}}</td><td  style="background-color:#FFFFCC;">Building Square footage:</td><td>{{$jobElectricalQuoteTblRow['building_square']}}</td><td  style="background-color:#FFFFCC;">Average House of Operations (AHO)::</td><td>{{$jobElectricalQuoteTblRow['aho']}}</td><td colspan="2"></td></tr>
                                      </tbody>
                                     </table>
                                   </section>
                 <section id="no-more-tables" style="overflow-x: scroll;">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead class="cf">
                                          <tr>
                                            <th rowspan="2">&nbsp;DEL&nbsp;</th>
                                            <th rowspan="2">&nbsp;&nbsp;Edit&nbsp;&nbsp;</th>
                                            <th rowspan="2">Location</th>
                                            <th colspan="2">&nbsp;Fixture Information and Product Numbers&nbsp;</th>
                                            <th rowspan="2">&nbsp;Notes&nbsp;</th> 
                                            <th rowspan="2">&nbsp;Pro. Fix.<br />Document&nbsp;</th> 
                                            <th colspan="2">&nbsp;Lamps per Fixture&nbsp;</th>
                                            <th colspan="2">&nbsp;Fixture Quantity&nbsp;</th>
                                            <th rowspan="2">Fixtures Left</th>
                                            <th colspan="2">Fixture Watts</th>
                                            <th colspan="2">Each Fixture kW</th>
                                            <th colspan="2">Measure Annual kWh</th>
                                            <th>Annual kWh Savings</th>
                                            <th colspan="2">Annual Hours of Operation</th>
                                            <th colspan="2">Annual Energy Multiplier:<input type="text" name="annual_energy_cost" id="annual_energy_cost" value="<? echo ($Constants['annual_energy_cost']!= NULL)?$Constants['annual_energy_cost']:'';?>" class="textRed" style="width:30px;" /><br/>Annual Energy Cost **</th>
                                            <th rowspan="2">Annual Energy Bill**&nbsp;Saving&nbsp;</th>
                                            <th rowspan="2">Unit Fixture&nbsp;Cost&nbsp;</th>
                                            <th rowspan="2">Line Total Cost</th>
                                            <th rowspan="2">Material Mark Up:<input type="text" name="material_mark_up" id="material_mark_up" size="5" value="<? echo ($Constants['material_mark_up']!= NULL)?$Constants['material_mark_up']:'';?>" class="textRed" style="width:30px"/><br/>Customer Invoice Material Cost </th>
                                            <th rowspan="2">Labor Hours Multiplier:<input type="text" name="labor_hours_multiplier" id="labor_hours_multiplier" size="5"  value="<? echo ($Constants['labor_hours_multiplier']!= NULL)?$Constants['labor_hours_multiplier']:'';?>" class="textRed" style="width:30px" />Unit Labor Hours</th>
                                            <th rowspan="2">Total LineLabor&nbsp;Hours&nbsp;</td>
                                            <th rowspan="2">&nbsp;Labor Rate:<input type="text" name="labor_rate" id="labor_rate" size="5" value="<? echo ($Constants['labor_rate']!= NULL)?$Constants['labor_rate']:'';?>" class="textRed" style="width:30px" /><br />Line Labor&nbsp;</th>
                                            <th colspan="4">Rebate Calculataions</th>
                                            <th colspan="4">Rebate Calculations</th>
                                            <th colspan="4">Rebate Calculations</th>
                                            <th colspan="4">Rebate Calculations</td>
                                            <th colspan="2">Incentive Rate / kWh Saved:<input type="text" name="incentive_rate" id="incentive_rate" size="5" value="<? echo ($Constants['incentive_rate']!= NULL)?$Constants['incentive_rate']:'';?>" class="textRed" style="width:30px" /><br />Incentive Calculations</th>
                                            <th rowspan="2">Exclude <br />Incentive</th>
                                          </tr>
                                          <tr bgcolor="#F2F2F2">
                                            <th>EXISTING</th>
                                            <th>PROPOSED</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro.&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th> 
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>&nbsp;Ex.&nbsp;</th>
                                            <th>&nbsp;Pro&nbsp;</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Quantity</th>
                                            <th>Measure</th>
                                            <th>Rebate Amount</th>
                                            <th>Rebate Subtotal</th>
                                            <th>Per Unit Incentive</th>
                                            <th>Line Item Incentive</th>
                                          </tr>   
                                        </thead>
                                        <tbody class="cf">
                                        @if(!empty($getJESQ))
                                          <?php $i=1;?>
                                          @foreach($getJESQ as $getJESQRow)
                                            <?php $i++;?>
                                            <tr>
                                              <td></td>
                                              <td></td>
                                              <td>{{$getJESQRow['location']}}</td>
                                              <td>{{$getJESQRow['fixture_name']}}</td>
                                              <td>{{$getJESQRow['fixture_name_pro']}}</td>
                                              <td>{{strlen($getJESQRow['notes'])>0?substr($getJESQRow['notes'],0,15)."...":""}}</td>
                                              <td>
                                                  @if(empty($getJESQRow['docs']))
                                                     {{'-'}}
                                                  @else
                                                    {{$getJESQRow['gpg_job_electrical_subquote_proposed_fixtures_id']}}   
                                                  @endif       
                                              </td>
                                              <td>{{$getJESQRow['lamps_fixture_quantity_ex']}}</td>
                                              <td>{{$getJESQRow['lamps_fixture_quantity_pro']}}</td>
                                              <td>{{$getJESQRow['fixture_quantity_ex']}}</td>
                                              <td>{{$getJESQRow['fixture_quantity_pro']}}</td>
                                              <td>
                                              @if(isset($installed_fix_arr[$getJESQRow['id']]))    
                                                {{ number_format(($getJESQRow['fixture_quantity_pro']-$installed_fix_arr[$getJESQRow['id']]),2)}}
                                              @else 
                                                {{$getJESQRow['fixture_quantity_pro']}}
                                              @endif  
                                              </td>
                                              <td><div id="fixture_watts_exDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="fixture_watts_proDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="each_fixture_kw_exDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="each_fixture_kw_proDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="measure_annual_kwh_exDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="measure_annual_kwh_proDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="annual_kwh_savings_proDIV_<?php echo $i; ?>"></div></td>
                                              <td>{{$getJESQRow['annual_hours_of_operation_ex']}}</td>
                                              <td>{{$getJESQRow['annual_hours_of_operation_pro']}}</td>
                                              <td><div id="annual_energy_cost_exDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="annual_energy_cost_proDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="annual_energy_bill_savingDIV_<?php echo $i; ?>"></div></td>
                                              <td>{{$getJESQRow['unit_fixture_cost']}}</td>
                                              <td><div id="line_total_costDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="material_costDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="unit_labor_hourDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="total_line_labor_hourDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="line_laborDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_quantity1DIV_<?php echo $i; ?>"></div></td>
                                              <td>
                                              @if(!empty($getJESQRow['rebate1Detail']))
                                                {{$getJESQRow['rebate1Detail']['rebate_measure'].'/'.$getJESQRow['rebate1Detail']['rebate_start_year']}}
                                              @endif
                                              </td>
                                              <td><div id="rebate_amount1DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_subtotal1DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_quantity2DIV_<?php echo $i; ?>"></div></td>
                                              <td>
                                              @if(!empty($getJESQRow['rebate2Detail']))
                                               {{$getJESQRow['rebate2Detail']['rebate_measure'].'/'.$getJESQRow['rebate2Detail']['rebate_start_year']}}
                                              @endif
                                              </td>
                                              <td><div id="rebate_amount2DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_subtotal2DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_quantity3DIV_<?php echo $i; ?>"></div></td>
                                              <td>
                                              @if(!empty($getJESQRow['rebate3Detail']))
                                               {{$getJESQRow['rebate3Detail']['rebate_measure'].'/'.$getJESQRow['rebate3Detail']['rebate_start_year']}}
                                              @endif
                                              </td>
                                              <td><div id="rebate_amount3DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_subtotal3DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_quantity4DIV_<?php echo $i; ?>"></div></td>
                                              <td>
                                              @if(!empty($getJESQRow['rebate4Detail']))
                                               {{$getJESQRow['rebate4Detail']['rebate_measure'].'/'.$getJESQRow['rebate4Detail']['rebate_start_year']}}
                                              @endif
                                              </td>
                                              <td><div id="rebate_amount4DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="rebate_subtotal4DIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="per_unit_incentiveDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="line_item_incentiveDIV_<?php echo $i; ?>"></div></td>
                                              <td><div id="_<?php echo $i; ?>"></div></td>
                                            </tr>
                                            <tr id="Totals_row" class="sum_totals">
                                              <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                              <td id="lamps_fixture_quantity_exTotal"></td>
                                              <td id="lamps_fixture_quantity_proTotal"></td>
                                              <td id="fixture_quantity_exTotal"></td>
                                              <td id="fixture_quantity_proTotal"></td>
                                              <td></td>
                                              <td></td><td></td><td></td><td></td>
                                              <td id="measure_annual_kwh_exTotal"></td>
                                              <td id="measure_annual_kwh_proTotal"></td>
                                              <td id="annual_kwh_savings_proTotal"></td>
                                              <td></td><td></td>
                                              <td id="annual_energy_cost_exTotal"></td>
                                              <td id="annual_energy_cost_proTotal"></td>
                                              <td id="annual_energy_bill_savingTotal"></td>
                                              <td></td>
                                              <td id="line_total_costTotal"></td>
                                              <td id="material_costTotal"></td>
                                              <td></td>
                                              <td id="total_line_labor_hourTotal"></td>
                                              <td id="line_laborTotal"></td>
                                              <td id="rebate_quantity1Total"></td>
                                              <td></td><td></td>
                                              <td id="rebate_subtotal1Total"></td>
                                              <td id="rebate_quantity2Total"></td>
                                              <td></td><td></td>
                                              <td id="rebate_subtotal2Total"></td>
                                              <td id="rebate_quantity3Total"></td>
                                              <td></td><td></td>
                                              <td id="rebate_subtotal3Total"></td>
                                              <td id="rebate_quantity4Total"></td><td></td><td></td>
                                              <td id="rebate_subtotal4Total"></td>
                                              <td></td>
                                              <td id="line_item_incentiveTotal"></td>
                                              <td></td>
                                            </tr>
                                          @endforeach
                                        @endif
                                        </tbody>
                                     </table>
                                   </section>
