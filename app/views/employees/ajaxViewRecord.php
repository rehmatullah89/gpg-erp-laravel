<?php
$path = realpath('.');
?><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#999999"><table width="100%" border="0" align="center" cellspacing="1" >
      <tbody>
        <tr valign="top">
          <td height="30" colspan="3" bgcolor="#F5F5F5" class="schrift_ueberschrift"  ><span style="color: #c10000"><font style="font-size: 16pt">VIEW EMPLOYEE </font></span> <span style="color: #57A6C7"><font style="font-size: 16pt">(<?php echo (@$full_name==""?$employee[0]->name:$full_name); ?>)</font> </span> </td>
        </tr>
        <tr >
          <td width="29%" height="20" align="right" bgcolor="#FFFFFF" >Login Name :</td>
          <td width="26%" bgcolor="#FFFFFF"><strong> &nbsp;<?php echo $employee[0]->login ?></strong></td>
          <td width="45%" rowspan="16" align="center" bgcolor="#FFFFFF"><p>Member Photo:<br />
                  <img src="<?php echo $path ?>/images/<?php echo @$employee[0]->pic ?>" width="113" height="150" /> </p></td>
        </tr>
        <tr>
          <td height="20" align="right" bgcolor="#F5F5F5" >Employee Type  :</td>
          <td bgcolor="#F5F5F5">&nbsp;<strong></strong></td>
        </tr>
        <tr>
          <td height="20" align="right" bgcolor="#FFFFFF" >Real Name :</td>
          <td bgcolor="#FFFFFF"><strong>&nbsp;<?php echo ucwords($employee[0]->name) ?></strong></td>
        </tr>
        <tr>
          <td height="20" align="right" bgcolor="#F5F5F5" >Date Of Birth  :</td>
          <td bgcolor="#F5F5F5"><strong>&nbsp;<?php echo date("d M, Y",strtotime($employee[0]->dob)) ?></strong></td>
        </tr>
        <tr>
          <td height="20" align="right" bgcolor="#FFFFFF" >Status  :</td>
          <td bgcolor="#FFFFFF"><strong>&nbsp;<?php echo EmployeeController::$UserStatus[$employee[0]->status] ?></strong></td>
        </tr>
        <tr>
          <td height="20" align="right" bgcolor="#F5F5F5" >Regular Pay :</td>
          <td bgcolor="#F5F5F5"><strong>&nbsp;<?php echo '$'.number_format($employee[0]->reg_pay,2) ?></strong></td>
        </tr>
        
        <tr>
          <td height="20" align="right" bgcolor="#FFFFFF" >DOB :</td>
          <td bgcolor="#FFFFFF"><strong>&nbsp;<?php echo date("m/d/Y",strtotime($employee[0]->dob)); ?></strong></td>
          </tr>
        <tr>
          <td height="20" align="right" bgcolor="#F5F5F5" >Salaried :</td>
          <td bgcolor="#F5F5F5"><strong>&nbsp;<?php echo ($employee[0]->salaried==1?'Yes':'No'); ?></strong></td>
        </tr>
        <tr>
          <td height="20" align="right" bgcolor="#FFFFFF" >Email  :</td>
          <td bgcolor="#FFFFFF"><strong>&nbsp;<?php echo $employee[0]->email; ?></strong></td>
          </tr>
        <tr>
          <td height="20" align="right" bgcolor="#F5F5F5" >Phone :</td>
          <td bgcolor="#F5F5F5"><strong>&nbsp;<?php echo $employee[0]->phone; ?></strong></td>
          </tr>
        <tr>
          <td height="20" align="right" bgcolor="#FFFFFF" >Created On :</td>
          <td bgcolor="#FFFFFF"><strong>&nbsp;<?php echo date("m/d/Y",strtotime($employee[0]->created_on)); ?></strong></td>
        </tr>
        <tr>
          <td height="20" align="right" bgcolor="#F5F5F5" >Edited On  :</td>
          <td bgcolor="#F5F5F5"><strong>&nbsp;<?php echo date("m/d/Y",strtotime($employee[0]->modified_on)); ?></strong></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
</table>