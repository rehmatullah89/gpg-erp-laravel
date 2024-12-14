<form onsubmit="return Validate_upass();" id="frmNewUser" method="post" action="ajax/employees/changeEmployeePassword">
 <input type="hidden" value="u_uppass" name="action">
 <input type="hidden" value="244" name="id">

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


<table border="0" width="100%" align="center">
  <tbody>
    <tr valign="top">
      <td colspan="2"><span style="padding-top: 25px;"><span style="color: #c10000"><font style="font-size: 16pt">CHANGE PASSWORD</font> </span> <span style="color: #57A6C7"> <font style="font-size: 16pt">(<b><?php echo $full_name?></b>)</font> </span></span></td>
    </tr>
    	<tr>
      <td width="44%" align="right"><p>New Password*: &nbsp;&nbsp;</p></td>
      <td width="56%">
        <div align="left">
          <input type="password" id="newpass" name="newpass">      
        </div></td>
    </tr>
    <tr>
      <td width="44%" nowrap="nowrap" align="right">Re-enter New Password*:&nbsp;&nbsp;&nbsp; </td>
      <td width="56%">
        <div align="left">
          <input type="password" id="repass" name="repass">      
        </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><font size="4">&nbsp; </font></div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input type="submit" name="Submit" value="Change Password">
      </div></td>
    </tr>
	  </tbody>
</table>
 <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
</form>
<script type="text/javascript">
    function Validate_upass() {
        
        var newpass = $.trim($("#newpass").val());
        var repass = $.trim($("#repass").val());
        var error_str = '';

        if (newpass.length < 1)
        {
                error_str+="Error : New Password is required<br>";
        }
        if (repass.length < 1)
        {
                error_str+="Error : Re-enter New Password<br>";
        }
        if (newpass != repass)
        {
                error_str+="Error : New Password and Re-entered Password doesnot match<br>";
        }
        
        if (error_str !="") {
                 $("#ERR_DISP").show();
                 $("#Error_Label").html(error_str) ;
                 return false;
        } 
        
        
	return true;
}
</script>