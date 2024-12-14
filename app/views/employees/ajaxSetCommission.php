<form onsubmit="return Validate_rate();" id="frmNewUser" method="post" action="/ajax/employees/setCommission">
 <input type="hidden" value="" name="type">
 <input type="hidden" value="<?php echo $id?>" name="id">
 <input type="hidden" value="u_commission" name="action">

<table cellspacing="0" cellpadding="2" border="0" width="100%">
  <tbody><tr>
    <td>
	<!--bgcolor="#000000"-->
	<table cellspacing="1" cellpadding="0" border="0" width="100%" align="center" style="display:none" id="ERR_DISP">
  <tbody><tr>
   <!-- bgcolor="#000000"-->
    <td>
	 <!-- bgcolor="#FFCC00"-->
	 <table cellspacing="0" cellpadding="5" border="0" width="100%">
      <tbody><tr>
        <td width="100%" class="error"><label class="ELabel" id="Error_Label"></label></td>
      </tr>
      
    </tbody></table></td>
  </tr>
</tbody></table>
</td>
  </tr>
</tbody></table>


<table cellspacing="2" cellpadding="2" border="0" width="100%" align="center"> 
  <tbody>
    <tr valign="top">
      <td colspan="2"><span style="padding-top: 25px;"><span style="color: #c10000"><font style="font-size: 16pt">ADDING Commission</font> </span> <span style="color: #57A6C7"> <font style="font-size: 16pt">(<b><?php echo $full_name?></b>)</font> </span></span></td>
    </tr>
    	<tr>
	  <td align="right">&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
      <td align="right">Current Sales Commission: &nbsp;&nbsp;</td>
	  <td><div align="left"><strong><?php echo(@$commission[0]->sales_commission)? number_format($commission[0]->sales_commission,2): '0.00';?>%</strong></div></td>
	  </tr>
	<tr>
      <td align="right">Current Estimate Commission: &nbsp;&nbsp;</td>
	  <td><div align="left"><strong><?php echo(@$commission[0]->estimate_commission)? number_format($commission[0]->estimate_commission,2): '0.00';?>%</strong></div></td>
	  </tr>
	<tr>
	  <td align="right">Current Contract Commission: &nbsp;&nbsp;</td>
	  <td><div align="left"><strong><?php echo(@$commission[0]->contract_sales_commission)? number_format($commission[0]->contract_sales_commission,2): '0.00';?>%</strong></div></td>
	  </tr>
	<tr>
      <td align="right">Started From:&nbsp; &nbsp;</td>
	  <td align="left"><strong><?php echo(@$commission[0]->start_date)? date("m/d/Y",strtotime($commission[0]->start_date)): '';?></strong></td>
	  </tr>
          <tr>
              <td colspan="2">&nbsp;</td>
          </tr>   
	<tr>
      <td width="36%" align="right"><p>Sales Commission*: &nbsp;&nbsp;</p></td>
      <td width="64%">
        <div align="left">
          <input type="text" id="sales_commission" name="sales_commission">      
        %</div></td>
    </tr>
	<tr>
      <td width="36%" align="right"><p>Estimate Commission*: &nbsp;&nbsp;</p></td>
      <td width="64%">
        <div align="left">
          <input type="text" id="estimate_commission" name="estimate_commission">      
        %</div></td>
    </tr>
	<tr>
	  <td align="right"><p>Contract Commission: &nbsp;&nbsp;</p></td>
	  <td><div align="left">
	    <input type="text" id="contract_sales_commission" name="contract_sales_commission">
	    %</div></td>
	  </tr>
    <tr>
      <td align="right">Start Date*:&nbsp; &nbsp;</td>
      <td align="left"><input value="<?php echo date("m/d/Y")?>" id="startDate" name="startDate" class="default-date-picker"> </td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><font size="4">&nbsp; </font></div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input type="submit" name="submit" value="SAVE COMMISSION">
      </div></td>
    </tr>
	  </tbody>
</table>
 <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
</form>
<script language="javascript">
function Validate_rate()
{
    var estimate_commission = $.trim($('#estimate_commission').val());
    var sales_commission = $.trim($('#sales_commission').val());
    var startDate = $.trim($('#startDate').val());
    
    var error_str = '';

    if (estimate_commission.length < 1)
    {
            error_str+="Error : Estimate Commission Required<br>";
    }
    if (sales_commission.length < 1)
    {
            error_str+="Error : Sales Commission Required<br>";
    }
    if (startDate.length < 1)
    {
            error_str+="Error : Start Date is Required<br>";
    }

    if (error_str) {
        $("#ERR_DISP").show();
        $("#Error_Label").html(error_str);
        return false;
    }

    return true;
}
</script>