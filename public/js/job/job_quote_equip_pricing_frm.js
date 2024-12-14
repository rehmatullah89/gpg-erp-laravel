
  function ChangeRowValue(tblName,itt,newRow){
  //alert('tblName:'+tblName+'itt:'+itt+'newRow:'+newRow);
    /*Tables: Equipment,Labor,Misc*/
    var quantity='';
    var cost='';
    var margin_percent='';
    var sell_price='';
    var total_cost='';
    var total_sale_price = 0;
    var total_cost2= 0;
    var total_margin= 0;
    var sales_tax='';
    var total_quantity=0;
    var tbl = document.getElementById(tblName);
    var totalRow = tbl.rows.length;
      if($('#'+tblName+'_quantity_'+itt).val() == ''){
          quantity = 0;
      }else{
        quantity =$('#'+tblName+'_quantity_'+itt).val();
      }
      if($('#'+tblName+'_cost_'+itt).val() == ''){
          cost = 1;
      }else{
          cost =$('#'+tblName+'_cost_'+itt).val();
      }
      
      if($('#'+tblName+'_margin_percent_'+itt).val() == ''){
          margin_percent = 0;
      }else{
          margin_percent =$('#'+tblName+'_margin_percent_'+itt).val()/100;
      }  
      sell_price =  (quantity*cost)/(1-margin_percent);
      $('#'+tblName+'_sell_price_'+itt).val(roundNumber(sell_price,2));
      // Total Cost
      total_cost =  (quantity*cost);
      $('#'+tblName+'_total_cost_'+itt).val(roundNumber(total_cost,2));
      $('#'+tblName+'_margin_'+itt).val(roundNumber((sell_price - total_cost),2));
      for(var i=1 ; i<=totalRow-2 ; i++){
        if(typeof $('#'+tblName+'_sell_price_'+i).val() != 'undefined' && $('#'+tblName+'_sell_price_'+i).val() !=='') {
          total_sale_price =parseFloat(total_sale_price) + parseFloat(clear_num($('#'+tblName+'_sell_price_'+i).val()));
        }
        if(typeof $('#'+tblName+'_total_cost_'+i).val() != 'undefined' && $('#'+tblName+'_total_cost_'+i).val() !=='') {
          total_cost2 =parseFloat(total_cost2) + parseFloat(clear_num($('#'+tblName+'_total_cost_'+i).val()));
        }
        if(typeof $('#'+tblName+'_margin_'+i).val() != 'undefined' && $('#'+tblName+'_margin_'+i).val() !=='') {
		  total_margin = parseFloat(total_margin) + parseFloat(clear_num($('#'+tblName+'_margin_'+i).val()));
        }       
        if(tblName=="Labor"){
          if(typeof $('#'+tblName+'_quantity_'+i).val() != 'undefined' && $('#'+tblName+'_quantity_'+i).val() != ''){           
            total_quantity = parseFloat(clear_num(roundNumber(parseFloat(total_quantity) + parseFloat(clear_num($('#'+tblName+'_quantity_'+i).val())),2)));
          }
        }
      }     
  
    $('#'+tblName+'_sell_price_total').val(roundNumber(parseFloat(total_sale_price),2));    
    $('#'+tblName+'_total_cost_total').val(roundNumber(parseFloat(total_cost2),2));
    $('#'+tblName+'_margin_total').val(roundNumber(parseFloat(total_margin),2));    
    
    if(tblName=="Labor"){
      $('#'+tblName+'_quantity_total').val(parseFloat(total_quantity));
    }
    if($('#Equipment_sell_price_total').val()==''){
      $('#Equipment_sell_price_total').val("0.00");
    }
    if($('#Labor_sell_price_total').val()==''){
      $('#Labor_sell_price_total').val("0.00");
    }
    if($('#Misc_sell_price_total').val()==''){
      $('#Misc_sell_price_total').val("0.00");
    }
    $('#Grand_total_sale_price').val(roundNumber(parseFloat(clear_num($('#Equipment_sell_price_total').val()))+parseFloat(clear_num($('#Labor_sell_price_total').val()))+parseFloat(clear_num($('#Misc_sell_price_total').val())),2));    
    if($('#Equipment_total_cost_total').val()==''){
      $('#Equipment_total_cost_total').val("0.00");
    }
    if($('#Labor_total_cost_total').val()==''){
      $('#Labor_total_cost_total').val("0.00");
    }
    if($('#Misc_total_cost_total').val()==''){
      $('#Misc_total_cost_total').val("0.00");
    }
    $('#Grand_total_cost').val(roundNumber(parseFloat(clear_num($('#Equipment_total_cost_total').val()))+parseFloat(clear_num($('#Labor_total_cost_total').val()))+parseFloat(clear_num($('#Misc_total_cost_total').val())),2));
    if($('#Equipment_margin_total').val()==''){
      $('#Equipment_margin_total').val(0);
    }
    if($('#Labor_margin_total').val()==''){
      $('#Labor_margin_total').val(0);
    }
    if($('#Misc_margin_total').val()==''){
      $('#Misc_margin_total').val(0);
    }
    $('#Grand_total_margin').val(roundNumber(parseFloat(clear_num($('#Equipment_margin_total').val())) + parseFloat(clear_num($('#Labor_margin_total').val())) + parseFloat(clear_num($('#Misc_margin_total').val())),2));
    calculate_tax ();  
 }
 
 function clear_num(str)
 {	
	if(str == null)
      str= '';
	var string = str.toString();
	string =  string.replace(',','');
    return string.replace('$','');
 }
 
 function calculate_tax (){
  var sales_tax = 0;
  var total_Taxable_amount = 0;
  var itt =0 ;
    if($('#sales_tax').val() !=''){
        sales_tax = clear_num($('#sales_tax').val());
    }
    //Equipment_include_tax
     while(1){
      itt++;
      if(typeof $('#Equipment_include_tax_'+itt).val() != 'undefined'){
	    if($('#Equipment_include_tax_'+itt).checked == true){
          if(typeof $('#Equipment_sell_price_'+itt).val() != 'undefined' && $('#Equipment_sell_price_'+itt).val()!=''){
            total_Taxable_amount = total_Taxable_amount + parseFloat(clear_num($('#Equipment_sell_price_'+itt).val())) ;
          }
        } 
      }else{
            itt = 0;
            break;
      }
    }
	//Labor_include_tax
    while(1){
      itt++;
      if(typeof $('#Labor_include_tax_'+itt).val() != 'undefined'){
        if($('#Labor_include_tax_'+itt).checked == true){
          if(typeof $('#Labor_sell_price_'+itt).val() != 'undefined' && $('#Labor_sell_price_'+itt).val()!=''){
                total_Taxable_amount = total_Taxable_amount + parseFloat(clear_num($('#Labor_sell_price_'+itt).val())) ;
          }
        }
      }else{
        itt = 0;
        break;
      }
    }
     //Misc_include_tax
     while(1){
      itt++;
      if(typeof $('#Misc_include_tax_'+itt).val() != 'undefined'){
        if($('#Misc_include_tax_'+itt).checked == true){
          if(typeof $('#Misc_sell_price_'+itt).val() != 'undefined' && $('#Misc_sell_price_'+itt).val()!=''){
            total_Taxable_amount = total_Taxable_amount + parseFloat(clear_num($('#Misc_sell_price_'+itt).val())) ;
          }
        }
      }else{
        itt = 0;
        break;
      }
     }
    $('#sale_price_tax').val(roundNumber(parseFloat(total_Taxable_amount*sales_tax/100),2));
    $('#cost_tax').val(roundNumber(total_Taxable_amount*sales_tax/100,2));
    $('#tax_total_sale_price').val(roundNumber(parseFloat(clear_num($('#Grand_total_sale_price').val())) + parseFloat(clear_num($('#sale_price_tax').val())),2));
    $('#tax_total_cost').val(roundNumber(parseFloat(clear_num($('#Grand_total_cost').val())) + parseFloat(clear_num($('#cost_tax').val())),2));
    $('#tax_total_margin').val(roundNumber(parseFloat(clear_num($('#Equipment_margin_total').val())) + parseFloat(clear_num($('#Labor_margin_total').val())) + parseFloat(clear_num($('#Misc_margin_total').val())),2));
  }
  function calc_margin(tblName,iteration,numx,swit)
  {
  switch(swit)
  {
    case 1: // sell price entered
      sell_price = $('#'+tblName+'_sell_price_cost_' + iteration).val();
      cost = $('#'+tblName+'_cost_'+iteration).val();
	  if(cost=="")
        cost = 0;
      if(sell_price=="")
        sell_price = 0;
        
      margin = sell_price - cost;
      if(sell_price != 0)
      {
        margin = (margin/sell_price)*100;
        margin = roundNumber(margin,2);
        console.log(margin);
		//margin = margin.substr(1,margin.length)
      }
      else
        margin = 0;
      
      $('#'+tblName+'_margin_percent_' + iteration).val(margin);
      ChangeRowValue(tblName,iteration,numx)  
    break;
    case 2: // margin entered
      margin = $('#'+tblName+'_margin_percent_' + iteration).val();
      margin = margin/100;
      if(margin==1)
        margin = 0;
      margin = 1 - margin;
      cost = $('#'+tblName+'_cost_' + iteration).val();
      if(cost=="")
        cost = 0; 
      sell_price = cost/margin;
      sell_price = roundNumber(sell_price,4);
      $('#'+tblName+'_sell_price_cost_' + iteration).val(sell_price);
      ChangeRowValue(tblName,iteration,numx)  
    
    break;
  }
}
 function roundNumber(rnum,rlength) {
   format = 1;
   if(rlength==3 || rlength==4)
    format = 0;
   //rlength = 2;
    if (rnum > 8191 && rnum < 10485) {
      rnum = rnum-5000;
      var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
      newnumber = newnumber+5000;
    } else {
      var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
    }
    if(format==1)
      return (newnumber);
    else
      return newnumber;
  }

  function checkAllConsolidate(obj){
    itt = 0;
      if(obj.checked==true){
        // loop for Equipment
        while(1){
          itt++;
          if(typeof $('#Equipment_consolidate_'+itt).attr('name') != 'undefined'){
                $('#Equipment_consolidate_'+itt).attr('checked', true);
                $('#Equipment_consolidate_'+itt).removeAttr("disabled");
          }else{
            itt = 0;
            break;
          }
        }
        // loop for Labor
        while(1){
          itt++;
          if(typeof $('#Labor_consolidate_'+itt).attr('name') != 'undefined'){
            $('#Labor_consolidate_'+itt).attr('checked', true);
            $('#Labor_consolidate_'+itt).removeAttr("disabled");
          } else {
            itt = 0;
            break;
          }
        }
        // loop for Misc
        while(1){
          itt++;
          if(typeof $('#Misc_consolidate_'+itt).attr('name') != 'undefined'){
            $('#Misc_consolidate_'+itt).attr('checked', true);
            $('#Misc_consolidate_'+itt).removeAttr("disabled");
		  } else {
            itt = 0;
            break;
          }
        }
      }else {
         // loop for Equipment
        while(1){
          itt++;
          if(typeof $('#Equipment_consolidate_'+itt).attr('name') != 'undefined'){
            $('#Equipment_consolidate_'+itt).checked = false;
            $('#Equipment_consolidate_'+itt).disabled = 'disabled';
          } else {
            itt = 0;
            break;
          }
        }
        // loop for Labor
        while(1){
          itt++;
          if(typeof $('#Labor_consolidate_'+itt).attr('name') != 'undefined'){
            $('#Labor_consolidate_'+itt).checked = false;
            $('#Labor_consolidate_'+itt).disabled = 'disabled';
          } else {
            itt = 0;
            break;
          }
        }
        // loop for Misc
        while(1){
          itt++;
          if(typeof $('#Misc_consolidate_'+itt).attr('name') != 'undefined'){
            $('#Misc_consolidate_'+itt).checked = false;
            $('#Misc_consolidate_'+itt).disabled = 'disabled';
          } else {
            itt = 0;
            break;
          }
        }
        }
  }
  function setGlobalMargin(val){
		itt = 0;
		if(confirm("It will change Margin percentage of all line items temporarily.\nPlease SAVE save the form to apply changes permanently!")){    
			  // loop for Equipment
			  while(1){
				  itt ++;
				  if(typeof $('#Equipment_margin_percent_'+itt).val() != 'undefined'){
						if($('#equipment_margin').val()=="")
						if($('#Equipment_quantity_'+itt).val() != "" ||  $('#Equipment_description_'+itt).val() != "")
							$('#Equipment_margin_percent_'+itt).val(val);
						else
						$('#Equipment_margin_percent_'+itt).val("");
				  } else{
					 for(i=1 ; i < itt-1 ; i++){ 
					  ChangeRowValue("Equipment",i,1);
					 }
					  itt = 0;
					  break;
				  }
			  }
			
			  // loop for Labor
			  while(1){
				  itt ++;
				  if(typeof $('#Labor_margin_percent_'+itt).val() != 'undefined'){
					  	if($('#labor_margin').val()=="")
						if($('#Labor_quantity_'+itt).val() != "" ||  $('#Labor_description_'+itt).val() != "")
							$('#Labor_margin_percent_'+itt).val(val);
						else
							$('#Labor_margin_percent_'+itt).val("");
					}else{
					  for(i=1 ; i < itt-1 ; i++){ 
					  ChangeRowValue("Labor",i,1);
					 }
					  itt = 0;
					  break;
				  }
			  }
			  // loop for Misc
			  while(1){
				  itt ++;
				  if(typeof $('#Misc_margin_percent_'+itt).val() != 'undefined'){
					  if($('#misc_margin').val()=="")
					  if($('#Misc_quantity_'+itt).val() != "" ||  $('#Misc_description_'+itt).val() != "")
							$('#Misc_margin_percent_'+itt).val(val);
						else
							$('#Misc_margin_percent_'+itt).val("");
					//	ChangeRowValue("Misc",itt,1);
				  		
				  } else {
					  for(i=1 ; i < itt-1 ; i++){ 
					  ChangeRowValue("Misc",i,1);
					 }
					  itt = 0;
					  break;
				  }
			  }
		  
			calculate_tax ();
		}
	}
