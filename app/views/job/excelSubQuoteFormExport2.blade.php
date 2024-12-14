<table border="0" cellspacing="1" cellpadding="2" id="new_tblMain"  width="100%" >
                              <tr>
                              </tr>
                              <tr>
                              </tr>
                              <tr>
                              	<td colspan="4">Site Information</td>
                                <td colspan="4"></td>
                              </tr>
                              <tr class="info_tr">
                              	<td colspan="4">Name</td>
                                <td colspan="4"><? echo $jobElectricalQuoteTblRow['GPG_customer_id']; ?></td>
                              </tr>
                              <tr class="info_tr">
                              	<td colspan="4" >Vendor / Contractor:</td>
                                <td colspan="4">Global Power Group</td>
                              </tr>
                              <tr >
                              	<td colspan="4">Project Sponsor:</td>
                                <td colspan="4"></td>
                              </tr>
                              <tr >
                              	<td colspan="4">Address:</td>
                                <td colspan="4"><? echo $jobElectricalQuoteTblRow['customer_info']['address'] ?></td>
                              </tr>
                              <tr >
                              	<td colspan="4">Climate Zone:</td>
                                <td colspan="4"></td>
                              </tr>
                              <tr >
                              	<td colspan="4">Year Built:</td>
                                <td colspan="4"></td>
                              </tr>
                              <tr >
                              	<td colspan="4">Number of Floors:</td>
                                <td colspan="4"></td>
                              </tr>
                              <tr >
                              	<td colspan="4">Number or Rooms:</td>
                                <td colspan="4"></td>
                              </tr>
                              <tr >
                              	<td colspan="4">Square feet of Occupied Space:</td>
                                <td colspan="4"></td>
                              </tr>
                              <tr >
                              	<td></td>
                                <td></td>
                              </tr>
                              
                              <tr >
                                <td ></td>
                                <td ></td>
                              <td colspan="2" valign="top"></td>
                              <td colspan="3" valign="top"></td>
                              <td></td>
    <td></td>
  
	</tr>
      <tr >
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="5" align="center" >Existing</td>
        <td colspan="5" align="center" >Proposed</td>
        <td colspan="4" align="center" >Energy Savings</td>
        <td colspan="2" align="center" >EEBR</td>
        <td colspan="3" align="center" >EEBI</td>
        <td colspan="10" align="center" >Economics</td>
      </tr>
      <tr >
        <td width=220 colspan="3" rowspan="2" align="center" valign="middle"  style='width:200pt' >Location</td>
        <td rowspan="2" align="center" >Annual Hours of Operation</td>
        <td colspan="2"  align="center" >Lighting Description</td>
        <td rowspan="2"  align="center" >Qty</td>
        <td rowspan="2"  align="center" >Fixture/Lamp Input Power<br>[Watts]</td>
        <td rowspan="2"  align="center" >Total Power<br>
          [kW]</td>
        <td colspan="2"  align="center" >Lighting Description</td>
        <td rowspan="2"  align="center" >Qty</td>
        <td rowspan="2"  align="center" >Fixture/Lamp Input Power<br>[Watts]</td>
        <td rowspan="2"  align="center" >Total Power<br>[kW]</td>
        <td rowspan="2"  align="center"  >Demand Savings<br>[Watts/fixture]</td>
        <td rowspan="2"  align="center"  >Annual Electric Savings<br>[kWH/fixture]</td>
        <td rowspan="2"  align="center" >Total Demand<br>[kW]</td>
        <td rowspan="2"  align="center"  >Total Electric Savings<br>[kW]</td>
        <td rowspan="2"  align="center" >Rebate<br>[$/unit]</td>
        <td rowspan="2"  align="center" >Total Rebate</td>
        <td rowspan="2"  align="center"  >kW Incentive Rate<br>[$/kW]</td>
        <td rowspan="2"  align="center"  >kWh Incentive Rate<br>[$/kWh]</td>
        <td rowspan="2"  align="center" >Total Incentive</td>
        <td rowspan="2"  align="center" >Installation Cost Per Fixture<br>[$/fixture]</td>
        <td rowspan="2"  align="center"  >Unit Cost Per Fixture<br>[$/fixture]</td>
        <td rowspan="2"  align="center" >Total Installation Cost</td>
        <td rowspan="2"  align="center" >Total Unit Cost</td>
        <td rowspan="2"  align="center" >Total Measure Cost</td>
        <td colspan="2" rowspan="2"  align="center"  >Total<br>(Max Incentive or Max Rebate)</td>
        <td rowspan="2"  align="center"  >Net Cost</td>
        <td rowspan="2"  align="center" >Total Annual Cost Savings</td>
        <td rowspan="2"  align="center"  >Est Payback Period<br>[Years]</td>
  </tr>
                              <tr  >
                                <td  align="center" >[Fixture Type]</td>
                                <td  align="center" >[Other Notes]</td>
                                <td  align="center" >[Fixture Type]</td>
                                <td  align="center" >[Other Notes]</td>
                              </tr>
					     <?php 
                          $rowNum =0;
						  $fmlaRowNum = 17;
						  $start = $fmlaRowNum;
						  $start++;
						  ?>
						@foreach($job_electrical_subquote as $row)
						<?php
							  $rowNum ++;
							  $fmlaRowNum++;
							  if($Constants['labor_hours_multiplier']!= NULL){
								  $LaborHoursMultiplier = $Constants['labor_hours_multiplier'];
							  }else{
								 $LaborHoursMultiplier=  '_LaborHoursMultiplier';
							  }
							   if($Constants['incentive_rate_kw']!= NULL){
								  $incentive_rate_kw = $Constants['incentive_rate_kw'];
							  }else{
								 $incentive_rate_kw =  '_IncentiveRateKw';
							  }
							  if($Constants['incentive_rate']!= NULL){
								  $incentive_rate = $Constants['incentive_rate'];
							  }else{
								 $incentive_rate =  '_IncentiveRate';
							  }
							  $labor_hours_multiplier = $LaborHoursMultiplier;
							   $rebate_id = "";
								if($row['gpg_rebate1_id']!=0)
									$rebate_id .= "'".$row['gpg_rebate1_id']."',";
								if($row['gpg_rebate2_id']!=0)
									$rebate_id .= "'".$row['gpg_rebate2_id']."',";
								if($row['gpg_rebate3_id']!=0)
									$rebate_id .= "'".$row['gpg_rebate3_id']."',";
								if($row['gpg_rebate4_id']!=0)
									$rebate_id .= "'".$row['gpg_rebate4_id']."',";
								if(strlen($rebate_id)>0)
									$rebate_id = substr($rebate_id,0,strlen($rebate_id)-1);
								if(($rebate_id!='0') and ($rebate_id != NULL) and (strlen($rebate_id)>0))	
									$rebate = @mysql_result(mysql_query("SELECT SUM(rebate_amount) FROM gpg_rebate WHERE id IN (".$rebate_id.")"),0,0);
								else
									$rebate = "-";
								
								
							  ?>
  <tr  >
    <td   align="center"  ><? echo $rowNum;?></td>
    <td   align="center" class="xl118_b" ></td>
    <td  width=91 style='width:171pt' align="center"  ><? echo $row['location'];?></td>
    <td  align="center"  x:num><? echo $row['annual_hours_of_operation_pro'];?></td>
    <td  align="center" ><? echo $existing_arr['fixture_name'];?></td>
    <td  align="center" >&nbsp;</td>
    <td  align="center"  style='width:65pt'><?=$row['fixture_quantity_ex']?></td>
    <td  align="center"  ><? echo $existing_arr['watts'];?></td>
    <td  align="center"  x:num x:fmla="=(H<?=$fmlaRowNum?>/1000)"></td>
    <td  align="center" ><? echo $proposed_arr['fixture_name'];?></td>
    <td  align="center" >&nbsp;</td>
    
    <td  align="center" ><?=$row['fixture_quantity_pro']?></td>
    <td  align="center"  ><? echo $proposed_arr['watts'];?></td>
    <td  align="center"></td>
    <td  align="center" ></td>
    <td  align="center"  ></td>
    <td  align="center"  ></td>
    <td  align="center"  ></td>
    <td  align="center"  ></td>
    <td  align="center" ></td>
   <td  align="center"  ></td>
   <td  align="center"  ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   <td  align="center" ></td>
   
    </tr>
	@endforeach
                        
    <tr>
    </tr>  
    <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="3" class="text_bold">EEBI Measures TOTAL</td>
        <td ></td>
        <td></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
    </tr>
    <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="3" class="text_bold">EEBR Measures TOTAL</td>
        <td ></td>
        <td></td>
        <td ></td>
        <td ></td>
        <td ></td>
		<td ></td>
    </tr>
    <tr >
    </tr>
       <? if($fmlaRowNum!=8){?>
        <tr>
    <td align="center"  ></td>
    <td align="center"  >Totals</td>
    <td  ></td>
    <td align="center" ></td>
    <td colspan="2"  align="center" ></td>
    <td  align="center"></td>
    <td  align="center" ></td>
    <td  align="center" ></td>
    <td colspan="2"  align="center" ></td>
    <td  align="center"></td>
    <td  align="center" ></td>
    <td  align="center" ></td>
    <td  align="center" ></td>
    <td  align="center" ></td>
    <td  align="center"></td>
    <td  align="center"></td>
    <td  align="center"></td>
    <td  align="center"></td>
    <td  align="center" ></td>
    <td  align="center" ></td>
	<td  align="center" ></td>
    <td  align="center" ></td>
    <td  align="center" ></td>
    <td align="center" ></td>
    <td align="center" ></td>
    <td align="center" ></td>
    <td align="center"  ></td>
    <td  align="center"></td>
    <td  align="center"></td>
    <td  align="center"></td>
    <td  align="center"></td>
    
    </tr>  

    <? } ?>
    </table>