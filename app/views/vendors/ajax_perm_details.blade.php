
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
-->
<?php
    if($urow!="") {
            $full_name=$urow[0]->name;

            $chkPerm = $urow[0]->perm;

            $prm = explode(",",$chkPerm);

            $perm['bill'] = "Show Billing Info. Panel";
            $perm['jobsite'] = "Show Job Site Info. Panel";
            $perm['other'] = "Show Other Info. Panel";
            $perm['scope'] = "Show Scope of Work Panel";
            $perm['workdone'] = "Show Actual Work Completed Panel";
            $perm['milestones'] = "Show Project Milestones Panel";
            $perm['recommendation'] = "Show Recommendation Panel";
    } 
?>
</style>
{{ Form::open(array('before' => 'csrf' ,'url'=>route('vendors/updateVendorPermissions'), 'id'=>'customerForm', 'files'=>true, 'method' => 'post')) }}
<input type="hidden" name="id" value="<?php echo $urow[0]->id?>">
<input type="hidden" name="update" value="yes">
       
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#999999"><table width="100%" border="0" align="center" cellspacing="1" >
      <tbody>
        <tr valign="top">
          <td height="30" colspan="2" bgcolor="#F5F5F5" class="schrift_ueberschrift"  ><span style="color: #c10000"><font style="font-size: 16pt">SET PERMISSIONS</font></span> <span style="color: #57A6C7"><font style="font-size: 16pt">(<?php echo $full_name; ?>)</font> </span> </td>
         </tr>
         <?php while (list($key,$value)= each($perm)) { 
		 ?>
        <tr >
          <td height="30" align="right" bgcolor="#FFFFFF" ><input type="checkbox" <?php echo ($prm[0]=='all' || in_array($key,$prm)? 'checked="checked"':'');  ?> name="pChk[]"  value="<?php echo $key ?>" /></td>
          <td bgcolor="#FFFFFF"><strong> &nbsp;<?php echo $value ?></strong></td>
          </tr>
          
       <?php } ?>
       
       <tr >
          <td height="30" bgcolor="#FFFFFF" colspan="2" align="center"><input type="submit" name="update" value="Update"></td>
          </tr>
      </tbody>
        </table>
    </td>
  </tr>
</table>
{{ Form::close() }}