function set_total(show)
{
	  var txt_wages = 0;
	  var txt_disposal = 0;
	  var txt_cleanup = 0;
	  var txt_lift_rental = 0;
	  var total = 0;
	  var txt_material_margin = 0;
	 
	  txt_wages = $('#txt_wages').val().replace(new RegExp(',', 'g'),'');
	  txt_disposal = $('#txt_disposal').val().replace(new RegExp(',', 'g'),'');
	  txt_cleanup = $('#txt_cleanup').val().replace(new RegExp(',', 'g'),'');
	  txt_lift_rental = $('#txt_lift_rental').val().replace(new RegExp(',', 'g'),'');
	  if(isNaN(txt_wages))
		txt_wages = 0;
	  if(isNaN(txt_disposal))
		txt_disposal = 0;
	  if(isNaN(txt_cleanup))
		txt_cleanup = 0;
	  if(isNaN(txt_lift_rental))
		txt_lift_rental = 0;
	  $('#txt_sum_line_total').html($('#line_total_costTotal').html());
	  sum_material_cost = $('#material_costTotal').html();
	  var rebate_total = (($('#rebate_subtotal1Total').html())*1)+(($('#rebate_subtotal2Total').html())*1)+(($('#rebate_subtotal3Total').html())*1)+(($('#rebate_subtotal4Total').html())*1);
	  var sum_annual_energy_bill_saving = $('#annual_energy_bill_savingTotal').html();
	  var energy_reduction = $('#measure_annual_kwh_proTotal').html() / $('#measure_annual_kwh_exTotal').html();
	  var sum_incentive_total = $('#line_item_incentiveTotal').html();
	  $('#txt_sum_line_labor').html($('#line_laborTotal').html());
	  total = (txt_wages*1) + (txt_disposal*1) + (txt_cleanup*1) + (txt_lift_rental*1);
	  txt_material_margin = $('#txt_sum_line_total').html().replace(new RegExp(',', 'g'),'') * $("#material_mark_up").val();
	  $('#txt_material_margin').html(isNaN(roundNumber(txt_material_margin,2))?'0':roundNumber(txt_material_margin,2));
	  $('#txt_sale_price_material').html(roundNumber(($('#txt_sum_line_total').html().replace(new RegExp(',', 'g'),'')*1) + ($('#txt_material_margin').html()*1),2));
	  $('#txt_total').html(roundNumber(total,2));
	  $('#txt_additional_cost').html(roundNumber(total,2));
	  $('#txt_incentive_total').html(roundNumber(sum_incentive_total,2));
	  $('#txt_incentive_rate_per_kw').html(roundNumber($('#incentive_rate').val(),2));
	  temp_red_inc = ($('#txt_incentive_total').html()/(roundNumber($('#incentive_rate').val(),2) * $('#reduction_constant').val()))*100;
	  temp_red_inc = isNaN(temp_red_inc)?'0':temp_red_inc;
	  temp_red_inc = !isFinite(temp_red_inc)?'0':temp_red_inc
	  $('#txt_reduction_incentive').html(roundNumber(temp_red_inc,2));
	  temp_q_red = $('#txt_incentive_total').html()/(roundNumber($('#incentive_rate').val(),2)*$('#reduction_constant').val());
	  temp_q_red = isNaN(temp_q_red)?'0':temp_q_red;
	  temp_q_red = !isFinite(temp_q_red)?'0':temp_q_red;
	  $('#txt_qualifying_kw_reduction').html(roundNumber(temp_q_red,2));
	  $('#txt_rebate_total').html(roundNumber(rebate_total,2));
	  $('#sdge_incentive_obf').html(roundNumber((roundNumber(rebate_total,2) + roundNumber($('#txt_incentive_total').html(),2) + roundNumber($('#txt_reduction_incentive').html(),2)) * $('#incentive_obf').val(),2));
	  $('#sdge_incentive_obf').html((isNaN($('#sdge_incentive_obf').html()))?"0":$('#sdge_incentive_obf').html());
	  $('#estimated_utility_incentive').html((isNaN($('#sdge_incentive_obf').html())?"0":$('#sdge_incentive_obf').html()));
	  $('#sum_annual_energy_bill_saving').html(sum_annual_energy_bill_saving);
	  temp_energy_red = isNaN(roundNumber(1-energy_reduction,2))?"0":roundNumber(1-energy_reduction,2);
	  $('#txt_energy_reduction').html(!isFinite(temp_energy_red)?'0':temp_energy_red);
	  $('#year_energy_saving').html(roundNumber($('#sum_annual_energy_bill_saving').html() * 5,2));
	  salestax = $('#txt_sales_tax');
	  if(salestax.val().length==0)
		salestax.val()='0';
	  $('#txt_applicable_sales_tax').html(roundNumber(((salestax.val()/100) * sum_material_cost),2));
	  set_sale_price_total();
}
function set_sale_price_total()
{
	sale_price = $('#txt_sale_price_material').html().replace(new RegExp(',', 'g'),'');
	add_cost = $('#txt_additional_cost').html().replace(new RegExp(',', 'g'),'');
	sum_line_labor = $('#txt_sum_line_labor').html().replace(new RegExp(',', 'g'),'');
	app_sale_tax = $('#txt_applicable_sales_tax').html().replace(new RegExp(',', 'g'),'');
	$('#txt_sale_price_total').html(roundNumber(((sale_price*1)+(add_cost*1)+(sum_line_labor*1)+(app_sale_tax*1)),2));
	$('#txt_total_project_cost').html(roundNumber($('#txt_sale_price_total').html(),2));
	$('#amount_paid_obf').html(roundNumber(($('#txt_total_project_cost').html() - $('#estimated_utility_incentive').html()),2));
	temp_pay_months = roundNumber(($('#amount_paid_obf').html() / $('#sum_annual_energy_bill_saving').html())*12,2);
	$('#payback_in_months').html(isNaN(temp_pay_months)?'0':temp_pay_months);
	$('#payback_in_months').html(!isFinite($('#payback_in_months').html())?"0":$('#payback_in_months').html());
	temp_pay_years = roundNumber(($('#amount_paid_obf').html() / $('#sum_annual_energy_bill_saving').html()),2);
	$('#payback_in_years').html(!isFinite(temp_pay_years)?'0':temp_pay_years);
	sync_constants();
}

