<form action="ajax/employees/getDeductionsHTML" method="post" id="frmNewUser" onsubmit="return(Validate_upass());">
    <input name="action" value="u_deductions" type="hidden">
    <input name="id" value="<?php echo $id?>" type="hidden">

<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tbody><tr>
    <td>
	<!--bgcolor="#000000"-->
	<table id="ERR_DISP" style="display:none" align="center" width="100%" border="0" cellpadding="0" cellspacing="1">
  <tbody><tr>
   <!-- bgcolor="#000000"-->
    <td>
	 <!-- bgcolor="#FFCC00"-->
	 <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tbody><tr>
        <td class="error" width="100%"><label id="Error_Label" class="ELabel"></label></td>
      </tr>
      
    </tbody></table></td>
  </tr>
</tbody></table>
</td>
  </tr>
</tbody></table>


    <table align="center" width="100%" border="0">
        <tbody>
            <tr valign="top">
                <td colspan="2">
                    <span style="padding-top: 25px;font-size: 16pt;color: #c10000">
                        Manage Deductions
                        <span style="color: #57A6C7;font-size: 16pt"> 
                            (<b><?php echo $full_name?></b>)</span></span></td>
            </tr>
                   
            <?php
//            echo "<pre>";
//            print_r($pw_wages_rates_type);
//            exit;
            $total = 0;
            foreach ( $pw_wages_rates_type as $key => $val ) {
                $total += @$arrvals[$key]; 
                ?>
                    <tr>
                        <td width="44%" align="right"><P><?php echo ucwords($val) ?>: &nbsp;&nbsp;</P></td>
                        <td width="56%">
                            <div align="left">
                                <input name="deduction_value_<?php echo $key;?>" type="text" id="deduction_value_<?php echo $key?>" value="<?=@$arrvals[$key] ?>" onKeyUp="set_totals(this)" /><?php if($val == "pension"){?>
                 <span style="padding-left:1px">%</span><?php  
                     }?>
                            </div></td>
                    </tr>    
                <?php
            }
    
            ?>    
            
            
            <tr>
                    <td align="right">Total Deductions: &nbsp;&nbsp;</td>
                    <td id="totals">$<?php echo number_format($total,2)?></td>
                </tr>
                <tr>
                    <td colspan="2"><div align="center">
                            <input value="Update" name="Submit" type="submit">
                        </div></td>
                </tr>
        </tbody>
    </table>
    <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
</form>
<script type="text/javascript">
    function roundNumber(rnum,rlength) {
        format = 1;
        if(rlength==3)
            format = 0;
        rlength = 2;
        if (rnum > 8191 && rnum < 10485) {
            rnum = rnum-5000;
            var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
            newnumber = newnumber+5000;
        } else {
            var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
        }
        if(format==1)
            return addCommas(newnumber);
        else
        {
            if(!isNaN(newnumber))
                return newnumber;
            else
                return "";
        }
    }
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
	currency = '$';	
        x2 = x.length > 1 ? '.' + (x[1].length == 1 ? x[1] + "0":x[1]) : '.00';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        //alert(currency)
        return currency+x1 + x2;
    }
 

    function set_totals(obj)
    {
        total_amount = 0;
        elems = new Array();
        count = 0;
<?
foreach ( $pw_wages_rates_type as $key => $val ) {
    ?>elems[count] = <?=$key?>;count++;
    <?
}
?>
                for(loop=0; loop<elems.length;loop++)
                {
                    //	alert(obj.value.substr(obj.value.length-1,obj.value.length))
                    if(obj.value.substr(obj.value.length-1,obj.value.length)!="." && obj.value.substr(obj.value.length-2,obj.value.length)!=".0")
                    {
                        if(roundNumber($('#deduction_value_'+elems[loop]).val(),3)!=0)
                            $('#deduction_value_'+elems[loop]).val(roundNumber($('#deduction_value_'+elems[loop]).val(),3)); 
                        else
                            $('#deduction_value_'+elems[loop]).val('');
                    }
                    total_amount += $('#deduction_value_'+elems[loop]).val()*1;
                }
                //	alert(roundNumber(total_amount,3))
                if(roundNumber(total_amount,3)!="")
                    $('#totals').html(roundNumber(total_amount,2)); 
                else
                    $('#totals').html("$0.00") ;
            }
</script>