function sync_constants()
{
	$('#new_annual_energy_cost').val($('#annual_energy_cost').val());
	$('#new_material_mark_up').val($('#material_mark_up').val());
	$('#new_labor_hours_multiplier').val($('#labor_hours_multiplier').val());
	$('#new_labor_rate').val($('#labor_rate').val());
	$('#new_incentive_rate').val($('#incentive_rate').val());
}
function setRebateSubtotal(obj,irt,str_new){	
	obj.value = trimStr(obj.value);
	var reb_amt4 = $('#'+str_new+'rebate_amount4_'+irt).val();
	if(reb_amt4 == '' || reb_amt4=='undefined')
		reb_amt4 = 1;
	$('#'+str_new+'rebate_subtotal4_'+irt).val(reb_amt4 * obj.value);
}
// creats new row and set the values onchange event
  function RowValueChange(itt,newRow,underscore,saved_values){
	  if(underscore!="DIV_")
	  	underscore = "_";
	 	newRow='new_';
	
		var annual_hours_of_operation_pro;
		var annual_hours_of_operation_ex;
		var fixture_quantity_pro;
		var fixture_quantity_ex;
		var each_fixture_kw_pro;
		var each_fixture_kw_ex;

	    if(check_value_to_get($('#'+newRow+'annual_hours_of_operation_pro'+underscore+itt)) == ''){
		   annual_hours_of_operation_pro = 1;
		}else{
		    annual_hours_of_operation_pro =check_value_to_get($('#'+newRow+'annual_hours_of_operation_pro'+underscore+itt));
		} 
		  
		if(check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)) == ''){
		   fixture_quantity_pro = 1;
		}else{
		   fixture_quantity_pro = check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt));
		}
	  
		if(check_value_to_get($('#'+newRow+'annual_hours_of_operation_ex'+underscore+itt)) == ''){
		    annual_hours_of_operation_ex = 1;
		}else{
		    annual_hours_of_operation_ex = check_value_to_get($('#'+newRow+'annual_hours_of_operation_ex'+underscore+itt));
		} 
		if(check_value_to_get($('#'+newRow+'fixture_quantity_ex'+underscore+itt)) == ''){
		   fixture_quantity_ex = 1;
		}else{
		   fixture_quantity_ex = check_value_to_get($('#'+newRow+'fixture_quantity_ex'+underscore+itt));
		}
			
		if (check_value_to_get($('#'+newRow+'each_fixture_kw_ex'+underscore+itt)) !='') {
			check_value_to_get($('#'+newRow+'measure_annual_kwh_ex'+underscore+itt),"set",roundNumber(check_value_to_get($('#'+newRow+'each_fixture_kw_ex'+underscore+itt)) * annual_hours_of_operation_ex * fixture_quantity_ex,2));
		}
		
		if (check_value_to_get($('#'+newRow+'each_fixture_kw_pro'+underscore+itt)) !=''){
			check_value_to_get($('#'+newRow+'measure_annual_kwh_pro'+underscore+itt),"set",roundNumber(check_value_to_get($('#'+newRow+'each_fixture_kw_pro'+underscore+itt)) * annual_hours_of_operation_pro * fixture_quantity_pro,2));
		}
			  		  
		if (isNaN(parseFloat(check_value_to_get($('#'+newRow+'measure_annual_kwh_ex'+underscore+itt))) - parseFloat(check_value_to_get($('#'+newRow+'measure_annual_kwh_pro'+underscore+itt))))) {
			check_value_to_get($('#'+newRow+'annual_kwh_savings_pro'+underscore+itt),"set",'');
		}else {
			check_value_to_get($('#'+newRow+'annual_kwh_savings_pro'+underscore+itt),"set",roundNumber(parseFloat(check_value_to_get($('#'+newRow+'measure_annual_kwh_ex'+underscore+itt))) - parseFloat(check_value_to_get($('#'+newRow+'measure_annual_kwh_pro'+underscore+itt))),3));
		}
			
		if (check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)) !=''){
			check_value_to_get($('#'+newRow+'total_line_labor_hour'+underscore+itt),"set",roundNumber(check_value_to_get(($('#'+newRow+'fixture_quantity_pro'+underscore+itt))) * (check_value_to_get($('#'+newRow+'unit_labor_hour'+underscore+itt))),2));
		}
		if (check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)) !=''){
			check_value_to_get($('#'+newRow+'line_total_cost'+underscore+itt),"set",roundNumber((check_value_to_get($('#'+newRow+'unit_fixture_cost'+underscore+itt)))*(check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt))),2));
		}
		
		if(check_value_to_get($('#'+newRow+'line_total_cost'+underscore+itt)) !=''){
			check_value_to_get($('#'+newRow+'material_cost'+underscore+itt),"set",roundNumber((check_value_to_get($('#'+newRow+'line_total_cost'+underscore+itt)))*(parseFloat((check_value_to_get($('#'+newRow+'material_mark_up'))))+1),2));
		}
			var rebate4_qty ="";
			// rebate 1
			if(check_value_to_get($('#'+newRow+'rebate_measure1'+underscore+itt)) !=''){

				if(!rebate1Type)
				{
					var rebate1Type = $('#'+newRow+'rebate_measure1'+underscore+itt).val().split('~~');
				}
					if(rebate1Type[2]=='Fixture'){
					check_value_to_get($('#'+newRow+'rebate_quantity1'+underscore+itt),"set", check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)));
					}
					if(rebate1Type[2]=='Lamp'){
					check_value_to_get($('#'+newRow+'rebate_quantity1'+underscore+itt),"set", roundNumber(check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)) * check_value_to_get($('#'+newRow+'lamps_fixture_quantity_pro'+underscore+itt)),2));
					}
					
					if(saved_values)
					cal_rebate_detail(newRow,itt,1,underscore,rebate1Type,saved_values);
			}
			// rebate 2
			if(check_value_to_get($('#'+newRow+'rebate_measure2'+underscore+itt)) !=''){
			
				if(!rebate2Type){
					var rebate2Type = $('#'+newRow+'rebate_measure2'+underscore+itt).val().split('~~');
				}
					if(rebate2Type[2]=='Fixture'){
					check_value_to_get($('#'+newRow+'rebate_quantity2'+underscore+itt),"set",check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)));
					}
					if(rebate2Type[2]=='Lamp'){
					check_value_to_get($('#'+newRow+'rebate_quantity2'+underscore+itt),"set",roundNumber(check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)) * check_value_to_get($('#'+newRow+'lamps_fixture_quantity_pro'+underscore+itt)),2));
					}

					if(saved_values)
					cal_rebate_detail(newRow,itt,2,underscore,rebate2Type,saved_values);
			}
			// rebate 3
			if(check_value_to_get($('#'+newRow+'rebate_measure3'+underscore+itt)) !=''){
				if(!rebate3Type){
					var rebate3Type = $('#'+newRow+'rebate_measure3'+underscore+itt).val().split('~~');
					}
					if(rebate3Type[2]=='Fixture'){
					check_value_to_get($('#'+newRow+'rebate_quantity3'+underscore+itt),"set",check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)));
					}
					if(rebate3Type[2]=='Lamp'){
					check_value_to_get($('#'+newRow+'rebate_quantity3'+underscore+itt),"set", roundNumber(check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)) * check_value_to_get($('#'+newRow+'lamps_fixture_quantity_pro'+underscore+itt)),2));
					}
					if(saved_values)
					cal_rebate_detail(newRow,itt,3,underscore,rebate3Type,saved_values);
				}
				// rebate 4

			if(check_value_to_get($('#'+newRow+'rebate_measure4'+underscore+itt)) !=''){

				if(!rebate4Type){
					var rebate4Type = $('#'+newRow+'rebate_measure4'+underscore+itt).val().split('~~');
					}
					check_value_to_get($('#'+newRow+'rebate_quantity4'+underscore+itt),"set",rebate4_qty);
					if(saved_values)
					{

						cal_rebate_detail(newRow,itt,4,underscore,rebate4Type,saved_values);
					}
				}
			if(check_value_to_get($('#'+newRow+'ExFix'+underscore+itt))!="" && check_value_to_get($('#'+newRow+'ProFix'+underscore+itt))!="" && check_value_to_get($('#'+newRow+'ExFix'+underscore+itt))!="Exnewfixture" && check_value_to_get($('#'+newRow+'ProFix'+underscore+itt))!="Pronewfixture")
			{
				exval = check_value_to_get($('#'+newRow+'ExFix'+underscore+itt));
				arrexval = exval.split(/~/);
				proval = check_value_to_get($('#'+newRow+'ProFix'+underscore+itt));
				arrproval = proval.split(/~/);
			}
			else
			{

			}
			
			if($('#'+newRow+'rebate_amount1'+underscore+itt)){
				var amountArr1 = check_value_to_get($('#'+newRow+'rebate_amount1'+underscore+itt)).split(/~~/);
				if(check_value_to_get($('#'+newRow+'rebate_amount1'+underscore+itt)) !='' && check_value_to_get($('#'+newRow+'rebate_quantity1'+underscore+itt)) !='') { 

				check_value_to_get($('#'+newRow+'rebate_subtotal1'+underscore+itt),"set",roundNumber(amountArr1[0] * check_value_to_get($('#'+newRow+'rebate_quantity1'+underscore+itt)),2)) ;
				}
		    }
			if($('#'+newRow+'rebate_amount2'+underscore+itt)){

				var amountArr2 = check_value_to_get($('#'+newRow+'rebate_amount2'+underscore+itt)).split(/~~/);
			if(check_value_to_get($('#'+newRow+'rebate_amount2'+underscore+itt)) !='' && check_value_to_get($('#'+newRow+'rebate_quantity2'+underscore+itt)) !='') { 

			check_value_to_get($('#'+newRow+'rebate_subtotal2'+underscore+itt),"set",roundNumber(amountArr2[0] * check_value_to_get($('#'+newRow+'rebate_quantity2'+underscore+itt)),2)) ;
			}
			}
			if($('#'+newRow+'rebate_amount3'+underscore+itt)){
				var amountArr3 = check_value_to_get($('#'+newRow+'rebate_amount3'+underscore+itt)).split(/~~/);
				if(check_value_to_get($('#'+newRow+'rebate_amount3'+underscore+itt)) !='' && check_value_to_get($('#'+newRow+'rebate_quantity3'+underscore+itt)) !='') { 
	
				check_value_to_get($('#'+newRow+'rebate_subtotal3'+underscore+itt),"set",roundNumber(amountArr3[0] * check_value_to_get($('#'+newRow+'rebate_quantity3'+underscore+itt)),2)) ;
				}
			}
			if($('#'+newRow+'rebate_amount4'+underscore+itt)){
				var amountArr4 = check_value_to_get($('#'+newRow+'rebate_amount4'+underscore+itt)).split(/~~/);
				if(check_value_to_get($('#'+newRow+'rebate_amount4'+underscore+itt)) !='' && check_value_to_get($('#'+newRow+'rebate_quantity4'+underscore+itt)) !='') { 
	
				check_value_to_get($('#'+newRow+'rebate_subtotal4'+underscore+itt),"set",roundNumber(amountArr4[0] * check_value_to_get($('#'+newRow+'rebate_quantity4'+underscore+itt)),2)) ;
				}
			}
			
			if (check_value_to_get($('#'+newRow+'measure_annual_kwh_ex'+underscore+itt)) != ''){
			check_value_to_get($('#'+newRow+'annual_energy_cost_ex'+underscore+itt),"set",roundNumber(($('#'+newRow+'annual_energy_cost').val())* (check_value_to_get($('#'+newRow+'measure_annual_kwh_ex'+underscore+itt))),2));
			}
			if (check_value_to_get($('#'+newRow+'measure_annual_kwh_pro'+underscore+itt)) !=''){
			check_value_to_get($('#'+newRow+'annual_energy_cost_pro'+underscore+itt),"set",roundNumber(($('#'+newRow+'annual_energy_cost').val())* (check_value_to_get($('#'+newRow+'measure_annual_kwh_pro'+underscore+itt))),2));
			}
			if(check_value_to_get($('#'+newRow+'annual_energy_cost_pro'+underscore+itt))!='' && check_value_to_get($('#'+newRow+'annual_energy_cost_ex'+underscore+itt))!=''){
			
			check_value_to_get($('#'+newRow+'annual_energy_bill_saving'+underscore+itt),"set",roundNumber(check_value_to_get($('#'+newRow+'annual_energy_cost_ex'+underscore+itt)) - check_value_to_get($('#'+newRow+'annual_energy_cost_pro'+underscore+itt)),2));
			}
			if (check_value_to_get($('#'+newRow+'total_line_labor_hour'+underscore+itt)) != '') {
			check_value_to_get($('#'+newRow+'line_labor'+underscore+itt),"set",roundNumber(check_value_to_get($('#'+newRow+'total_line_labor_hour'+underscore+itt)) * check_value_to_get($('#'+newRow+'labor_rate')),2));
			}
			var temp_incentive_qty;
			if (check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)) != '') {
			temp_incentive_qty = roundNumber(((check_value_to_get($('#'+newRow+'measure_annual_kwh_ex'+underscore+itt)) - check_value_to_get($('#'+newRow+'measure_annual_kwh_pro'+underscore+itt))) / check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt))) * check_value_to_get($('#'+newRow+'incentive_rate')),2 );
			if(Trim(check_value_to_get($('#'+newRow+'rebate_amount1'+underscore+itt))) != '' || Trim(check_value_to_get($('#'+newRow+'rebate_amount2'+underscore+itt))) != '' || Trim(check_value_to_get($('#'+newRow+'rebate_amount3'+underscore+itt))) != '' || Trim(check_value_to_get($('#'+newRow+'rebate_amount4'+underscore+itt))) != '')
			{
				incentinve_quantity = temp_incentive_qty;
			}
			else
				check_value_to_get($('#'+newRow+'per_unit_incentive'+underscore+itt),"set",roundNumber(temp_incentive_qty,2));
			}
			if (check_value_to_get($('#'+newRow+'per_unit_incentive'+underscore+itt))!= '') {
			if(check_value_to_get($('#'+newRow+'rebate_amount1'+underscore+itt)) != '' || check_value_to_get($('#'+newRow+'rebate_amount2'+underscore+itt)) != '' || check_value_to_get($('#'+newRow+'rebate_amount3'+underscore+itt)) != ''  || check_value_to_get($('#'+newRow+'rebate_amount4'+underscore+itt)) != '')
			{
				line_item_incentive = roundNumber(temp_incentive_qty * check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)),2);
			}
			else	
				check_value_to_get($('#'+newRow+'line_item_incentive'+underscore+itt),"set",roundNumber(check_value_to_get($('#'+newRow+'per_unit_incentive'+underscore+itt)) * check_value_to_get($('#'+newRow+'fixture_quantity_pro'+underscore+itt)),2)) ;
			}
  
 }
 function check_value_to_get(obj,return_set,value)
  {
	  if(return_set=="set"){
		  	obj.val(value);
	  }
	  else{
		  if(obj.val())
			  return obj.val();
		  else
		  	return obj.html();
	  }
  }
  function Trim(String)
{
	if (String == null)
		return (false);

	return RTrim(LTrim(String));
}

function RTrim(String)
{
	var i = 0;
	var j = String.length - 1;

	if (String == null)
		return (false);

	for(j = String.length - 1; j >= 0; j--)
	{
		if (String.substr(j, 1) != ' ' &&
			String.substr(j, 1) != '\t')
		break;
	}

	if (i <= j)
		return (String.substr(i, (j+1)-i));
	else
		return ('');
}
function LTrim(String)
{
	var i = 0;
	var j = String.length - 1;

	if (String == null)
		return (false);

	for (i = 0; i < String.length; i++)
	{
		if (String.substr(i, 1) != ' ' &&
		    String.substr(i, 1) != '\t')
			break;
	}

	if (i <= j)
		return (String.substr(i, (j+1)-i));
	else
		return ('');
}
function trimStr(sInString) {
  sInString = sInString.replace( /^\s+/g, "" );// strip leading
  return sInString.replace( /\s+$/g, "" );// strip trailing
}